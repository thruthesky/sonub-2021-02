<?php
$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : 'qna';
?>
<hr>
<div class="p-2 d-flex justify-content-between">
    <h2>Forum List</h2>
    <a class="btn btn-success" href="/?page=forum/edit&category=<?php echo $category ?>">Create</a>
</div>
<div>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="notificationUnderMyPost"  v-model="alertOnNewPost" @change="onChangeAlertOnNewPost">
        <label class="form-check-label" for="notificationUnderMyPost">Notification on New Post</label>
    </div>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="notificationUnderMyComment" v-model="alertOnNewComment" @change="onChangeAlertOnNewComment">
        <label class="form-check-label" for="notificationUnderMyComment">Notification on New Comment</label>
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
            console.log('list.created!');
        },
        mounted() {
            console.log('list.mounted!');
            this.$data.alertOnNewPost = this.$data.user[config.post_notification_prefix + category] === 'Y';
            this.$data.alertOnNewComment = this.$data.user[config.comment_notification_prefix + category] === 'Y';
        },
        data() {
            return {
                alertOnNewPost: false,
                alertOnNewComment:  false,
            }
        },
        methods: {
            onChangeAlertOnNewPost() {
                const data = {
                    [config.post_notification_prefix + category]:this.$data.alertOnNewPost ? "Y" : "N"
                };
                this.onProfileUpdateSubmit(data);
            },
            onChangeAlertOnNewComment() {
                const data = {
                    [config.comment_notification_prefix + category]: this.$data.alertOnNewComment ? "Y" : "N"
                };
//                data[comment_notification_prefix + category] =  this.$data.alertOnNewComment ? "Y" : "N";

//                if ( this.$data.alertOnNewPost === false &&  this.$data.alertOnNewComment === true) {
//                    data[post_notification_prefix + category] =  "Y";
//                }
                this.onProfileUpdateSubmit(data);
            }
        }
    }
</script>