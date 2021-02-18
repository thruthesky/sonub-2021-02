<?php
$post = null;
if (in('category')) {
    $category = in('category');
} else {
    $post = post_response(in('ID'));
    $category = $post['category'];
}
?>
<h1>상품 등록</h1>

<form @submit.prevent="onFormSubmit($event)">

    <div class="form-group mb-3">
        <label for="post_title">제목</label>
        <input type="text" class="form-control" id="post_title" name="post_title" v-model="post.post_title">
    </div>


    <div class="form-group mb-3">
        <label for="short_title">짧은 제목</label>
        <input type="text" class="form-control" id="short_title" name="short_title" v-model="post.short_title">
        <div class="form-text">
            메인 화면이나 위젯에 표시할 짧은 제목. 한글 8글자.
        </div>
    </div>

    <div class="form-group mb-3">
        <label for="short_title">키워드(또는 카피)</label>
        <input type="text" class="form-control" id="keywords" name="keywords" v-model="post.keywords">
        <div class="form-text">
            상품을 설명을 할 때, 보여지는 짧은 키워드 문구. 한 줄로 입력 할 수 있으며, 콤마로 구분하여 입력 가능.
            <div class="d-block hint">예) 여름 신상품 초특가 세일</div>
            <div class="d-block hint">예) 신발,장화</div>
        </div>
    </div>

    <div class="form-group mb-2">
        <label for="price">가격</label>
        <input type="number" class="form-control" id="price" name="price" v-model="post.price">
    </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="leatPrice" name="least_price"  v-model="post.least_price">
            <label class="form-check-label" for="leatPrice">
                옵션에 상품 가격 입력 <a href="https://docs.google.com/document/d/1JnEIoytM1MgS35emOju90qeDoIH963VeMHLaqvOhA7o/edit#heading=h.t9yy0z10h3rp" target="_blank">[?]</a>
            </label>
        </div>



    <div class="form-group mb-3">
        <label for="discount_rate">할인율</label>
        <input type="number" class="form-control" id="discount_rate" name="discount_rate" v-model="post.discount_rate">
        <div class="form-text">
            단위 %. 가격에서 자동으로 할인율이 계산되어 화면에 표시됩니다.
        </div>
    </div>


    <div class="form-group mb-3">
        <label for="point">적립포인트</label>
        <input type="number" class="form-control" id="point" name="point" v-model="post.point">
        <div class="form-text">
            화면 표시 예) 적립 포인트 1,000 Point 지급
        </div>
    </div>



    <div class="form-check form-switch">
        <input class="form-check-input" :class="{ 'bg-danger border-danger': post.stop, 'bg-primary border-primary': !post.stop }" type="checkbox" id="stop" name="stop" v-model="post.stop">
        <label class="form-check-label fs-md" :class="{ blue: post.stop == false, red: post.stop }" for="stop">
            {{ post.stop ? "중단 된 상태. 운영하기!" : "운영 중" }}
        </label>
    </div>
    <div class="form-text">본 상품을 사이트(앱)에 노출이 안되도록 일시 중지 할 수 있습니다. 버튼이 빨간색이면 일시 중단된 상태.</div>


    <div class="form-group mb-3">
        <label for="volume">용량, 수량</label>
        <input type="number" class="form-control" id="volume" name="volume" v-model="post.volume">
        <div class="form-text">
            상품의 크기나, 용량, 수량을 입력하세요.
        </div>
    </div>



    <div class="form-group mb-3">
        <label for="short_title"><a href="https://docs.google.com/document/d/1JnEIoytM1MgS35emOju90qeDoIH963VeMHLaqvOhA7o/edit#heading=h.inp7ewl4tmv3" target="_blank">옵션 [?]</a></label>
        <input type="text" class="form-control" id="options" name="options" v-model="post.options">
        <div class="form-text">
            <a href="https://docs.google.com/document/d/1JnEIoytM1MgS35emOju90qeDoIH963VeMHLaqvOhA7o/edit#heading=h.inp7ewl4tmv3" target="_blank">상품 옵션 설명 참고 [?]</a>

        </div>
    </div>


    <div class="form-group mb-3">
        <label for="delivery_fee">배송비</label>
        <input type="number" class="form-control" id="delivery_fee" name="delivery_fee" v-model="post.delivery_fee">
        <div class="form-text">
            배송비를 입력하세요.
        </div>
    </div>


    <div class="form-group mb-3">
        <label for="storage_method">보관방법</label>
        <input type="text" class="form-control" id="storage_method" name="storage_method" v-model="post.storage_method">
        <div class="form-text">
            보관 방법을 입력하세요. 예) 냉장고에 보관하세요.
        </div>
    </div>


    <div class="form-group mb-3">
        <label for="expiry">유통기한</label>
        <input type="text" class="form-control" id="expiry" name="expiry" v-model="post.expiry">
        <div class="form-text">
            유통 기한을 입력하세요. 예) 2022년 3월 30일 까지.
        </div>
    </div>





    <div class="mt-3">
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

        <img :src="post[image.field]" class="mw-100" v-if="post[image.field]">
    </div>


    <div class="d-flex justify-content-between mt-2 mb-3">
        <div></div>
        <div>
            <a href="/?page=forum.list&category=<?=$category?>" type="button" class="btn btn-secondary">목록으로 돌아가기</a>
            <button type="submit" class="btn btn-primary ms-2" :disabled="loading">
                <div class="spinner-grow spinner-grow-sm" role="status" v-if="loading">
                    <span class="visually-hidden">Loading...</span>
                </div>
                저장
            </button>

        </div>
    </div>

</form>

<script>
    const mixin = {
        data() {
            return {
                loading: false,
                post: {
                    category: "<?=$category?>",
                },
                postId: <?=in('ID', 0)?>,
                images: [
                    {field: 'item_primary_photo', title: '상품 대표 사진 업로드', desc: '필수 항목. 상품 보기 페이지 맨 위에 나오는 사진. 크기: 너비 1024px, 높이: 자유' },
                    {field: 'item_widget_photo', title: '상품 위젯 사진 업로드', desc: '선택 항목. 메인 화면이나 위젯에 작게 나오는 사진으로 업로드하지 않으면, 대표 사진이 사용됩니다. 크기: 너비 400px, 높이 300px.' },
                    {field: 'item_detail_photo', title: '상품 설명 사진 업로드', desc: '필수 항목. 상품 설명 사진. 크기: 너비 1024px, 높이: 자유, JPG 로 용량이 작게것 업로드 할 것.' },
                ]
            };
        },
        created() {
            /// 페이지가 로딩되면, 게시글을 원격에서 가져온다.
            if ( this.postId ) {
                request('forum.getPost', {id: this.postId }, function (res) {
                    app.post = res;
                    // true, false 를 DB 에 저장하면, 1, 0 이 되는데, 아래와 같이 boolean 으로 변환 해 주어야 한다.
                    app.post.stop = app.post.stop === '1';
                    app.post.least_price = app.post.least_price === '1';
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
            // 폼을 저장하고 refresh.
            onFormSubmit(event) {
                app.loading = true;
                request('forum.editPost', app.post, function(post) {
                    move("/?page=forum.edit&ID=" + post.ID);
                }, this.error);
            }
        }
    }
</script>
