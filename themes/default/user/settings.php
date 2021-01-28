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
                const notificationRoute = this.$data.alertOnNewPost === true
                    ? "notification.subscribeTopic"
                    : "notification.unsubscribeTopic";
                request(notificationRoute, {topic: config.post_notification_prefix}, function () {
                    app.onProfileMetaUpdateSubmit({
                        [config.post_notification_prefix]: app.$data.alertOnNewPost ? "Y" : "N"
                    });
                }, this.error);
            },
            onChangeAlertOnNewComment() {
                const notificationRoute = this.$data.alertOnNewComment === true
                    ? "notification.subscribeTopic"
                    : "notification.unsubscribeTopic";
                request(notificationRoute, {topic: config.post_notification_prefix}, function () {
                    app.onProfileMetaUpdateSubmit({
                        [config.post_notification_prefix]: app.$data.alertOnNewComment ? "Y" : "N"
                    });
                }, this.error);
            }
        }
    }
</script>

<style>

</style>