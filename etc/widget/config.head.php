<?php




$o = get_widget_options();
$dwo = get_dynamic_widget_options($o['id']);
if ( empty($dwo) ) $dwo = $o;

if ( in('mode') == 'save' ) {

    $in = in();
    unset($in['mode'], $in['page'], $in['update_widget']);
    $dwo = array_merge($dwo, $in);

    set_dynamic_widget_options($o['id'], $dwo);
    $id = $o['id'];
    jsGo("/?page=home&update_widget={$id}#$id");
}

?>

<section>
    <div class="fs-xs">위젯 설정 ID: <?=$dwo['id']?></div>
    <form>
        <input type="hidden" name="mode" value="save">
        <input type="hidden" name="page" value="<?=in('page')?>">
        <input type="hidden" name="update_widget" value="<?=in('update_widget')?>">

        <div>
            <select name="widget_type" onchange="this.form.submit()">
                <option value="">위젯 타입 선택</option>
                <option value="posts" <? if ( $dwo['widget_type'] == 'posts') echo 'selected'; ?>>최근 글(사진) 목록</option>
                <option value="statistics" <? if ( $dwo['widget_type'] == 'statistics') echo 'selected'; ?>>통계</option>
            </select>
        </div>
<?php if ( $dwo['widget_type'] ) { ?>

        <div>
            <select name="path" onchange="this.form.submit()">
            <option value="">위젯 선택</option>
                <?
                select_list_widgets_option($dwo['widget_type'], $dwo['path']);
                ?>
            </select>
        </div>

        <? } ?>