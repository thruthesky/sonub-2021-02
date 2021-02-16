<?php

if ( in('mode') == 'save' ) {
    update_option(POINT_REGISTER, in(POINT_REGISTER));
    update_option(POINT_LOGIN, in(POINT_LOGIN));
    update_option(POINT_LIKE, in(POINT_LIKE));
    update_option(POINT_DISLIKE, in(POINT_DISLIKE));
    update_option(POINT_LIKE_DEDUCTION, in(POINT_LIKE_DEDUCTION));
    update_option(POINT_DISLIKE_DEDUCTION, in(POINT_DISLIKE_DEDUCTION));
    update_option(POINT_LIKE_HOUR_LIMIT, in(POINT_LIKE_HOUR_LIMIT));
    update_option(POINT_LIKE_COUNT_LIMIT, in(POINT_LIKE_COUNT_LIMIT));
}


?>

<h1>포인트 설정</h1>

<div class="hint">
    <ul>
        <li>
            음수 값을 지정하면 포인트가 차감됩니다.
        </li>
        <li>
            출석 도장은 게시판 설정으로 하면 됩니다.
            게시판 설정에 1일 1회 글 쓰기로 제한하고, 글 쓰기 포인트를 지정하면 되는 것입니다.
        </li>
    </ul>
</div>

<form action="?" method="post">
    <input type="hidden" name="page" value="admin/forum/point">
    <input type="hidden" name="mode" value="save">

    <div class="box border-radius-md">
        <div class="mb-3">
            <label class="form-label">가입 보너스</label>
            <input type="number" class="form-control" name="POINT_REGISTER" placeholder="0" value="<?=get_option(POINT_REGISTER, 0)?>">
        </div>
        <div class="mb-3">
            <label class="form-label">로그인 보너스</label>
            <input type="number" class="form-control" name="POINT_LOGIN" placeholder="0" value="<?=get_option(POINT_LOGIN, 0)?>">
        </div>
    </div>

    <div class="box border-radius-md mt-3">
        <h3>추천/비추천</h3>
        <hr>
        <div class="hint">
            <ul>
                <li>추천 받는 사람 포인트는 추천을 받는 사람(내가 아닌 다른 사람)이 얻게 되는 포인트.</li>
                <li>비추천 받는 사람 포인트는 비 추천을 받는 사람(내가 아닌 다른 사람)이 얻게되는 포인트. 주로 0 또는 음수 값.</li>
                <li>추천하는 사람 포인트는 추천을 하는 사람(나)이 얻게되는 포인트.</li>
                <li>비추천 받는 사람의 포인트는 비 추천을 받으면 얻게되는 포인트. 주로 0 또는 음수 값.</li>
                <li>
                    시간 제한과 회수 제한은 같이 사용되는 것으로 하루에 5번까지만 추천 포인트가 주어지게 한다면
                    시간에 24, 회수에 5를 입력하면 됩니다.
                    시간/회수 제한을 넘어서도 추천/비추천 가능하지만, 포인트 증/감은 하지 않습니다.
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col">
                <label class="form-label">추천 받는 사람 포인트</label>
                <input type="number" class="form-control" name="POINT_LIKE" placeholder="0" value="<?=get_option(POINT_LIKE, 0)?>">
            </div>
            <div class="col">
                <label class="form-label">비추천 받는 사람 포인트 </label>
                <input type="number" class="form-control" name="POINT_DISLIKE" placeholder="0" value="<?=get_option(POINT_DISLIKE, 0)?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <label class="form-label">추천하는 사람 포인트</label>
                <input type="number" class="form-control" name="POINT_LIKE_DEDUCTION" placeholder="0" value="<?=get_option(POINT_LIKE_DEDUCTION, 0)?>">
            </div>
            <div class="col">
                <label class="form-label">비추천 하는 사람 포인트</label>
                <input type="number" class="form-control" name="POINT_DISLIKE_DEDUCTION" placeholder="0" value="<?=get_option(POINT_DISLIKE_DEDUCTION, 0)?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <label class="form-label">추천/비추천 포인트 증/감 시간 제한</label>
                <input type="number" class="form-control" name="POINT_LIKE_HOUR_LIMIT" placeholder="0" value="<?=get_option(POINT_LIKE_HOUR_LIMIT, 0)?>">
            </div>
            <div class="col">
                <label class="form-label">추천/비추천 포인트 증/감 회수 제한</label>
                <input type="number" class="form-control" name="POINT_LIKE_COUNT_LIMIT" placeholder="0" value="<?=get_option(POINT_LIKE_COUNT_LIMIT, 0)?>">
            </div>
        </div>
    </div>

    <div class="d-grid">
        <button class="btn btn-primary mt-3" type="submit">저장</button>
    </div>

</form>



<div class="box border-radius-md mt-5">
    <h3>게시판 포인트 목록</h3>
    <hr>
</div>
@todo ...

