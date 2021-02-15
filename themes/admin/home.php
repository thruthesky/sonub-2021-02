

<h1>관리자 홈</h1>



<div class="option-box mt-5">
    <h2>사용자 검색</h2>
    <div class="hint">
        사용자를 검색하여, 회원 정보, 포인트, 글 목록 등을 확인 해 보세요.
    </div>
    <hr>
    <input>
</div>

<div class="option-box mt-4">
    <h2>언어 설정</h2>
    <div class="hint">
        관리자 페이지를 영어 또는 한국어를 선택하여 볼 수 있습니다.
    </div>
    <hr>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="language" id="korean" value="ko" <? if ( get_user_language() == 'ko' ) echo 'checked'; ?>
        onclick="location.href='<?=set_cookie_url('language', 'ko', '/?page=admin/home')?>'"
        >
        <label class="form-check-label" for="korean">한국어</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="language" id="english" value="en" <? if ( get_user_language() == 'en' ) echo 'checked'; ?>
               onclick="location.href='<?=set_cookie_url('language', 'en', '/?page=admin/home')?>'"
        >
        <label class="form-check-label" for="english">영어</label>
    </div>
</div>
