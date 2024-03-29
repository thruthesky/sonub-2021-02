<?php

$category = '';
$post = null;

if (isset($_REQUEST['category'])) {
    $category = $_REQUEST['category'];
} else if(isset($_REQUEST['ID'])){
    $post = get_post($_REQUEST['ID'], ARRAY_A);
    $category = get_the_category($post['ID'])[0]->slug;
}

//print_r($post);

?>

<h1>Admin send push notification</h1>


<form @submit.prevent="sendPushNotification">
    <div class="form-group">

        Send a message to
        <select class="form-control mb-2 col-12 col-sm-6 col-md-3" v-model="pushNotification.targetType" @change="onChangeSelect()">
            <option value="">All users</option>
            <option value="topic">Topic</option>
            <option value="tokens">Tokens</option>
            <option value="users">Users</option>
        </select>
        <div v-if="pushNotification.targetType">
            <input type="text" class="form-control" id="sendTo" name="sendTo" :placeholder="pushNotification.targetType" v-model="pushNotification.target">
            <small class="d-block form-text text-muted" @click="pushNotification.targetType = ''">To send message to all users, Choose 'all users'. Or click here.</small>
            <small id="sendTo" class="d-block form-text text-muted">Token and User ID must be separated by comma.</small>
        </div>
    </div>

    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="title" v-model="pushNotification.title">
    </div>

    <div class="form-group">
        <label for="body">Body</label>
        <input type="text" class="form-control" id="body" name="body" aria-describedby="body" v-model="pushNotification.body">
    </div>
    <div class="form-group">
        <label for="click_action">Click Action</label>
        <input type="text" class="form-control" id="click_action" name="click_action" aria-describedby="click_action" v-model="pushNotification.click_action">
    </div>
    <button type="submit" class="btn btn-primary mt-3 px-5" type="submit">Send</button>
</form>



<script>
    const ID = "<?=$post['ID'] ?? '';?>";
    const title = "<?=$post['post_title'] ?? '';?>";
    const body = "<?=$post['post_content'] ?? '';?>";
    const click_action = "<?=$post['guid'] ?? ''?>";
    const topic = "<?=$category ?? ''?>";

    const mixin = {
        created() {
            console.log('send push notification.created!');
        },
        mounted() {
            console.log('send push notification.mounted!');
            this.$data.pushNotification = {
                targetType: '',
                target: config.defaultTopic,
                title: title,
                body: body,
                click_action: click_action ?? "/"
            }
        },
        data() {
            return {
                pushNotification: {
                    targetType: '',
                    target: config.defaultTopic,
                    title: '',
                    body: '',
                    click_action: "/"
                },
            }
        },
        methods: {
            sendPushNotification() {
                // console.log(this.$data.pushNotification.title);
                // if (this.$data.pushNotification.title === void 0 && this.$data.pushNotification.title === void 0) return alert('Title or Body is missing');
                console.log("sendPushNotification::", this.$data.pushNotification);
                let route = '';
                const data = {
                    title: this.$data.pushNotification.title,
                    body: this.$data.pushNotification.body,
                    click_action: this.$data.pushNotification.click_action
                };
                if ( this.$data.pushNotification.targetType === '') {
                    route = 'notification.sendMessageToTopic';
                    data['topic'] = config.defaultTopic;
                }
                else if (this.$data.pushNotification.targetType === 'topic' ) {
                    route = 'notification.sendMessageToTopic';
                    data['topic'] = this.$data.pushNotification.target;
                } else if (this.$data.pushNotification.targetType === 'tokens' ) {
                    route = 'notification.sendMessageToTokens';
                    data['tokens'] = this.$data.pushNotification.target;
                } else if (this.$data.pushNotification.targetType === 'users' ) {
                    route = 'notification.sendMessageToUsers';
                    data['users'] = this.$data.pushNotification.target;
                }
                request(route, data, function(res) {
                    console.log(res);
                    alert('Send Push Success');
                }, this.error);
            },
            onChangeSelect() {
                if (this.$data.pushNotification.targetType === config.defaultTopic) {
                    this.$data.pushNotification.target = config.defaultTopic;
                } else {
                    this.$data.pushNotification.target = "";
                }
            }
        }
    }
</script>

