<?php


$dwo = get_widget_options();
//$dwo = get_dynamic_widget_options($o['widget_id']);
//if ( empty($dwo) ) $dwo = $o;


if ( in('mode') == 'save' ) {
    $in = in();
    unset($in['mode'], $in['page'], $in['update_widget']);
    $dwo = array_merge($dwo, $in);
    /// 위젯 타입이 없이 전송되면 초기화 하는 것이다. 그냥 설정을 삭제한다.
    if ( empty($in['widget_type']) ) {
        $dwo['widget_type'] = '';
        $dwo['path'] = '';
        delete_dynamic_widget_options($dwo['widget_id']);
    } else {
        if ( !isset($dwo['posts_per_page']) || empty($dwo['posts_per_page']) ) $dwo['posts_per_page'] = 5;
        else if ( $dwo['posts_per_page'] > 20 ) $dwo['posts_per_page'] = 20;

        set_dynamic_widget_options($dwo['widget_id'], $dwo);
    }
    $mode = time();
    jsGo("/?page=home&update_widget={$dwo['widget_id']}&mode=$mode#$dwo[widget_id]");
    exit;
}

?>
<div class="fs-xs">위젯 설정을 변경합니다.</div>
<section class="alert alert-info">
    <form method="post">
        <input type="hidden" name="mode" value="save">
        <input type="hidden" name="page" value="<?=in('page')?>">
        <input type="hidden" name="update_widget" value="<?=in('update_widget')?>">

        <div>
            <select class="form-select mb-2" name="widget_type" onchange="this.form.submit()">
                <option value="">위젯 타입 선택</option>
                <option value="posts" <? if ( $dwo['widget_type'] == 'posts') echo 'selected'; ?>>타입: 최근 글 또는 사진 목록</option>
                <option value="login" <? if ( $dwo['widget_type'] == 'login') echo 'selected'; ?>>타입: 로그인</option>
                <option value="statistics" <? if ( $dwo['widget_type'] == 'statistics') echo 'selected'; ?>>타입: 통계</option>
            </select>
        </div>
<?php if ( $dwo['widget_type'] ) { ?>

        <div>
            <select class="form-select mb-1" name="path" onchange="this.form.submit()">
            <option value="">위젯 선택</option>
                <?
                select_list_widgets_option($dwo['widget_type'], $dwo['path']);
                ?>
            </select>
        </div>

        <? } ?>