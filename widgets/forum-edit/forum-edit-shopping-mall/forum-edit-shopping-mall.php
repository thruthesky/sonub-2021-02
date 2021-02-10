<?php
$post = null;
if (in('category')) {
    $category = in('category');
} else {
    $post = post_response(in('ID'));
    $category = $post['category'];
}
?>

<h1> Shopping mall edit : <?php echo $category ?></h1>

<form @submit.prevent="onFormSubmit($event)">
    <?php if ($post != null) { ?> <input type="hidden" id="ID" name="ID" value="<?php echo $post['ID'] ?>"> <?php } ?>
    <input type="hidden" id="category" name="category" value="<?php echo $category ?>">
    <div class="form-group mb-3">
        <label for="short_title">짧은 제목</label>
        <input type="text" class="form-control" id="short_title" name="short_title" value="<?php echo $post != null ? $post['short_title'] : '' ?>">
        <div class="form-text">
            메인 화면이나 위젯에 표시할 짧은 제목. 한글 8글자.
        </div>
    </div>

    <div class="form-group mb-3">
        <label for="post_title">제목</label>
        <input type="text" class="form-control" id="post_title" name="post_title" value="<?php echo $post != null ? $post['post_title'] : '' ?>">
    </div>

    <div class="form-group mb-3">
        <label for="price">가격</label>
        <input type="number" class="form-control" id="price" name="price" value="<?php echo $post != null ? $post['price'] : '' ?>">
    </div>


    <div class="form-group mb-3">
        <label for="discount_rate">할인율</label>
        <input type="number" class="form-control" id="discount_rate" name="discount_rate" value="<?php echo $post != null ? $post['discount_rate'] : '' ?>">
        <div class="form-text">
            단위 %. 가격에서 자동으로 할인율이 계산되어 화면에 표시됩니다.
        </div>
    </div>
    <div>
        모든 사진은 GIF 애니메이션 사진 가능하며, .gif 또는 .jpg, .png 사진이 가능합니다.
    </div>
    <? function image_pair($field_name, $name, $desc) { ?>
        <div class="mb-3">
            <div class="position-relative d-flex align-items-center p-2 bg-light">
                <div>
                    <i class="fa fa-file-image fs-xl"></i>
                </div>
                <div class="ms-2">
                    <?=$name?>
                </div>
                <input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onFileChange($event, '<?=$field_name?>')">
            </div>
            <div class="form-text"><?=$desc?></div>
            <div>
                <img :src="<?=$field_name?>" class="w-100" v-if="<?=$field_name?>">
            </div>
        </div>
    <? } ?>
    <? image_pair('item_primary_photo', '상품 대표 사진 업로드', '상품 보기 페이지 맨 위에 나오는 사진.') ?>
    <? image_pair('item_widget_photo', '상품 위젯 사진 업로드', '메인 화면이나 위젯에 나오는 사진.') ?>
    <? image_pair('item_detail_photo', '상품 설명 사진 업로드', '상품 설명 사진은 가능한 JPG 로 용량이 작게해서 업로드.') ?>

    <div class="d-flex justify-content-between mt-2">
        <div></div>
        <div>
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
            <button type="submit" class="btn btn-primary ms-3">Submit</button>
        </div>
    </div>


</form>



<script>
    const mixin = {
        data() {
            return {
                images : {},
            };
        },
        methods: {
            // 사진 업로드를 하면, 서버에 올리고, $data 에 저장
            onFileChange(event, field_name) {
                this.onFileUpload(event, function (res) {
                    app.images[field_name] = res.url;
                });
            },
            onFormSubmit(event) {
                const data = serializeFormEvent(event);
                Object.assign(data, app.images);
                console.log('data: ', data);
                request('forum.editPost', data, function(post) {
                    console.log('res: ', post);
                    // refresh();
                }, this.error);
            }
        }
    }
</script>
