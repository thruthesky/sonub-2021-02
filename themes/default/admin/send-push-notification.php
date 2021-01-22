<?php

?>
<h1>Admin send push notification</h1>

<form @submit.prevent="sendPushNotification">

    <div>
        <select v-model="pushNotification.sendTo">
            <option v-for="(v,k) in pushNotificationSendingOptions" :value="k">{{v}}</option>
            <option value="topic">Topic</option>
            <option value="tokens">Tokens</option>
            <option value="users">Users</option>
        </select>
        <input type="text" name="sendTo" v-model="pushNotification.receiverInfo">
    </div>
    <div>
        <label>Title</label>
        <input type="text" name="title" v-model="pushNotification.title">
    </div>
    <div>
        <label>Body</label>
        <input type="text" name="body" v-model="pushNotification.body">
    </div>
    <button type="submit">Send</button>
</form>

