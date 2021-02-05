<?php
if ( in('mode') == 'delete' ) {
    wp_delete_post(in('ID'));
}
?>
<section class="wrong-picture">
    <h1>틀린 그림 찾기</h1>
    <ul>
        <li>이미지 너비: 200px, 높이: 256px</li>
    </ul>
    <form @submit.prevent="onFormSubmit()">
        <div class="d-flex justify-content-center">
            <? function image_pair($ab, $name) { ?>
                <div class="position-relative of-hidden <?=$ab?>">
                    <div class="w-100px h-xxxl">
                        <i class="fa fa-file-image fs-xxxl" v-if="!<?=$ab?>"></i>
                        <img :src="<?=$ab?>" class="w-100" v-if="<?=$ab?>">
                    </div>
                    <div class="mt-2 text-center">
                        <?=$name?>
                    </div>
                    <input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onFileChange($event, '<?=$ab?>')">
                </div>
            <? } ?>
            <? image_pair('A', '올바른 사진(A)') ?>
            <? image_pair('B', '다른 사진(B)') ?>
        </div>
        <div class="d-flex justify-content-center">
            <div class="d-flex flex-column">
                <div class="progress mt-3 w-100px" style="height: 5px;" v-if="uploadPercentage > 0">
                    <div class="progress-bar" role="progressbar" :style="{width: uploadPercentage + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                    <button class="mt-3" type="submit">문제 등록</button>
            </div>
        </div>
    </form>
    <div class="posts">
        <?
        $posts = forum_search(['category_name' => 'wrong_picture']);
        foreach($posts as $post) {
            ?>
            <article class="post p-3">
                <div class="d-flex">
                    <div>
                        번호: <?=$post['ID']?>
                        <div>
                            <a href="/?page=admin/game/find_wrong_picture&mode=delete&ID=<?=$post['ID']?>" class="btn btn-secondary">삭제하기</a>
                        </div>
                    </div>
                    <div class="ms-2 w-100px h-xxxl">
                        <img class="w-100" src="<?=$post['A']?>">
                    </div>
                    <div class="ms-2 w-100px h-xxxl">
                        <img class="w-100" src="<?=$post['B']?>">
                    </div>
                </div>
            </article>
        <?
        }
        ?>
    </div>
</section>

<script>
    const mixin = {
        data() {
            return {
                A: '',
                B: '',
            };
        },
        methods: {
            onFileChange(event, AB) {
                console.log(event);
                console.log(AB);
                this.onFileUpload(event, function (res) {
                    console.log('uploaded file: res: ', res);
                    app[AB] = res.url;
                });
            },
            onFormSubmit() {
                const data = {
                    category: 'wrong_picture',
                    post_title: 'wrong picture',
                    A: this.A,
                    B: this.B,
                }
                request('forum.editPost', data, function(post) {
                    console.log('post edit', post);
                    refresh();
                }, this.error);
            }
        }
    }
</script>
<style>
    .wrong-picture form .B {
        margin-left: 1em;
        color: red;
    }
</style>