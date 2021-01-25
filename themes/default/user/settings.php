<h1>Settings</h1>
<hr>
<!--    {{ user }}-->

<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="notificationUnderMyPost" v-model="alertOnNewPost" @change="onChangeAlertOnNewPost">
    <label class="custom-control-label" for="notificationUnderMyPost">Receive notification under my post.</label>
</div>
<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="notificationUnderMyComment" v-model="alertOnNewComment" @change="onChangeAlertOnNewComment">
    <label class="custom-control-label" for="notificationUnderMyComment">Receive notification under my comment.</label>
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
                    'notify_post': this.$data.alertOnNewPost ? "Y" : "N"
                })
            },
            onChangeAlertOnNewComment() {
                console.log(this.$data.alertOnNewComment);
                this.onProfileUpdateSubmit({
                    'notify_comment': this.$data.alertOnNewComment ? "Y" : "N"
                })
            }
        }
    }
</script>

<style>

</style>