<h1>Settings</h1>
<hr>

<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="notificationUnderMyPostAndComment"
           v-model="alertOnNewPostAndComment" @change="onChangeSubscribeOrUnsubscribe('<?=NOTIFY_COMMENT?>',alertOnNewPostAndComment)">
    <label class="form-check-label" for="notificationUnderMyPostAndComment">Receive notification under my Post and Comment.</label>
</div>
<script>
    const mixin = {
        created() {
            console.log('settings.created!');
        },
        mounted() {
            console.log('settings.mounted!');
            this.$data.alertOnNewPostAndComment = this.$data.user.notifyComment === 'Y';
        },
        data() {
            return {
                alertOnNewPostAndComment:  true,
            }
        },
        methods: {
            onChangeAlertOnNewPostAndComment() {
                const notificationRoute = this.$data.alertOnNewPostAndComment === true
                    ? "notification.subscribeTopic"
                    : "notification.unsubscribeTopic";
                request(notificationRoute, {topic: config.comment_notification_prefix}, function () {

                }, this.error);
            }
        }
    }
</script>

<style>

</style>