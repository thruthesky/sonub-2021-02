<h1>Settings</h1>
<hr>
<!--    {{ user }}-->

<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="notificationUnderMyPost" v-model="alertOnNewPost" @change="onChangeAlertOnNewPost">
    <label class="form-check-label" for="notificationUnderMyPost">Receive notification under my post.</label>
</div>
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="notificationUnderMyComment" v-model="alertOnNewComment" @change="onChangeAlertOnNewComment">
    <label class="form-check-label" for="notificationUnderMyComment">Receive notification under my comment.</label>
</div>
<script>
    const mixin = {
        created() {
            console.log('settings.created!');
        },
        mounted() {
            console.log('settings.mounted!');
            this.$data.alertOnNewPost = this.$data.user.notifyPost === 'Y';
            this.$data.alertOnNewComment = this.$data.user.notifyComment === 'Y';
        },
        data() {
            return {
                alertOnNewPost: true,
                alertOnNewComment:  true,
            }
        },
        methods: {
            onChangeAlertOnNewPost() {
                console.log(this.$data.alertOnNewPost);
                this.onProfileUpdateSubmit({
                    [config.post_notification_prefix]: this.$data.alertOnNewPost ? "Y" : "N"
                })
            },
            onChangeAlertOnNewComment() {
                console.log(this.$data.alertOnNewComment);
                this.onProfileUpdateSubmit({
                    [comment_notification_prefix]: this.$data.alertOnNewComment ? "Y" : "N"
                })
            }
        }
    }
</script>

<style>

</style>