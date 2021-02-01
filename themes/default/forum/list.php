<?php
$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : 'qna';
$post_topic = NOTIFY_POST . $category;
$comment_topic = NOTIFY_COMMENT . $category;
?>
<hr>
<div class="p-2 d-flex justify-content-between">
    <h2>Forum List</h2>
    <a class="btn btn-success" href="/?page=forum/edit&category=<?= $category ?>">Create</a>
</div>
<div>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="notificationUnderMyPost"
               v-model="alertOnNewPost" @change="onChangeSubscribeOrUnsubscribe('<?=$post_topic?>', alertOnNewPost)">
        <label class="form-check-label" for="notificationUnderMyPost">Notification on New Post</label>
    </div>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="notificationUnderMyComment"
               v-model="alertOnNewComment" @change="onChangeSubscribeOrUnsubscribe('<?=$comment_topic?>', alertOnNewComment)">
        <label class="form-check-label" for="notificationUnderMyComment">Notification on New Comment</label>
    </div>
</div>
<hr>

<section class="post-list p-2">
    <?php
    $page_no = $_REQUEST['page_no'] ?? 1;
    $posts_per_page = 2;
    $offset = ($page_no - 1) * $posts_per_page;
    $q = ['category_name' => $category, 'posts_per_page' => $posts_per_page, 'offset' => $offset];
    $posts = forum_search($q);

    foreach ($posts as $post) {
        // print_r($post);
    ?>
        <a class="d-flex justify-content-between mb-2" href="<?php echo $post['url'] ?>">

            <div class="d-flex">
                <? if ( $post['profile_photo_url'] ) { ?>
                <img class="me-3 size-40 circle" src="<?= $post['profile_photo_url'] ?>">
                <? } ?>
                <h1><?php echo $post['post_title'] ?></h1>
            </div>

            <div class="meta">
                By <?php echo $post['author_name'] ?>
            </div>
        </a>
    <?php } ?>
</section>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item"><a class="page-link" href="">Previous</a></li>
        <?php for ($i = 0; $i <= 10; $i++) {
            $paged = $i + 1;
            $href = '/?page=forum/list&category=' . $category . '&page_no=' . $paged;
        ?>
            <li class="page-item"><a class="page-link" href="<?= $href ?>"><?= $paged ?></a></li>
        <?php } ?>
        <li class="page-item"><a class="page-link" href="<?= $href ?>">Next</a></li>
    </ul>
</nav>

<script>
    const category = "<?php echo $category ?>";
    const mixin = {
        created() {
            console.log('list.created!');
        },
        mounted() {
            console.log('post_notification_prefix.mounted!',config.post_notification_prefix + category, this.$data.user[config.post_notification_prefix + category]);
            console.log('post_notification_prefix.mounted!', this.$data.user[config.comment_notification_prefix + category]);
            console.log('this.$data.user', this.$data.user);
            console.log('this.$data.profile', this.$data.profile);
            this.$data.alertOnNewPost = this.$data.user[config.post_notification_prefix + category] === 'Y';
            this.$data.alertOnNewComment = this.$data.user[config.comment_notification_prefix + category] === 'Y';
        },
        data() {
            return {}
        },
        methods: {
        }
    }
</script>