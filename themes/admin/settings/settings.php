<?php


if ( in('mode') == 'save' ) {
    $in = in();
    unset($in['page'], $in['mode']);
    api_update_settings($in);
    jsGo('/?page=admin.settings.settings');
    return;
}
$in = api_get_settings();

?>
<section>
    <div class="alert alert-secondary">
        <h3>사이트 설정</h3>
        <hr>
        글로벌 설정입니다.
    </div>


    <form action="/" method="post">
        <input type="hidden" name="page" value="admin/settings/settings">
        <input type="hidden" name="mode" value="save">

<h2>기본 설정</h2>


        <div class="mb-3">
            <label for="site_name" class="form-label"><?=ln('Site Name', '사이트 이름')?></label>
            <input name="site_name" type="text" class="form-control" id="site_name" placeholder="사이트 이름을 입력하세요."  v-model="settings.site_name">
            <div id="site_name_text" class="form-text">
                웹 브라우저 상단 제목이나 검색 엔진에 색인 될 사이트 이름입니다.
                가능한 특수 문자를 입력하지 마세요.
            </div>
        </div>




        <hr>
        <h2><?=ln("Global Forum Settings", "게시판 설정")?></h2>

        <div>
            <label for="search_categories" class="form-label"><?=ln("Search Categories", "검색 가능한 카테고리")?></label>
            <input class="form-control" id="search_categories" type="text" name="search_categories" v-model="settings.search_categories">
            <div class="form-text">
                여기에 기록하는 카테고리만 검색이 됩니다. 공백으로 구분해서 입력 가능. 예) qna,job<br>
                검색을 할 때, 전체 게시판 검색을 할 수 있게 하려면, 공백으로 두세요.
            </div>


        </div>


        <label>
            Like
        </label>
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" id="toggle_forum_like"  type="radio" name="forum_like" value="Y" v-model="settings.forum_like">
                <label class="form-check-label" for="toggle_forum_like">보이기</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" id="toggle_forum_like2"  type="radio" name="forum_like" value="N" v-model="settings.forum_like">
                <label class="form-check-label" for="toggle_forum_like2">숨기기</label>
            </div>

        </div>

        <label>
            Dislike
        </label>
        <div>


            <div class="form-check form-check-inline">
                <input class="form-check-input" id="show_forum_dislike"  type="radio" name="forum_dislike" value="Y" v-model="settings.forum_dislike">
                <label class="form-check-label" for="show_forum_dislike">보이기</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" id="hide_forum_dislike"  type="radio" name="forum_dislike" value="N" v-model="settings.forum_dislike">
                <label class="form-check-label" for="hide_forum_dislike">숨기기</label>
            </div>



        </div>

        <div>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>


        <hr>


        <h2>User Agreements</h2>
<div class="row">
    <div class="col-6">

        <h3>Terms and Conditions</h3>
        <textarea class="w-100" rows="4" name="terms_and_conditions" v-model="settings.terms_and_conditions"></textarea>

    </div>
    <div class="col-6">

        <h3>Privacy Policy</h3>
        <textarea class="w-100" rows="4" name="privacy_policy" v-model="settings.privacy_policy"></textarea>

    </div>
</div>
        <div>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>

        <hr>

    </form>

</section>


<script>
    const mixin = {
        data() {
            return {
                settings: {},
            }
        },
        created() {
            request('app.settings', undefined, function(re) {
                app.settings = re;
            }, alert);
        },
    }
</script>