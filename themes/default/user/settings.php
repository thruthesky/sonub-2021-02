<?php
?>
<h1>Settings</h1>
<hr>

<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="notificationUnderMyPostAndComment"
           @change="onChangeSubscribeOrUnsubscribeTopic('<?=NOTIFY_COMMENT?>',$event)"
           <? echo ( isSubscribedToTopic(NOTIFY_COMMENT) ? 'checked' : '');?>
    >
    <label class="form-check-label" for="notificationUnderMyPostAndComment">Receive notification under my Post and Comment.</label>
</div>
<script>
    const mixin = {
        created() {
            console.log('settings.created!');
        },
        mounted() {
            console.log('settings.mounted!');
        },
        data() {
            return {}
        },
        methods: {
        }
    }
</script>

<style>

</style>