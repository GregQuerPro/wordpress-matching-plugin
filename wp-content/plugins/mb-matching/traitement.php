<?php

if (!empty($_POST)) {
    global $wpdb;

    // $result = $wpdb->get_row('SELECT * WHERE name = Latitude');

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
    // $wpdb->update('user_searches_options', ['column' => 'to_hide', 'field' => 1]);
}
