<?php


if ( in('mode') == 'save' ) {

    $in = in();
    unset($in['page'], $in['mode']);

    d($in);
    foreach($in as $k => $v) {
        update_option($k, $v, false);
    }

    /// Firebase realtime database.
}
?>

<form method="post">
    <input type="hidden" name="page" value="admin/settings/settings">
    <input type="hidden" name="mode" value="save">


    <div>
        <div>
            Search categories:
            If set, only these categories will be searched. By default, search all categories.</div>
        <input type="text" name="search_categories" value="<?=get_option('search_categories')?>">
    </div>

    <hr>
<h2>Forum Global Settings</h2>
    <div>
        <div>
            Like
        </div>
        <label>
            <input type="radio" name="forum_like" value="Y" <? if ( get_option('forum_like') == 'Y' ) echo 'checked'; ?>> Show
        </label>
        <label>
            <input type="radio" name="forum_like" value="N" <? if ( get_option('forum_like') == 'N' ) echo 'checked'; ?>> Hide
        </label>
    </div>
    <div>
        <div>
            Dislike
        </div>
        <label>
            <input type="radio" name="forum_dislike" value="Y" <? if ( get_option('forum_dislike') == 'Y' ) echo 'checked'; ?>> Show
        </label>
        <label>
            <input type="radio" name="forum_dislike" value="N" <? if ( get_option('forum_dislike') == 'N' ) echo 'checked'; ?>> Hide
        </label>
    </div>
    <hr>


    <div>
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>
