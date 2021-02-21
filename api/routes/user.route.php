<?php

class UserRoute {
    public function register($in) {
        return register($in);
    }
    public function login($in) {
        return login($in);
    }
    public function profile() {
        return profile();
    }

    public function otherProfile($in) {
        return other_profile($in['id']);
    }

    public function loginOrRegister($in) {
        return login_or_register($in);
    }

    public function profileUpdate($in) {
        return profile_update($in);
    }



//    public function pointUpdate($in) {
//        return point_update($in);
//    }

}