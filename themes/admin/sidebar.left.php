<section class="l-sidebar d-none d-md-block mt-3 of-hidden">

    <? if ( in('user_ID') ) {
        $_user = profile(in('user_ID'));
        ?>

        <div class="option-box">
            <? if ( isset($_user['profile_photo_url']) && $_user['profile_photo_url'] ) { ?>
                <img class="avatar d-block size-128" src="<?=$_user['profile_photo_url']?>">
            <? } ?>

            <b><?=$_user['name'] ?? '??'?> (<?=$_user['gender'] ?? '?'?>/<?=$_user['birthdate'] ?? '?'?>)</b>
            <div>
                <?=$_user['phoneNo'] ?? '- - -'?>
            </div>
            <div>
                가입 날짜:
            </div>
            <div>
                특이 사항:
            </div>
            <div class="hint">
                특이 사항은 관리자만 볼 수 있습니다.
            </div>

            <div>
                보유 포인트: 1,000,000
                포인트 기록 보기
            </div>

            <div>
                작성한 글 보기
            </div>
            <div>
                작성한 코멘트 보기
            </div>

        </div>

    <? } ?>

    <?php
    $_path = get_theme_page_script_path();
    $_arr = explode("/", $_path);
    $file_name = array_pop($_arr);
    $folder_name = array_pop($_arr);
    $_path = implode("/", $_arr) . "/" . "$folder_name/$folder_name.sidebar.php";

    if ( file_exists($_path) ) include $_path;
    else include "sidebar.home.php";

    ?>


</section>

<style>
    .l-sidebar {
        width: 360px !important;
        min-width: 360px !important;
        max-width: 360px !important;
    }
    .l-sidebar::after {
        content: '';
        display: block;
        width: 360px !important;
        min-width: 360px !important;
        max-width: 360px !important;
    }
</style>