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
        <input type="text" class="form-control" id="short_title" name="short_title" v-model="post.short_title">
        <div class="form-text">
            메인 화면이나 위젯에 표시할 짧은 제목. 한글 8글자.
        </div>
    </div>

    <div class="form-group mb-3">
        <label for="post_title">제목</label>
        <input type="text" class="form-control" id="post_title" name="post_title" v-model="post.post_title">
    </div>

    <div class="form-group mb-3">
        <label for="price">가격</label>
        <input type="number" class="form-control" id="price" name="price" v-model="post.price">
    </div>


    <div class="form-group mb-3">
        <label for="discount_rate">할인율</label>
        <input type="number" class="form-control" id="discount_rate" name="discount_rate" v-model="post.discount_rate">
        <div class="form-text">
            단위 %. 가격에서 자동으로 할인율이 계산되어 화면에 표시됩니다.
        </div>
    </div>
    <div>
        모든 사진은 GIF 애니메이션 사진 가능하며, .gif 또는 .jpg, .png 사진이 가능합니다.
    </div>

    <div class="mb-3 of-hidden" v-for="image of images" :bind="image.field">

        <div class="position-relative d-flex align-items-center p-2 bg-light">
            <div>
                <i class="fa fa-file-image fs-xl"></i>
            </div>
            <div class="ms-2">
                {{ image.title }}
            </div>
            <input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onFileChange($event, image.field)">
        </div>
        <div class="form-text">{{ image.desc }}</div>


        <img :src="post[image.field]" class="w-100" v-if="post[image.field]">

    </div>


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
                post: {},
                postId: <?=in('ID', 0)?>,
                images: [
                    {field: 'item_primary_photo', title: '상품 대표 사진 업로드', desc: '상품 보기 페이지 맨 위에 나오는 사진.' },
                    {field: 'item_widget_photo', title: '상품 위젯 사진 업로드', desc: '메인 화면이나 위젯에 나오는 사진.' },
                    {field: 'item_detail_photo', title: '상품 설명 사진 업로드', desc: '상품 설명 사진. JPG 로 용량이 작게업로드.' },
                ]
            };
        },
        created() {
            if ( this.postId ) {
                request('forum.getPost', {id: <?=in('ID')?>}, function (res) {
                    app.post = res;
                }, alert);
            }
        },
        methods: {
            // 사진 업로드를 하면, 서버에 올리고, $data 에 저장
            onFileChange(event, field_name) {
                this.onFileUpload(event, function (res) {
                    console.log('field_name: ', field_name);
                    app.post[field_name] = res.url;
                });
            },
            onFormSubmit(event) {
                request('forum.editPost', app.post, function(post) {
                    refresh();
                }, this.error);
            }
        }
    }
</script>
