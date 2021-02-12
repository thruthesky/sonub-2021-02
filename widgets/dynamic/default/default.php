<?php

$o = get_widget_options();

?>

<div class="box mb-2">
    <div class="d-flex justify-content-between">
        <div>위젯 선택</div>
    </div>
    <div class="mt-3">
        앗! 위젯이 선택되지 않았습니다.<br>
        <?=update_widget_icon($o['widget_id'])?> 아이콘을 클릭하셔서,
        멋진 위젯을 선택해보세요.
    </div>
</div>

