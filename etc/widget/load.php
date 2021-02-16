<?php

$o = get_widget_options();
/// @var $dwo 현재 위젯 설정 정보를 가지고 있다.
$dwo = get_dynamic_widget_options($o['widget_id']);

if ( ! $dwo || empty($dwo['path']) ) { // dynamic 위젯 설정이 안된 경우,
    if ( is_widget_edit_mode() ) { // 관리자가 위젯 설정을 한다고하면 기본 위젯 보여 줌
        $dwo['path'] = 'dynamic/default';
    } else {
        // 일반 사용자가 볼 때에는 위젯을 보여주지 않음.
        return;
    }
}
/// 위젯 옵션과 다이나믹 위젯 옵션을 합친다.
$dwo = array_merge($o, $dwo);

if ( !isset($dwo['widget_type']) ) $dwo['widget_type'] = '';


?>
<?php if ( is_cafe_admin() ) { ?>
    <div  id="<?=$dwo['widget_id']?>" class="widget-wrapper position-relative">
<?php } ?>
<?php
include widget($dwo['path'], $dwo);
?>
<?php if ( is_cafe_admin() && is_widget_edit_mode() ) { ?>
    <div class="position-absolute bottom right" style="z-index: 12345; padding: .5em;">
        <?=update_widget_icon($dwo['widget_id'])?>
    </div>
<?php } ?>
    </div>
<?php
/// 위젯 수정 모드이면, 선택된 위젯을 설정 할 수 있도록 한다.
/// $dwo 변수를 그대로 사용 할 수 있다.
if ( in('update_widget') == $o['widget_id'] ) {
    /// 위젯 수정. 현재 위젯이 업데이트하려는 위젯 id 와 동일하면 수정.
    /// @todo 현재 카페 관리자만 수정 할 수 있도록 한다.
    include THEME_DIR . '/etc/widget/config.head.php';
    include widget_config($dwo['path'], $dwo);
    include THEME_DIR . '/etc/widget/config.tail.php';
}