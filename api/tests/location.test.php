<?php
define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');




// Location at Rizal park in Manila city in Manila(NCR)
$rizal = ['latitude'=>14.5827134, 'longitude'=>120.9777218];

$intramuros_golf_club = ['latitude' => 14.5843948 , 'longitude' => 120.9754953, 'rizal' => 0.30 ];

// Somewhere to km away from Rizal.
$rizal_2km = ['latitude'=>14.5747701, 'longitude'=>120.9915513, 'rizal' => 1.73];

// Philippines Convention Center in Manila. It's 3.10 km away from rizal.
$convention_center = ['latitude'=>14.5550189, 'longitude' =>120.9809992, 'rizal' => 3.10];


// Location at Quezon memorial circle in Manila. It's 11 km away from rizal.
$quezon_memorial_circle = ['latitude' => 14.651424, 'longitude' => 121.0483225, 'rizal' => 10.77];

// Location at SM Clark in Angeles city. It's 77.99 km away from rizal.
$sm_clark = ['latitude' => 15.1689815, 'longitude' => 120.5793488, 'rizal' => 77.99];

// Location at Yongsan station in Korea. it's 2619.15 km away from rizal.
$yongsan_station = ['latitude'=>37.5297347, 'longitude' => 126.9644588, 'rizal' => 2619.15];

$haeundae = ['latitude'=>35.1586788, 'longitude'=>129.1597749, 'youngsan_station' => 328.87];

$user_rizal = 1;
$user_rizal_2km = 2;
$user_quezon_memorial_circle = 3;
$user_sm_clark = 4;
$user_yongsan_station = 5;
$user_haeundae = 6;
$user_intramuros_golba_club = 7;
$user_convention_center = 8;



$wpdb->query("TRUNCATE location");
setUserLocation($user_rizal, $rizal);
setUserLocation($user_intramuros_golba_club, $intramuros_golf_club);
setUserLocation($user_rizal_2km, $rizal_2km);
setUserLocation($user_quezon_memorial_circle, $quezon_memorial_circle);
setUserLocation($user_sm_clark, $sm_clark);
setUserLocation($user_yongsan_station, $yongsan_station);
setUserLocation($user_haeundae, $haeundae);
setUserLocation($user_convention_center, $convention_center);




$re = userSearchByLocation(array_merge($rizal, ['km' => 1]));
isTrue(
    count($re) == 2
    && in_array($user_rizal, ids($re))
    && in_array($user_intramuros_golba_club, ids($re))
    && !in_array($user_rizal_2km, ids($re)),
    'Found myself in 1 km' );



$re = userSearchByLocation(array_merge($rizal, ['km' => 2]));
isTrue(
    count($re) == 3
    && in_array($user_rizal, ids($re))
    && in_array($user_intramuros_golba_club, ids($re))
    && in_array($user_rizal_2km, ids($re))
    && !in_array($user_haeundae, ids($re)),
    '2 km away from rizal park.' );



$re = userSearchByLocation(array_merge($rizal, ['km' => 3]));
isTrue(
    count($re) == 3
    && in_array($user_rizal, ids($re))
    && in_array($user_intramuros_golba_club, ids($re))
    && in_array($user_rizal_2km, ids($re))
    && !in_array($user_convention_center, ids($re))
    && !in_array($user_haeundae, ids($re)),
    '3 km away from rizal park.' );



$re = userSearchByLocation(array_merge($rizal, ['km' => 4]));
isTrue(
    count($re) == 4
    && in_array($user_rizal, ids($re))
    && in_array($user_intramuros_golba_club, ids($re))
    && in_array($user_rizal_2km, ids($re))
    && in_array($user_convention_center, ids($re))
    && !in_array($user_haeundae, ids($re)),
    '4 km away from rizal park.' );


$re = userSearchByLocation(array_merge($rizal, ['km' => 10]));
isTrue(
    count($re) == 4
    && in_array($user_rizal, ids($re))
    && in_array($user_intramuros_golba_club, ids($re))
    && in_array($user_rizal_2km, ids($re))
    && in_array($user_convention_center, ids($re))
    && !in_array($user_quezon_memorial_circle, ids($re))
    && !in_array($user_haeundae, ids($re)),
    '10 km away from rizal park.' );

$re = userSearchByLocation(array_merge($rizal, ['km' => 11]));
//print_r($re);
isTrue(
    count($re) == 5
    && in_array($user_rizal, ids($re))
    && in_array($user_intramuros_golba_club, ids($re))
    && in_array($user_rizal_2km, ids($re))
    && in_array($user_convention_center, ids($re))
    && in_array($user_quezon_memorial_circle, ids($re))
    && !in_array($user_haeundae, ids($re)),
    '11 km away from rizal park.' );


$re = userSearchByLocation(array_merge($rizal, ['km' => 100, 'fields' => 'user_ID,distance']));
isTrue(
    count($re) == 6
    && in_array($user_rizal, ids($re))
    && in_array($user_intramuros_golba_club, ids($re))
    && in_array($user_rizal_2km, ids($re))
    && in_array($user_convention_center, ids($re))
    && in_array($user_quezon_memorial_circle, ids($re))
    && in_array($user_sm_clark, ids($re))
    && !in_array($user_haeundae, ids($re)),
    '100 km away from rizal park.' );



print_r($re);

$re = userSearchByLocation(array_merge($rizal, ['km' => 10000]));
isTrue(
    count($re) == 8,
    '10,000 km away from rizal park.' );



$re = userSearchByLocation(array_merge($yongsan_station, ['km' => 1]));
isTrue(
    count($re) == 1
    && in_array($user_yongsan_station, ids($re))
    && !in_array($user_rizal_2km, ids($re)),
    'Found myself in 1 km for Yongsan station' );


$re = userSearchByLocation(array_merge($yongsan_station, ['km' => 100]));
isTrue(
    count($re) == 1
    && in_array($user_yongsan_station, ids($re))
    && !in_array($user_haeundae, ids($re))
    && !in_array($user_rizal_2km, ids($re)),
    '100 km away from Yongsan station' );



$re = userSearchByLocation(array_merge($yongsan_station, ['km' => 300]));
isTrue(
    count($re) == 1
    && in_array($user_yongsan_station, ids($re))
    && !in_array($user_haeundae, ids($re))
    && !in_array($user_rizal_2km, ids($re)),
    '300 km away from Yongsan station' );


$re = userSearchByLocation(array_merge($yongsan_station, ['km' => 400]));
isTrue(
    count($re) == 2
    && in_array($user_yongsan_station, ids($re))
    && in_array($user_haeundae, ids($re))
    && !in_array($user_rizal_2km, ids($re)),
    '400 km away from Yongsan station' );




displayTestSummary();

function ids($users)
{
    $ret = [];
    foreach ($users as $u) {
        $ret[] = $u['user_ID'];
    }
    return $ret;
}

function setUserLocation($user_ID, $loc) {
    global $wpdb;
    $data = [
        'user_ID' => $user_ID,
        'latitude' => $loc['latitude'],
        'longitude'=> $loc['longitude'],
        'accuracy'=> 0,
        'altitude'=>0,
        'speed'=> 0,
        'heading'=> 0,
        'time'=> 0,
    ];
    $re = $wpdb->replace(LOCATION_TABLE, $data);
    if ( $re ) return $data;
    else return ERROR_WRONG_QUERY;
}

