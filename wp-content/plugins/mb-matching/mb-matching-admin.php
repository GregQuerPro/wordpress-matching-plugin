<?php
// Récupérer les champs MetaBox depuis la base de données
// On veut voir s'afficher latitude, longitude, price, surface, room
// ?_geolocalisation=48.539927%2C2.660817%2C100%2CMelun%2C+Seine-et-Marne%2C+France&_price=2000%2C1407000&_surface=235%2C550&_room=2&


/** @var wpdb $wpdb */
global $wpdb;

if (!empty($_POST)) {

    // var_dump($_POST);
    // var_dump(!$_POST['to-hide'] === '0');
    // var_dump(!$_POST['to-hide'] === '1');

    if (!isset($_POST['to-hide'])) {

        $results = $wpdb->get_results("SELECT * FROM user_searches_options");

        // var_dump($results);

        $names = [];

        foreach ($results as $result) {
            $names[] = $result->name;
        }

        // var_dump($_POST);
        // var_dump($names);
        // var_dump($_POST);

        foreach ($_POST as $key => $value) {

            if (in_array($key, $names)) {
                // echo 'existe déjà';
                $wpdb->update(
                    'user_searches_options',
                    [
                        'weight_base' => $value['weight'][0],
                        'weight_essential' => $value['weight'][1],
                        'sign' => $value['sign']
                    ],
                    [
                        'id' => $value['id'],
                    ]
                );
            } else {
                // echo 'existe pas';
                $wpdb->insert(
                    'user_searches_options',
                    [
                        'name' => $key,
                        'weight_base' => $value['weight'][0],
                        'weight_essential' => $value['weight'][1],
                        'sign' => $value['sign']
                    ],
                    [
                        '%s',
                        '%d',
                        '%s'
                    ]
                );
            }
        }

        foreach ($names as $name) {
            if (!array_key_exists($name, $_POST)) {
                $wpdb->delete('user_searches_options', ['name' => $name, 'to_hide' => 0]);
            }
        }
        // Vérifier si il n'y a pas des items en trop dans la BDD
    } else {
        // var_dump('test');
        global $wpdb;

        // var_dump('test');

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM user_searches_options WHERE name = %s",
            $_POST['field']
        ));

        // var_dump($count);

        if ($count > 0) {
            // L'élément existe déjà, afficher un message d'erreur ou effectuer une autre action
            $wpdb->update(
                'user_searches_options',
                [
                    'to_hide' => $_POST['to-hide']
                ],
                [
                    'name' => $_POST['field'],
                ]
            );
            // echo "existe déjà";
        } else {
            // Insérer un nouvel élément
            $wpdb->insert(
                'user_searches_options',
                [
                    'name' => $_POST['field'],
                    'weight_base' => null,
                    'weight_essential' => null,
                    'sign' => null,
                    'to_hide' => 1
                ],
            );
            // echo 'Nouvel élément inséré';
        }
    }
    // var_dump($_POST);
}


$matchingFields = $wpdb->get_results("SELECT * FROM user_searches_options");
$metaBoxFields = $wpdb->get_results("DESCRIBE wp_mb_properties");

// var_dump($matchingFields);
// var_dump($metaBoxFields);

foreach ($matchingFields as $matchingField) {
    foreach ($metaBoxFields as $metaBoxField) {
        // var_dump(strtolower($matchingField->name));
        // var_dump(lcfirst($metaBoxField->Field));
        // var_dump(lcfirst($matchingField->to_hide === '1'));
        if (strtolower($matchingField->name) === $metaBoxField->Field && $matchingField->to_hide === '1') {
            $metaBoxField->archived = true;
        } else {
            if (strtolower($matchingField->name) === $metaBoxField->Field && $matchingField->weight_base !== null && $matchingField->weight_essential !== null && $matchingField->sign !== null) {
                $metaBoxField->isActive = true;
            }
        }
    }
}

// var_dump($metaBoxFields);
// var_dump($matchingFields);
?>

<h1>MB Matching</h1>

<div class="mb-matching__wrapper">
    <div class="mb-matching__grid">
        <div class="mb-matching__grid-left">
            <h2 class="mb-matching__title">Champs Matching</h2>
            <form action="" method="POST" class="mb-matching__fields-ctn">
                <?php if (!empty($matchingFields)) : ?>
                    <?php foreach ($matchingFields as $matchingField) : ?>
                        <?php if ($matchingField->to_hide === '0' && $matchingField->weight_base !== null && $matchingField->weight_essential !== null && $matchingField->sign !== null) : ?>
                            <div class="mb-matching__fields-item" data-name="<?= ucfirst($matchingField->name) ?>">
                                <div>
                                    <label for="<?= $matchingField->name ?>"><?= $matchingField->name ?></label>
                                    <input type="hidden" name="<?= $matchingField->name ?>" id="<?= $matchingField->name ?>" value="<?= $matchingField->name ?>">
                                </div>
                                <div class="mb-matching__input-ctn">
                                    <input type="hidden" name="<?= $matchingField->name ?>[id]" value="<?= $matchingField->id ?>">
                                    <input type="number" name="<?= $matchingField->name ?>[weight][]<?= $matchingField->name ?>_weight_base" id="<?= $matchingField->name ?>_weight_base" placeholder="Poids en % de base" class="mb-matching__input" value="<?= $matchingField->weight_base ?>"><br>
                                    <input type="number" name="<?= $matchingField->name ?>[weight][]<?= $matchingField->name ?>_weight_essential" id="<?= $matchingField->name ?>_weight_essential" placeholder="Poids en % essentiel" class="mb-matching__input" value="<?= $matchingField->weight_essential ?>">
                                    <input type="text" name="<?= $matchingField->name ?>[sign]<?= $matchingField->name ?>_sign" id="<?= $matchingField->name ?>_sign" placeholder="Valeur Retenue" class="mb-matching__input" value="<?= $matchingField->sign ?>">
                                </div>
                                <div class="mb-matching__delete-btn">X</div>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                <?php endif ?>
            </form>
            <input type="text" placeholder="Nom du nouveau champ" class="add-input"><button class="add-btn">Ajouter</button>
            <button type="submit" class="mb-matching__btn">Sauvegarder</button>
        </div>
        <div class="mb-matching__grid-right">
            <h2 class="mb-matching__title">Champs Metabox</h2>
            <ul class="mb-matching__fields-ctn">
                <?php foreach ($metaBoxFields as $metaBoxField) : ?>
                    <?php if ($metaBoxField->Field !== 'ID') : ?>
                        <?php if ($metaBoxField->archived !== true) : ?>
                            <li class="mb-matching__fields-item <?= $metaBoxField->isActive ? 'active' : '' ?>" data-name="<?= ucfirst($metaBoxField->Field) ?>">
                                <span><?= ucfirst($metaBoxField->Field) ?></span>
                                <form method="POST">
                                    <input type="hidden" name="to-hide" id="to-hide" value="1">
                                    <input type="hidden" name="field" id="field" value="<?= ucfirst($metaBoxField->Field) ?>">
                                    <button type="submit">Archiver</button>
                                </form>
                            </li>
                        <?php endif ?>
                    <?php endif ?>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
    <div class="archived">
        <h2 class="mb-matching__title">Champs Metabox Archivés</h2>
        <ul class="mb-matching__fields-ctn">
            <?php foreach ($metaBoxFields as $metaBoxField) : ?>
                <?php if ($metaBoxField->Field !== 'ID') : ?>
                    <?php if ($metaBoxField->archived === true) : ?>
                        <li class="mb-matching__fields-item <?= $metaBoxField->isActive ? 'active' : '' ?>" data-name="<?= ucfirst($metaBoxField->Field) ?>">
                            <span><?= ucfirst($metaBoxField->Field) ?></span>
                            <form method="POST">
                                <input type="hidden" name="to-hide" id="to-hide" value="0">
                                <input type="hidden" name="field" id="field" value="<?= ucfirst($metaBoxField->Field) ?>">
                                <button type="submit">Restaurer</button>
                            </form>
                        </li>
                    <?php endif ?>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
    </div>
</div>