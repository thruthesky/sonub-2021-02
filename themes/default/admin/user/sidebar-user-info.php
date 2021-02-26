<?php
    $user = profile(in('user_ID'));
    ?>

    <div class="option-box mb-3">
        <? if ( isset($user['profile_photo_url']) && $user['profile_photo_url'] ) { ?>
            <img class="avatar d-block size-128" src="<?=$user['profile_photo_url']?>">
        <? } ?>

        <b><?=$user['name'] ?? '??'?> (<?=$user['gender'] ?? '?'?>/<?=$user['birthdate'] ?? '?'?>)</b>
        <div>
            <?=$user['phoneNo'] ?? '- - -'?>
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
            보유 포인트: <?=number_format($user['point'])?>
            <a href="/?page=admin.user.point-history&user_ID=<?=$user['ID']?>">포인트 기록 보기</a>
        </div>

        <div>
            작성한 글 보기
        </div>
        <div>
            작성한 코멘트 보기
        </div>

    </div>
