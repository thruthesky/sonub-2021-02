<?php


define('V3_DIR', '.');
require_once(V3_DIR . '/../wp-load.php');
require_once(V3_DIR . '/v3-load.php');


$str = <<<EOH
{"user_ID":"555","name":"Mrs. Rae Witting II","birthdate":"990309","gender":"F","height":"189","weight":"89","city":"Los Angeles","drinking":"","smoking":"","hobby":"Movies","dateMethod":"Drinking coffee","profile_photo_url":"https://local.nalia.kr/wp-content/uploads/2021/01/beef9a768411dbe2b6d85e73b2e39420.jpg","createdAt":"1610354759","updatedAt":"1610427616"}
{"user_ID":"555","name":"Mrs. Rae Witting II","birthdate":"990309","gender":"F","height":"189","weight":"89","city":"Los Angeles","drinking":"","smoking":"","hobby":"Movies","dateMethod":"Drinking coffee","profile_photo_url":"https://www.nalia.kr/wp-content/uploads/update.jpg","createdAt":"1610354759","updatedAt":"1610427616"}
{"user_ID":"555","name":"Mrs. Rae Witting II","birthdate":"990309","gender":"F","height":"189","weight":"89","city":"Los Angeles","drinking":"","smoking":"","hobby":"Movies","dateMethod":"Drinking coffee","profile_photo_url":"http://127.0.0.1/wp-content/uploads/update.jpg","createdAt":"1610354759","updatedAt":"1610427616"}
EOH;

$re = replace_host_of_image_url_to_request_host($str, '192.168.0.999');

echo "$re\n";
