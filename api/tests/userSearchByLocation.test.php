<?php
define('API_DIR', '.');
require_once(API_DIR . '/../wp-load.php');
require_once(API_DIR . '/api-load.php');

/**
 * Test IDs
 */
$Me = 50;
$A = 1;
$B = 2;
$C = 3;
$D = 4;
$E = 5;
$F = 6;
$G = 7;
$H = 8;
$I = 9;
$J = 10;
$K = 11;
$L = 12;
$M = 13;
$N = 14;
$O = 15;
$P = 16;
$Q = 17;
$R = 18;
$S = 19;
$T = 20;
$U = 21;
$V = 22;
$W = 23;
$X = 24;
$Y = 25;
$Z = 26;
$R1 = 161;
$R3 = 163;
$test_ids = [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z];

/**
 * Test coordinates.
 */
$angeles = ['latitude' => 15.145999, 'longitude' => 120.5826621, 'user_ID' => $A];
$baguio = ['latitude' => 16.3998197, 'longitude' => 120.5617454, 'user_ID' => $B];
$cebu = ['latitude' => 10.3764889, 'longitude' => 123.8364109, 'user_ID' => $C];
$davao = ['latitude' => 7.254475, 'longitude' => 125.3907479, 'user_ID' => $D];
$escalante = ['latitude' => 10.8220231, 'longitude' => 123.4369517, 'user_ID' => $E];
$elsalvador = ['latitude' => 8.5021079, 'longitude' => 124.4632936, 'user_ID' => $F];
$gapan = ['latitude' => 15.3027093, 'longitude' => 120.9821337, 'user_ID' => $G];
$himamaylan = ['latitude' => 10.0710516, 'longitude' => 122.8980179, 'user_ID' => $H];
$iloilo = ['latitude' => 10.7212231, 'longitude' => 122.5473865, 'user_ID' => $I];
$isabela = ['latitude' => 10.2127951, 'longitude' => 123.0197389, 'user_ID' => $J];
$kidapawan = ['latitude' => 7.0656202, 'longitude' => 125.0416334, 'user_ID' => $K];
$lamitan = ['latitude' => 6.6517187, 'longitude' => 122.083316, 'user_ID' => $L];
$mabalacat = ['latitude' => 15.2259252, 'longitude' => 120.5390815, 'user_ID' => $M];
$naga = ['latitude' => 13.6453813, 'longitude' => 123.2466996, 'user_ID' => $N];
$ozamiz = ['latitude' => 8.1494508, 'longitude' => 123.7538432, 'user_ID' => $O];
$pasig = ['latitude' => 14.5747801, 'longitude' => 121.0819937, 'user_ID' => $P];
$quezon = ['latitude' => 14.6703277, 'longitude' => 121.0510759, 'user_ID' => $Q];
$roxas = ['latitude' => 11.5568933, 'longitude' => 122.7352005, 'user_ID' => $R];
$samal = ['latitude' => 7.0806252, 'longitude' => 125.7207676, 'user_ID' => $S];
$tagaytay = ['latitude' => 14.1219865, 'longitude' => 120.957985, 'user_ID' => $T];
$urdaneta = ['latitude' => 15.9682269, 'longitude' => 120.5614881, 'user_ID' => $U];
$valenzuela = ['latitude' => 14.7116307, 'longitude' => 120.9675367, 'user_ID' => $V];
$taguig = ['latitude' => 14.4940021, 'longitude' => 121.0513602, 'user_ID' => $W];
$vigan = ['latitude' => 17.5635915, 'longitude' => 120.3573365, 'user_ID' => $X];
$olongapo = ['latitude' => 14.8723711, 'longitude' => 120.2724407, 'user_ID' => $Y];
$zamboanga = ['latitude' => 7.1612812, 'longitude' => 121.8872102, 'user_ID' => $Z];

$location_rizal = ['latitude'=>14.5827134, 'longitude'=>120.9777218, 'user_ID' => $R1];
$location_3km_from_rizal = ['latitude'=>14.5747701, 'longitude'=>120.9915513, 'user_ID' => $R3];


/**
 * Data Sets
 * 
 * Search poeple by in 1km, 5km, 10km, 100km, 1000km, 3000km, 10,000km on each test set.
 */
$kms = [1, 5, 10, 50, 100, 1000, 3000, 10000];
$testSetA = [
    $angeles,
    $baguio,
    $cebu,
    $davao,
    $escalante,
    $elsalvador,
    $gapan,
    $himamaylan,
    $iloilo,
    $isabela,
    $kidapawan,
    $lamitan,
    $mabalacat,
    $naga,
    $ozamiz,
    $pasig,
    $quezon,
    $roxas,
    $samal,
    $tagaytay,
    $urdaneta,
    $valenzuela,
    $taguig,
    $vigan,
    $olongapo,
    $zamboanga,
    $location_rizal,
    $location_3km_from_rizal
];
$testSetB = [
    $A =>  ['latitude' => 14.6043736, 'longitude' => 120.9826443, 'user_ID' => $A],
    $B =>  ['latitude' => 14.554899, 'longitude' => 121.0233224, 'user_ID' => $B],
    $C =>  ['latitude' => 14.5579935, 'longitude' => 121.0272277, 'user_ID' => $C],
    $D =>  ['latitude' => 14.5598004, 'longitude' => 121.0209514, 'user_ID' => $D],
    $E =>  ['latitude' => 14.5458616, 'longitude' => 121.0291481, 'user_ID' => $E],
    $F =>  ['latitude' => 14.535411, 'longitude' => 121.0181932, 'user_ID' => $F],
    $G =>  ['latitude' => 15.1419761, 'longitude' => 120.5766522, 'user_ID' => $G],
    $H =>  ['latitude' => 15.1379566, 'longitude' => 120.5753455, 'user_ID' => $H],
    $I =>  ['latitude' => 15.1360551, 'longitude' => 120.5814721, 'user_ID' => $I],
    $J =>  ['latitude' => 15.1481736, 'longitude' => 120.5933225, 'user_ID' => $J],
    $K =>  ['latitude' => 16.3978538, 'longitude' => 120.6019829, 'user_ID' => $K],
    $L =>  ['latitude' => 16.3995832, 'longitude' => 120.5989428, 'user_ID' => $L],
    $M =>  ['latitude' => 16.3916894, 'longitude' => 120.5917302, 'user_ID' => $M],
    $N =>  ['latitude' => 16.3918511, 'longitude' => 120.5786893, 'user_ID' => $N],
    $O =>  ['latitude' => 16.4219714, 'longitude' => 120.5921212, 'user_ID' => $O],
    $P =>  ['latitude' => 16.4232379, 'longitude' => 120.5999804, 'user_ID' => $P],
    $Q =>  ['latitude' => 14.6750282, 'longitude' => 121.0426155, 'user_ID' => $Q],
    $R =>  ['latitude' => 14.6870691, 'longitude' => 121.0579371, 'user_ID' => $R],
    $S =>  ['latitude' => 14.6896712, 'longitude' => 121.0650329, 'user_ID' => $S],
    $T =>  ['latitude' => 14.6751121, 'longitude' => 121.0791182, 'user_ID' => $T],
    $U =>  ['latitude' => 14.5500694, 'longitude' => 121.0520212, 'user_ID' => $U],
    $V =>  ['latitude' => 14.5474836, 'longitude' => 121.0479657, 'user_ID' => $V],
    $W =>  ['latitude' => 14.5413025, 'longitude' => 121.0500333, 'user_ID' => $W],
    $X =>  ['latitude' => 14.5364343, 'longitude' => 121.054698, 'user_ID' => $X],
    $Y =>  ['latitude' => 14.5312641, 'longitude' => 121.0598595, 'user_ID' => $Y],
    $Z =>  ['latitude' => 14.5301794, 'longitude' => 121.0687299, 'user_ID' => $Z],
];

/**
 * Set A - 26 Users are scattered around philippines
 */
print("\n =========================== >> TEST SET A \r\n");

/** 
 * Test 1. Im in Manila, and I search for users near me.
 */
$test_location = ['latitude' => 14.5826096, 'longitude' => 120.9772712];
print("\n\n============ >> SET A: TEST 1 \r\n");
$expectations = [
    1 => [$R1, $R3],
    5 => [$R1, $R3],
    10 => [$R1, $R3],
    50 => [$R1, $P, $Q, $V, $W],
    100 => [$R1, $A, $G, $M, $P, $Q, $T, $V, $W, $Y],
    1000 => [$R1, $A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    3000 => [$R1, $A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    10000 => [$R1, $A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
];
testLocation(
    $test_location,
    $testSetA,
    $kms,
    $expectations
);

/**
 * Test 2, I travelled for vacation to Iloilo in visayas then searched user right after I arrived there.
 */
print("\n\n============ >> SET A: TEST 2 \r\n");
$test_location = ['latitude' => 10.7127939, 'longitude' => 122.5566655];
$expectations = [
    1 => [],
    5 => [$I],
    10 => [$I],
    50 => [$I],
    100 => [$E, $H, $I, $J, $R],
    1000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    3000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    10000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
];
testLocation(
    $test_location,
    $testSetA,
    $kms,
    $expectations
);

/*
 * Test 3, I go to cotabato city in mindanao and search users again.
 */
print("\n\n============ >> SET A: TEST 3 \r\n");
$test_location = ['latitude' => 7.1988411, 'longitude' => 124.1742481];
$expectations = [
    1 => [],
    5 => [],
    10 => [],
    50 => [],
    100 => [$K],
    1000 => [$A, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $V, $W, $Y, $Z],
    3000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    10000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
];
testLocation(
    $test_location,
    $testSetA,
    $kms,
    $expectations
);

/**
 * Test 4, I travelled abroad to busan and searched users.
 */
print("\n\n============ >> SET A: TEST 4 \r\n");
$test_location = ['latitude' => 35.1645701, 'longitude' => 129.0015885];
$expectations = [
    1 => [],
    5 => [],
    10 => [],
    50 => [],
    100 => [],
    1000 => [],
    3000 =>  [$A, $B, $C, $E, $G, $H, $I, $J, $M, $N, $P, $Q, $R, $T, $U, $V, $W, $X, $Y],
    10000 =>  [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
];
testLocation(
    $test_location,
    $testSetA,
    $kms,
    $expectations
);

/**
 * Set B. Narrow Location Test where people are mostly in near location.
 * 
 *  User A is in same city of user Me.
 *  User B~F is also in Makati.
 *  User G~J is in Angeles City
 *  User K~P is in Baguio
 *  User Q~T is in Quezon City.
 *  User U~Z is in Bonifacio.
 */
print("\n=========================== >> TEST SET B \r\n");

/**
 * Test 1. Initial expectation check.
 */
print("\n\n============ >> SET B: TEST 1 \r\n");
$test_location = ['latitude' => 14.6043736, 'longitude' => 120.9836443];
$expectations = [
    1 => [$A],
    5 => [$A],
    10 => [$A, $B, $C, $D, $E, $F, $U, $V],
    50 => [$A, $B, $C, $D, $E, $F, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    100 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    1000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    3000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    10000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z]
];
testLocation(
    $test_location,
    $testSetB,
    $kms,
    $expectations
);

/**
 * test 2
 *  User A moves to angeles. A should not appear near me at 1 - 10 KM radius
 */
print("\n\n============ >> SET B: TEST 2 \r\n");
$testSetB[$A] = ['latitude' => 15.1360551, 'longitude' => 120.5814721, 'user_ID' => $A];
$expectations = [
    1 => [],
    5 => [],
    10 => [$B, $C, $D, $E, $F, $U, $V],
    50 => [$B, $C, $D, $E, $F, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    100 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    1000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    3000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    10000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z]
];
testLocation(
    $test_location,
    $testSetB,
    $kms,
    $expectations
);

/**
 * Test 3
 *  User B, C, D travelled together to hongkong. Then I checked again fot users near me
 */
print("\n============ >> SET B: TEST 3 \r\n");
$testSetB[$B] = ['latitude' => 22.3164128, 'longitude' => 114.1684988, 'user_ID' => $B];
$testSetB[$C] = ['latitude' => 22.3164128, 'longitude' => 114.1684988, 'user_ID' => $C];
$testSetB[$D] = ['latitude' => 22.3164128, 'longitude' => 114.1684988, 'user_ID' => $D];
$expectations = [
    1 => [],
    5 => [],
    10 => [$E, $F, $U, $V],
    50 => [$E, $F, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    100 => [$A, $E, $F, $G, $H, $I, $J, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    1000 => [$A, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    3000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    10000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z]
];
testLocation(
    $test_location,
    $testSetB,
    $kms,
    $expectations
);

/**
 * Test Set C: 
 *  I am moving from and to another locations periodically checking users near me.
 * 
 *  NOTE: Test Set A locations is used for other users locations.
 */
print("\n\n============ >> SET C: Moving test \r\n");
$test_locations = [
    ['latitude' => 15.145999, 'longitude' => 120.5826621], // angeles
    ['latitude' => 10.3764889, 'longitude' => 123.8364109], // cebu
    ['latitude' => 10.7212231, 'longitude' => 122.5473865], // iloilo
    ['latitude' => 8.1494508, 'longitude' => 123.7538432], // ozamiz
    ['latitude' => 7.1612812, 'longitude' => 121.8872102], // zamboanga
];
$expectations = [
    [
        1 => [$A],
        5 => [$A],
        10 => [$A],
        50 => [$A, $G, $M, $Y],
        100 => [$A, $G, $M, $P, $Q, $U, $V, $W, $Y],
        1000 => [$A, $B, $C, $E, $F, $G, $H, $I, $J, $L, $M, $N, $O, $P, $Q, $R, $T, $U, $V, $W, $X, $Y, $Z],
        3000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
        10000 => [$A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    ],
    [
        1 => [$C],
        5 => [$C],
        10 => [$C],
        50 => [$C],
        100 => [$C, $E, $J],
        1000 => [$C, $A, $B, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
        3000 => [$C, $A, $B, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
        10000 => [$C, $A, $B, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    ],
    [
        1 => [$I],
        5 => [$I],
        10 => [$I],
        50 => [$I],
        100 => [$I, $E, $H, $J, $R],
        1000 => [$I, $A, $B, $C, $D, $E, $F, $G, $H, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
        3000 => [$I, $A, $B, $C, $D, $E, $F, $G, $H, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
        10000 => [$I, $A, $B, $C, $D, $E, $F, $G, $H, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    ],
    [
        1 => [$O],
        5 => [$O],
        10 => [$O],
        50 => [$O],
        100 => [$O, $F],
        1000 => [$O, $A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $P, $Q, $R, $S, $T, $U, $V, $W, $Y, $Z],
        3000 => [$O, $A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
        10000 => [$O, $A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y, $Z],
    ],
    [
        1 => [$Z],
        5 => [$Z],
        10 => [$Z],
        50 => [$Z],
        100 => [$Z, $L],
        1000 => [$Z, $A, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $Y,],
        3000 => [$Z, $A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y],
        10000 => [$Z, $A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, $M, $N, $O, $P, $Q, $R, $S, $T, $U, $V, $W, $X, $Y],
    ],
];
$index = 0;
foreach ($test_locations as $location) {
    $point = $index + 1;
    print("\n\n============ >> SET C: POINT $point \r\n");
    testLocation(
        $location,
        $testSetA,
        $kms,
        $expectations[$index],
    );
    $index++;
}

///////////////////// METHODS

/**
 * Run tests set.
 */
function testLocation($myLocation, $testSet, $kms, $expectations)
{
    global $wpdb;
    $wpdb->query("TRUNCATE " . LOCATION_TABLE);

    // Save test location set
    saveLocations($testSet);

    // Search by KM
    foreach ($kms as $km) {
        print("\n[ Search radius <= $km KM ]\r\n");
        $myLocation['km'] = $km;
        $myLocation['limit'] = 30;
        $response = userSearchByLocation($myLocation);
        shouldBeInSearch($expectations[$km], $response);
    }
}

/**
 * Save test users locations to backend.
 */
function saveLocations($users)
{
    foreach ($users as $u) {
        updateUserLocation($u);
    }
}


/**
 * Check if expectations for users are inside of the search radius is correct.
 */
function shouldBeInSearch($ids, $response)
{

    print("\n====== Inside search radius\r\n");
    if (empty($ids)) {
        isTrue(empty($response), 'EXPECT NO USER TO BE INSIDE THE SEARCH RADIUS.');
    } else {
        foreach ($ids as $id) {
            isTrue(in_array($id, ids($response)), "user #$id is inside the search radius. Distance from location: " . getDistance($id, $response));
        }
    }

    shouldNotBeInSearch($ids, $response);
}

/**
 * Check if expectations for users are outside of the search radius. 
 */
function shouldNotBeInSearch($inside, $response)
{
    print("\n====== Outside search radius\r\n");
    global $test_ids;
    /// expected outside the search radius.
    $outside = array_diff($test_ids, $inside);

    // if (empty($outside))
    //     isTrue(empty($outside), 'EXPECT NO USER TO BE OUTSIDE THE SEARCH RADIUS.');
    // else
        foreach ($outside as $out) {
            isTrue(!in_array($out, ids($response)), "user #$out is outside the search radius");
        }
}

function getDistance($id, $response)
{
    foreach ($response as $res) {
        if ($res['user_ID'] == $id) return round($res['distance'], 2);
    }
}


function ids($users)
{
    $ret = [];
    foreach ($users as $u) {
        $ret[] = $u['user_ID'];
    }
    return $ret;
}

displayTestSummary();
