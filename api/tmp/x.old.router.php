<?php

class Router {


    public function version() {
        return ['version' => '0.0.1'];
    }

    /**
     * @param $data
     * @return array|int|mixed|string|null
     *
     * @see tests/loginOrRegister.test.php
     * @see tests/loginOrRegister.function_call.test.php
     */
    public function loginOrRegister($data) {
        $re = login($data);
        if ( $re === 0 ) return userProfile();
        if ( $re !== ERROR_USER_NOT_FOUND_BY_THAT_EMAIL ) {
            return $re;
        }
        $re = register($data);
        if ( $re === 0 ) return userProfile();
        else return $re;
    }

    /**
     * @param $data
     * @see https://docs.google.com/document/d/1plrZXoUNS5cb4XXzHy_LZap6-XfY1oZ_0D9IfOqUbI4/edit#heading=h.3azcvssxegm1
     */
    public function testLoginOrRegister() {

        $data = in();
        v3log($data);
        $re = [
            'user_pass' => PASS_LOGIN_PASSWORD,
            'autoLoginYn' => 'N',
            'autoStatusCheck' => 'N',
            'plid' => $data['plid'],
            'agegroup' => $data['agegroup'] ?? '30',
            'gender' => $data['gender'] ?? 'M',
            'foreign' => '..',
            'telcoCd' => '..',
            'ci' => $data['ci'],
            'phoneNo' => $data['phoneNo'],
            'name' => $data['name'],
            'birthdate' => $data['birthdate'],
        ];

        $re['user_email'] = MOBILE_PREFIX . "$re[phoneNo]@passlogin.com";
        $res = $this->loginOrRegister($re);
        return $res;
    }

    public function profile() {
        return userProfile();
    }



    public function generateDailyBonus() {
        $row = getMyBonusJewelry();
        if ( ! $row ) {

            $user_ID = wp_get_current_user()->ID;
            generateDailyBonus($user_ID);
            $row = getMyBonusJewelry();
            $row['isNew'] = true;
            return $row;
        }
        else {
            $row['isNew'] = false;
            return $row;
        }
    }

    public function getDailyBonus() {
        $row['isNew'] = false;
        return getMyBonusJewelry();
    }


    public function recommend() {
        return giveJewelry(in());
    }

    public function updateLocationData() {

    }

    public function userSearchByLocation() {
return        userSearchByLocation(in());
    }
}