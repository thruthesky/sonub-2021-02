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



    public function loginOrRegister($in) {
        return loginOrRegister($in);
    }

    public function profileUpdate($in) {
        return profileUpdate($in);
    }


}