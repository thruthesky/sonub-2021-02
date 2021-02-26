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

    /// 사용자의 포인트 기록을 리턴한다.
    public function point($in) {
        return get_point_history([ 'to_user_ID' => my('ID') ]);
    }
}