<?php

// function getMatchingProperties()
// {
//     global $wpdb;

//     /* Les données de la recherche utilisateur qui sera stocké dans la BDD */

//     // Point de références (Mairie Melun)
//     // define("LATITUDE", 48.539927);
//     // define("LONGITUDE", 2.660817);
//     // define("BASE_DISTANCE", 25);
//     // define("DISTANCE", BASE_DISTANCE + (BASE_DISTANCE * (20 / 100)));


//     // Valeur sans poids
//     // define("BASE_PRICE", 100000);
//     // define("BASE_SURFACE", 500);
//     // define("BASE_DISTANCE", 25);

//     // // Valeur avec poids
//     // define("PRICE", BASE_PRICE + (BASE_PRICE * (10 / 100)));
//     // define("SURFACE", BASE_SURFACE - (BASE_SURFACE * (10 / 100)));
//     // define("DISTANCE", BASE_DISTANCE + (BASE_DISTANCE * (20 / 100)));

//     // var_dump(PRICE, SURFACE, DISTANCE);

//     // Calculate weight for price
//     // $priceWeight = (BASE_PRICE < 100000) ? 20 : 10;
//     // // var_dump($priceWeight);

//     // // Calculate weight for surface
//     // $surfaceWeight = ($surface < 500) ? 20 : 10;

//     // // Calculate delta for price
//     // $priceDelta = 100000 - $price;

//     // // Calculate delta for surface
//     // $surfaceDelta = 500 - $surface;

//     // Calculate distance in km
//     // $distance = 6371 * acos(cos(radians($latitude)) * cos(radians(37.7749)) * cos(radians(-122.4194) - radians($longitude)) + sin(radians($latitude)) * sin(radians(37.7749)));

//     // Build and execute SQL query
//     global $wpdb;

//     $latitude = 48.539927;
//     $longitude = 2.660817;
//     $base_distance = 25;
//     $distance = $base_distance + ($base_distance * (0.2));

//     $results = $wpdb->get_results($wpdb->prepare(
//         "SELECT id, name, latitude, longitude,
//         (6371 * acos(cos(radians(%f)) * cos(radians(latitude))
//               * cos(radians(longitude) - radians(%f))
//               + sin(radians(%f)) * sin(radians(latitude)))) AS distance
//     FROM {$wpdb->prefix}properties
//     HAVING distance < %f
//     ORDER BY distance",
//         $latitude,
//         $longitude,
//         $latitude,
//         $distance
//     ));

//     // var_dump($results);

//     // return $matchingProperties;
// }

// getMatchingProperties();
