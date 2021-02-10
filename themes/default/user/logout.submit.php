<?php
/**
 * @file loguot.submit.php
 */
/**
 * App.js 와 호환되는 로그아웃
 */
delete_login_cookies();
wp_logout(); // 워드프레스 로그아웃도 같이 함.
jsGo('/');
