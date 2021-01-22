<?php

?>
<h1>Admin send push notification</h1>
<form @submit.prevent="sendPushNotification">
    <div class="form-group">
        <select class="form-control mb-2 col-12 col-sm-6 col-md-3" v-model="pushNotification.sendTo">
            <option v-for="(v,k) in pushNotificationSendingOptions" :value="k">{{v}}</option>
            <option value="topic">Topic</option>
            <option value="tokens">Tokens</option>
            <option value="users">Users</option>
        </select>
        <input type="text" class="form-control" id="sendTo" name="sendTo" :placeholder="pushNotification.sendTo" v-model="pushNotification.receiverInfo">
    </div>
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="title" v-model="pushNotification.title">
    </div>
    <div class="form-group">
        <label for="body">Body</label>
        <input type="text" class="form-control" id="body" name="body" aria-describedby="body" v-model="pushNotification.body">
    </div>
    <button type="submit" class="btn btn-primary px-5" type="submit">Send</button>
</form>

