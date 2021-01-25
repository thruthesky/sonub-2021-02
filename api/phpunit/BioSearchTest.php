<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;


require_once("../../../wp-load.php");
require_once(API_DIR . '/ext/bio.route.php');



define('BIO_TEST_SET', [
    'rizal' => [
        'user_ID' => 1,
        'gender' => 'M',
        'height' => 174,
        'weight' => 74,
        'city' => 'manila',
        'latitude' => 14.5843948,
        'longitude' => 120.9754953,
        'from' => 'rizal', 'away' => 0
    ],
    'intramuros' => [
        'user_ID' => 2,
        'gender' => 'M',
        'height' => 163,
        'weight' => 63,
        'city' => 'manila',
        'latitude' => 14.5843948,
        'longitude' => 120.9754953,
        'from' => 'rizal', 'away' => 0.30
    ],
    'rizal2km' => [
        'user_ID' => 3,
        'gender' => 'M',
        'height' => 189,
        'weight' => 89,
        'city' => 'manila',
        'latitude' => 14.5747701,
        'longitude' => 120.9915513,
        'from' => 'rizal', 'away' => 1.730
    ],
    'ccp' => [
        'user_ID' => 4,
        'gender' => 'M',
        'height' => 178,
        'weight' => 78,
        'city' => 'manila',
        'latitude' => 14.5550189,
        'longitude' => 120.9809992,
        'from' => 'rizal', 'away' => 3.1
    ],
    'quezon_circle' => [
        'user_ID' => 5,
        'gender' => 'F',
        'height' => 168,
        'weight' => 68,
        'city' => 'quezon',
        'latitude' => 14.651424,
        'longitude' => 121.0483225,
        'from' => 'rizal', 'away' => 10.77
    ],
    'sm_clark' => [
        'user_ID' => 6,
        'gender' => 'F',
        'height' => 166,
        'weight' => 66,
        'city' => 'angeles',
        'latitude' => 15.1689815,
        'longitude' => 120.5793488,
        'from' => 'rizal', 'away' => 77.99
    ],
    'yongsan' => [
        'user_ID' => 7,
        'gender' => 'F',
        'height' => 159,
        'weight' => 59,
        'city' => 'seoul',
        'latitude' => 37.5297347,
        'longitude' => 126.9644588,
        'from' => 'rizal', 'away' => 2619.15
    ],
    'haeundae' => [
        'user_ID' => 8,
        'gender' => 'F',
        'height' => 145,
        'weight' => 45,
        'city' => 'seoul',
        'latitude' => 35.1586788,
        'longitude' => 129.1597749,
        'from' => 'yongsan', 'away' => 328.87
    ],
]);



final class BioSearchTest extends TestCase {
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);


        $this->setTestData();
    }

    private function setTestData() {
        global $wpdb;
        foreach( BIO_TEST_SET as $name => $data ) {
            unset($data['from'], $data['away']);
            $data['name'] = $name;
            $re = $wpdb->replace(BIO_TABLE, $data);
//            if ( $re ) return $data;
//            else return ERROR_WRONG_QUERY;
        }
    }
    public function testGetOneRecord(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'limit' => 1,
        ]);
        self::assertTrue(count($re) === 1, 'testGetOneRecord');
    }

    public function testGetWithIn1KmFromRizal(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 1,
            'limit' => 1000,
        ]);
//        print_r($re);
//        print_r(ids($re));
//        print_r(BIO_TEST_SET['rizal']['user_ID']);
//        echo "\nSame?: " . in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)) . "\n";
        self::assertTrue(count($re) === 2, 'testGetWithIn1KmFromRizal. expect 2.');
        self::assertTrue( in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal himself is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros is inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2Km is  not inside' );
    }
    public function testGetWithIn2KmFromRizal(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 2,
            'limit' => 1000,
        ]);
        self::assertTrue( in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal himself is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros is inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km is  inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan is  not inside' );
    }
    public function testGetWithIn3KmFromRizal(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 3,
            'limit' => 1000,
        ]);
        self::assertTrue( in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal himself is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km is  inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp is  inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan is  not inside' );
    }
    public function testGetWithIn10KmFromRizal(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 10,
            'limit' => 1000,
        ]);
        self::assertTrue( in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal himself is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp is  inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan is  not inside' );
    }
    public function testGetWithIn50KmFromRizal(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 50,
            'limit' => 1000,
        ]);
        self::assertTrue( in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal himself is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['quezon_circle']['user_ID'], ids($re)), 'quezon_circle is  inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['sm_clark']['user_ID'], ids($re)), 'sm_clark is not inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan is  not inside' );
    }
    public function testGetWithIn100KmFromRizal(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 100,
            'limit' => 1000,
        ]);
        self::assertTrue( in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal himself is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['quezon_circle']['user_ID'], ids($re)), 'quezon_circle is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['sm_clark']['user_ID'], ids($re)), 'sm_clark is  inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan is  not inside' );
    }
    public function testGetWithIn1000KmFromRizal(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 1000,
            'limit' => 1000,
        ]);
        self::assertTrue( in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal himself is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['quezon_circle']['user_ID'], ids($re)), 'quezon_circle is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['sm_clark']['user_ID'], ids($re)), 'sm_clark is  inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan is  not inside' );
        self::assertTrue( !in_array( BIO_TEST_SET['haeundae']['user_ID'], ids($re)), 'haesundae is not inside' );
    }
    public function testGetWithIn10000KmFromRizal(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 10000,
            'limit' => 1000,
        ]);
        self::assertTrue( in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal himself is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros is inside' );
        self::assertTrue( in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['quezon_circle']['user_ID'], ids($re)), 'quezon_circle is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['sm_clark']['user_ID'], ids($re)), 'sm_clark is  inside' );
        self::assertTrue( in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan is  not inside' );
        self::assertTrue( in_array( BIO_TEST_SET['haeundae']['user_ID'], ids($re)), 'haesundae is not inside' );
    }
    public function testGetWithIn100KmFromYongsan(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['yongsan']['latitude'],
            'longitude' => BIO_TEST_SET['yongsan']['longitude'],
            'km' => 100,
            'limit' => 1000,
        ]);
//        print_r($re);
        self::assertTrue( !in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal n' );
        self::assertTrue( !in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros n' );
        self::assertTrue( !in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km n' );
        self::assertTrue( !in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp n' );
        self::assertTrue( !in_array( BIO_TEST_SET['quezon_circle']['user_ID'], ids($re)), 'quezon_circle n' );
        self::assertTrue( !in_array( BIO_TEST_SET['sm_clark']['user_ID'], ids($re)), 'sm_clark n' );
        self::assertTrue( in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan y' );
        self::assertTrue( !in_array( BIO_TEST_SET['haeundae']['user_ID'], ids($re)), 'haesundae n' );
    }
    public function testGetWithIn1000KmFromYongsan(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['yongsan']['latitude'],
            'longitude' => BIO_TEST_SET['yongsan']['longitude'],
            'km' => 1000,
            'limit' => 1000,
        ]);
//        print_r($re);
        self::assertTrue( !in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal n' );
        self::assertTrue( !in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros n' );
        self::assertTrue( !in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km n' );
        self::assertTrue( !in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp n' );
        self::assertTrue( !in_array( BIO_TEST_SET['quezon_circle']['user_ID'], ids($re)), 'quezon_circle n' );
        self::assertTrue( !in_array( BIO_TEST_SET['sm_clark']['user_ID'], ids($re)), 'sm_clark n' );
        self::assertTrue( in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan y' );
        self::assertTrue( in_array( BIO_TEST_SET['haeundae']['user_ID'], ids($re)), 'haesundae n' );
    }

    public function testName(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'name' => 'ccp',
        ]);
        self::assertTrue(count($re) === 1);
        self::assertTrue($re[0]['name'] === 'ccp');
    }
    public function testGender(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'gender' => 'M',
        ]);
        self::assertTrue(count($re) > 3);
        self::assertTrue(in_array(BIO_TEST_SET['rizal']['user_ID'], ids($re)));
        self::assertTrue(in_array(BIO_TEST_SET['intramuros']['user_ID'], ids($re)));
        self::assertTrue(!in_array(BIO_TEST_SET['yongsan']['user_ID'], ids($re)));
    }
    public function testGenderHeight(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'gender' => 'M',
            'heightFrom' => BIO_TEST_SET['rizal']['height'],
            'heightTo' => BIO_TEST_SET['rizal']['height'],
        ]);

        self::assertTrue(count($re) >= 1);
        self::assertTrue(in_array(BIO_TEST_SET['rizal']['user_ID'], ids($re)));
        self::assertTrue(!in_array(BIO_TEST_SET['intramuros']['user_ID'], ids($re)));
    }
    public function testGenderHeightRange(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'gender' => 'M',
            'heightFrom' => BIO_TEST_SET['rizal']['height']-1,
            'heightTo' => BIO_TEST_SET['rizal']['height']+1,
        ]);

        self::assertTrue(count($re) >= 1);
        self::assertTrue(in_array(BIO_TEST_SET['rizal']['user_ID'], ids($re)));
        self::assertTrue(!in_array(BIO_TEST_SET['intramuros']['user_ID'], ids($re)));
    }
    public function testGenderHeightToOnly(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'gender' => 'F',
            'heightTo' => 145,
        ]);
        self::assertTrue(count($re) >= 1);
        self::assertTrue(!in_array(BIO_TEST_SET['rizal']['user_ID'], ids($re)));
        self::assertTrue(in_array(BIO_TEST_SET['haeundae']['user_ID'], ids($re)));


        $bio = new BioRoute();
        $re = $bio->search([
            'gender' => 'M',
            'heightTo' => 145,
        ]);
        self::assertTrue(!in_array(BIO_TEST_SET['haeundae']['user_ID'], ids($re)));
    }



    public function testGenderWithin100kmFromRizal(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 100,
            'gender' => 'M',
            'limit' => 1000,
        ]);
        self::assertTrue( in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal y' );
        self::assertTrue( in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros y' );
        self::assertTrue( in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km y' );
        self::assertTrue( in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp y' );
        self::assertTrue( !in_array( BIO_TEST_SET['quezon_circle']['user_ID'], ids($re)), 'quezon_circle n' );
        self::assertTrue( !in_array( BIO_TEST_SET['sm_clark']['user_ID'], ids($re)), 'sm_clark n' );
        self::assertTrue( !in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan y' );
        self::assertTrue( !in_array( BIO_TEST_SET['haeundae']['user_ID'], ids($re)), 'haesundae n' );
    }


    public function testCombinationSearch(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 100,
            'heightFrom' => 160,
            'weightTo' => 70,
            'gender' => 'M',
            'limit' => 1000,
        ]);
        self::assertTrue( !in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal y' );
        self::assertTrue( in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros y' );
        self::assertTrue( !in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km y' );
        self::assertTrue( !in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp y' );
        self::assertTrue( !in_array( BIO_TEST_SET['quezon_circle']['user_ID'], ids($re)), 'quezon_circle n' );
        self::assertTrue( !in_array( BIO_TEST_SET['sm_clark']['user_ID'], ids($re)), 'sm_clark n' );
        self::assertTrue( !in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan y' );
        self::assertTrue( !in_array( BIO_TEST_SET['haeundae']['user_ID'], ids($re)), 'haesundae n' );
    }
    public function testProfilePhoto(): void {
        $bio = new BioRoute();
        $re = $bio->search([
            'latitude' => BIO_TEST_SET['rizal']['latitude'],
            'longitude' => BIO_TEST_SET['rizal']['longitude'],
            'km' => 100,
            'heightFrom' => 160,
            'weightTo' => 70,
            'gender' => 'M',
            'hasProfilePhoto' => 'Y',
            'limit' => 1000,
        ]);
        self::assertTrue( !in_array( BIO_TEST_SET['rizal']['user_ID'], ids($re)), 'rizal y' );
        self::assertTrue( !in_array( BIO_TEST_SET['intramuros']['user_ID'], ids($re)), 'intramuros y' );
        self::assertTrue( !in_array( BIO_TEST_SET['rizal2km']['user_ID'], ids($re)), 'rizal2km y' );
        self::assertTrue( !in_array( BIO_TEST_SET['ccp']['user_ID'], ids($re)), 'ccp y' );
        self::assertTrue( !in_array( BIO_TEST_SET['quezon_circle']['user_ID'], ids($re)), 'quezon_circle n' );
        self::assertTrue( !in_array( BIO_TEST_SET['sm_clark']['user_ID'], ids($re)), 'sm_clark n' );
        self::assertTrue( !in_array( BIO_TEST_SET['yongsan']['user_ID'], ids($re)), 'yongsan y' );
        self::assertTrue( !in_array( BIO_TEST_SET['haeundae']['user_ID'], ids($re)), 'haesundae n' );
    }
}