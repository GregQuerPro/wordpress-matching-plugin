<?php

class MatchingQueryBuilder
{

    public function buildQuery()
    {
        global $wpdb;

        $searchesOptions = $wpdb->get_results('SELECT name, sign FROM user_searches_options WHERE to_hide = 0');

        $condition = '';

        for ($i = 0; $i < count($searchesOptions); $i++) {
            // var_dump(count($searchesOptions));
            // var_dump($i);
            if ($searchesOptions[$i]->sign === 'max') {
                $searchesOptions[$i]->sign = '<=';
            } else if ($searchesOptions[$i]->sign === 'min') {
                $searchesOptions[$i]->sign = '>=';
            }
            if ($i !== count($searchesOptions) - 1) {
                if ($searchesOptions[$i]->name === 'Rayon') {
                    $condition .= '(6371 * acos(cos(radians(us.latitude)) * cos(radians(mb.latitude))
                    * cos(radians(mb.longitude) - radians(us.longitude))
                    + sin(radians(us.latitude)) * sin(radians(mb.latitude)))) <= CAST(us.rayon AS UNSIGNED) AND ';
                } else {
                    $condition .= 'CAST(mb.' . lcfirst($searchesOptions[$i]->name) . ' AS UNSIGNED) ' .  $searchesOptions[$i]->sign . ' CAST(us.' . lcfirst($searchesOptions[$i]->name) . ' AS UNSIGNED) AND ';
                }
            } else {
                if ($searchesOptions[$i]->name === 'Rayon') {
                    $condition .= '(6371 * acos(cos(radians(us.latitude)) * cos(radians(mb.latitude))
                    * cos(radians(mb.longitude) - radians(us.longitude))
                    + sin(radians(us.latitude)) * sin(radians(mb.latitude)))) <= CAST(us.rayon AS UNSIGNED)';
                } else {
                    $condition .= 'CAST(mb.' . lcfirst($searchesOptions[$i]->name) . ' AS UNSIGNED) ' .  $searchesOptions[$i]->sign . ' CAST(us.' . lcfirst($searchesOptions[$i]->name) . ' AS UNSIGNED)';
                }
            }
        }
        // var_dump($condition);

        // var_dump($searchesOptions);


        // CAST(mb.price AS UNSIGNED) < CAST(us.price AS UNSIGNED) 
        // AND CAST(mb.surface AS UNSIGNED) >= CAST(us.surface AS UNSIGNED)


        $sql = "
            WITH cte AS (
                SELECT p.ID AS id, p.post_title AS name, p.post_name AS slug, mb.latitude, mb.longitude,
                    (6371 * acos(cos(radians(us.latitude)) * cos(radians(mb.latitude))
                    * cos(radians(mb.longitude) - radians(us.longitude))
                    + sin(radians(us.latitude)) * sin(radians(mb.latitude)))) AS rayon,
                    mb.price,
                    1 - (mb.price / us.price) AS price_delta,
                    mb.surface,
                    (mb.surface / us.surface) - 1 AS surface_delta,
                    mb.room,
                    (mb.room / us.room) - 1 AS room_delta,
                    us.user_id AS userID,
                    us.nbr_criteria AS number_criteria,
                    1 - ((6371 * acos(cos(radians(us.latitude)) * cos(radians(mb.latitude))
                    * cos(radians(mb.longitude) - radians(us.longitude))
                    + sin(radians(us.latitude)) * sin(radians(mb.latitude)))) / us.rayon) AS rayon_delta
                FROM {$wpdb->prefix}posts p
                INNER JOIN {$wpdb->prefix}mb_properties mb ON p.ID = mb.id
                INNER JOIN user_searches us ON
                $condition  
            )
            SELECT DISTINCT cte.id, cte.name, cte.slug,
                    ROUND(cte.rayon) AS rayon,
                    cte.price,
                    cte.surface,
                    cte.room,
                    ROUND(((cte.rayon_delta + cte.price_delta + cte.surface_delta + cte.room_delta) / number_criteria) * 100) AS score,
                    wu.user_nicename,
                    wu.user_email
            FROM cte
            INNER JOIN wp_users wu ON userID = wu.ID
            ORDER BY score DESC;
        ";

        $results = $wpdb->get_results($sql);

        // cr??er une structure de donn??es pour stocker les r??sultats
        $users = [];

        // parcourir les r??sultats et remplir la structure de donn??es
        foreach ($results as $result) {
            // v??rifier si l'utilisateur existe d??j?? dans la structure de donn??es
            if (!array_key_exists($result->user_nicename, $users)) {
                // si l'utilisateur n'existe pas, cr??er un nouvel ??l??ment pour lui
                $users[$result->user_nicename] = [
                    "name" => $result->user_nicename,
                    "email" => $result->user_email,
                    "properties" => []
                ];
            }

            // ajouter les donn??es de la propri??t?? ?? la liste des propri??t??s de l'utilisateur
            $users[$result->user_nicename]["properties"][] = [
                "id" => $result->id,
                "name" => $result->name,
                "rayon" => $result->rayon,
                "price" => $result->price,
                "surface" => $result->surface,
                "room" => $result->room,
                "score" => $result->score,
                "slug" => "localhost/propriete/$result->slug/"
            ];
        }

        // var_dump($users);

        var_dump($sql);
    }
}
