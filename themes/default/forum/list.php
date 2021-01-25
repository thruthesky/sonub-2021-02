<?php
$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : 'qna';
?>
<hr>
<div class="p-2 d-flex justify-content-between">
    <h2>Forum List</h2>
    <a class="btn btn-success" href="/?page=forum/edit&category=<?php echo $category ?>">Create</a>
</div>
<div>
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="notificationUnderMyPost" v-model="alertOnNewPost" @change="onChangeAlertOnNewPost">
        <label class="custom-control-label" for="notificationUnderMyPost">Notification on New Post</label>
    </div>
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="notificationUnderMyComment" v-model="alertOnNewPostAndComment" @change="onChangeAlertOnNewComment">
        <label class="custom-control-label" for="notificationUnderMyComment">Notification on New Post and Comment</label>
    </div>
</div>
<hr>

<section class="post-list p-2">
    <?php
    $posts = forum_search(['category_name' => $category, 'posts_per_page' => 20]);

    foreach ($posts as $post) {
        // print_r($post);
    ?>
        <div class="p-2 m-2 card border border-dark">
            <a class="d-block mb-2" href="<?php echo $post['url'] ?>">
                <?php echo $post['post_title'] ?>
                By <?php echo $post['author_name'] ?>
            </a>
        </div>
    <?php } ?>
</section>


<script>
    const category = "<?php echo $category ?>";
    const mixin = {
        created() {
            console.log('settings.created!');
        },
        mounted() {
            console.log('settings.mounted!');
            this.$data.alertOnNewPost = this.$data.user['notify_post_' + category] === 'Y';
            this.$data.alertOnNewPostAndComment = this.$data.user['notify_post_and_comment_' + category] === 'Y';
        },
        data() {
            return {
                alertOnNewPost: false,
                alertOnNewPostAndComment:  false,
            }
        },
        methods: {
            onChangeAlertOnNewPost() {
                const data = {};
                data["notify_post_" + category] = this.$data.alertOnNewPost ? "Y" : "N"
                console.log(data);
                this.onProfileUpdateSubmit(data);
            },
            onChangeAlertOnNewComment() {
                const data = {};
                data["notify_post_and_comment_" + category] =  this.$data.alertOnNewPostAndComment ? "Y" : "N";
                console.log('this.$data.alertOnNewPost', this.$data.alertOnNewPost);
                if ( this.$data.alertOnNewPost === false &&  this.$data.alertOnNewPostAndComment === "Y") {
                    data["notify_post_" + category] =  "Y";
                }
                console.log(data);
                this.onProfileUpdateSubmit(data);
            }
        }
    }
</script>