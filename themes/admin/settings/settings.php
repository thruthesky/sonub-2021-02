<?php


if ( in('mode') == 'save' ) {
    $in = in();
    unset($in['page'], $in['mode']);

    api_update_settings($in);
}
$in = api_get_settings();

?>
<section>

    @todo 저장을 그냥 PHP submit 으로 하는데, Vue.js Axios 로 할 것.
    각 항목 별로 저장하는데, 양식에서 값이 변경되면, 저장 버튼을 보여줄 것.


    <form method="post">
        <input type="hidden" name="page" value="admin/settings/settings">
        <input type="hidden" name="mode" value="save">



        <hr>
        <h2>Forum Global Settings</h2>

        <div>
            <div>
                Search categories:
                If set, only these categories will be searched. By default, search all categories.</div>
            <input class="w-100" type="text" name="search_categories" v-model="settings.search_categories">
        </div>
        <div>
            <div>
                Like
            </div>
            <label>
                <input type="radio" name="forum_like" value="Y" v-model="settings.forum_like"> Show
            </label>
            <label>
                <input type="radio" name="forum_like" value="N" v-model="settings.forum_like"> Hide
            </label>
        </div>
        <div>
            <div>
                Dislike
            </div>
            <label>
                <input type="radio" name="forum_dislike" value="Y" v-model="settings.forum_dislike"> Show
            </label>
            <label>
                <input type="radio" name="forum_dislike" value="N" v-model="settings.forum_dislike"> Hide
            </label>
        </div>

        <div>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>

        <hr>
        <h2>Point Settings</h2>
<div>
    <div>
        회원 가입: <input>
    </div>
    <div>
        글 작성: <input>, 1일 포인트 제한 글 개수: <input>
        글 삭제: <input>
    </div>
    <div>
        코멘 작성: <input>, 1일 포인트 제한 코멘트 개 수: <input>
        코멘트 삭제: <input>
    </div>
    <div>
        추천: <input>,
        비 추천: <input>
    </div>
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