<?php

$o = get_widget_options();

?>
<div class="box mb-2">
    <div class="d-flex justify-content-between">
        <div><?=$o['widget_title']?></div>
        <div>
            <i class="fa fa-cog"></i>
        </div>
    </div>
</div>

<?php
global $widget_dialog_included;
if ( ! isset($widget_dialog_included) ) {
    $widget_dialog_included = true;
    ?>
    카페 관리자인 경우에만 위젯 설정을 보여주고,
    각 위젯 스크립트에서 설정을 따로 하도록 한다.
    그리고, dialog id 를 해당 위젯 id 로 하면 출돌이 없다.
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Open modal for @mdo</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@fat">Open modal for @fat</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Open modal for @getbootstrap</button>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">위젯 설정</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="d-flex mb-3">
                            <label for="recipient-name" class="col-form-label w-64px">제목:</label>
                            <input type="text" class="form-control" id="recipient-name">
                        </div>
                        <div class="d-flex mb-3">
                            <label for="message-text" class="col-form-label w-64px">글 수:</label>
                            <input type="text" class="form-control" id="recipient-name">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send message</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>