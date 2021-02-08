<?php

$dwo = get_widget_options();
if ( in('mode') == 'save' ) {
    d(in());
    d($dwo);

    $dwo['widget_type'] = in('widget_type');
    $dwo['widget_title'] = in('widget_title');
    $dwo['path'] = in('path');

}
?>

<section>
    <div class="fs-xs">위젯 설정 ID: <?=$dwo['id']?></div>
    <form>
        <input type="hidden" name="mode" value="save">
        <input type="hidden" name="page" value="<?=in('page')?>">
        <input type="hidden" name="update_widget" value="<?=in('update_widget')?>">
        <select name="widget_type" onchange="this.form.submit()">
            <option value="">위젯 타입 선택</option>
            <option value="posts" <? if ( in('widget_type') == 'latest-posts') echo 'selected'; ?>>최근 글(사진) 목록</option>
            <option value="statistics" <? if ( in('widget_type') == 'statistics') echo 'selected'; ?>>통계</option>
        </select>

        <select name="path">
            <?
            select_list_widgets_option($dwo['widget_type'], '');
            ?>
        </select>

        <div>
            <button class="btn btn-sm btn-secondary" type="submit">저장</button>
        </div>
    </form>
</section>

