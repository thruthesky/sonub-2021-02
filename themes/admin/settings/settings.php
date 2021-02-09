<?php


if ( in('mode') == 'save' ) {
    $in = in();
    unset($in['page'], $in['mode']);

    api_update_settings($in);
}
$in = api_get_settings();

?>
<section>
    @TODO: Textarea 에서 \n 이 안먹는다. vue.js 때문에 그런 것 같다. axio 로 로드해서 보여준다.

    <form method="post">
        <input type="hidden" name="page" value="admin/settings/settings">
        <input type="hidden" name="mode" value="save">



        <hr>
        <h2>Forum Global Settings</h2>

        <div>
            <div>
                Search categories:
                If set, only these categories will be searched. By default, search all categories.</div>
            <input class="w-100" type="text" name="search_categories" value="<?=$in['search_categories']?>">
        </div>
        <div>
            <div>
                Like
            </div>
            <label>
                <input type="radio" name="forum_like" value="Y" <? if ( $in['forum_like'] == 'Y' ) echo 'checked'; ?>> Show
            </label>
            <label>
                <input type="radio" name="forum_like" value="N" <? if ( $in['forum_like'] == 'N' ) echo 'checked'; ?>> Hide
            </label>
        </div>
        <div>
            <div>
                Dislike
            </div>
            <label>
                <input type="radio" name="forum_dislike" value="Y" <? if ( ($in['forum_dislike'] ?? '') == 'Y' ) echo 'checked'; ?>> Show
            </label>
            <label>
                <input type="radio" name="forum_dislike" value="N" <? if ( ($in['forum_dislike'] ?? '') == 'N' ) echo 'checked'; ?>> Hide
            </label>
        </div>

        <div>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>

        <hr>

        <h2>User Agreements</h2>

        <h3>Terms and Conditions</h3>
        <textarea class="w-100" rows="10" name="terms_and_conditions"><?=$in['terms_and_conditions'] ?? ''?></textarea>

        <h3>Privacy Policy</h3>
        <textarea class="w-100" rows="10" name="privacy_policy"><?=$in['privacy_policy'] ?? ''?></textarea>

        <div>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>

        <hr>

    </form>

</section>