<?php

// Query String Test
// ?_geolocalisation=48.539927%2C2.660817%2C100%2CMelun%2C%20Seine-et-Marne%2C%20France&_price=2000%2C1407000&_surface=235%2C550&_room=2&price%5Bsign%5D=>&price%5Bimportant%5D%5Bweight%5D=10&price%5Bessentiel%5D%5Bweight%5D=20&price%5Bessentiel%5D%5Bchoice%5D=on&surface%5Bsign%5D=<&surface%5Bimportant%5D%5Bweight%5D=30&surface%5Bessentiel%5D%5Bweight%5D=40&surface%5Bessentiel%5D%5Bchoice%5D=on



$path = WP_PLUGIN_DIR . '/mb-matching/class/MatchingQueryBuilder.php';

require_once($path);
$matchingQueryBuilder = new MatchingQueryBuilder();
$matchingQueryBuilder->buildQuery();

global $wpdb;

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
        CAST(mb.price AS UNSIGNED) < CAST(us.price AS UNSIGNED) 
    	AND CAST(mb.surface AS UNSIGNED) >= CAST(us.surface AS UNSIGNED) 
    	AND CAST(mb.room AS UNSIGNED) >= CAST(mb.room AS UNSIGNED) 
            AND (6371 * acos(cos(radians(us.latitude)) * cos(radians(mb.latitude))
            * cos(radians(mb.longitude) - radians(us.longitude))
            + sin(radians(us.latitude)) * sin(radians(mb.latitude)))) <= CAST(us.rayon AS UNSIGNED)
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

// créer une structure de données pour stocker les résultats
$users = [];

// parcourir les résultats et remplir la structure de données
foreach ($results as $result) {
    // vérifier si l'utilisateur existe déjà dans la structure de données
    if (!array_key_exists($result->user_nicename, $users)) {
        // si l'utilisateur n'existe pas, créer un nouvel élément pour lui
        $users[$result->user_nicename] = [
            "name" => $result->user_nicename,
            "email" => $result->user_email,
            "properties" => []
        ];
    }

    // ajouter les données de la propriété à la liste des propriétés de l'utilisateur
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head() ?>
    <title>Document</title>
</head>

<body>

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php the_content() ?>
        <?php endwhile ?>
    <?php endif ?>
</body>

</html>