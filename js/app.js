function request(route, data, successCallback, errorCallback) {
    data = Object.assign({}, data, {route: route});

    if (app.loggedIn()) {
        data['session_id'] = app.sessionId();
    }
    console.log('data', data);
    axios.post(config.apiUrl, data).then(function (res) {
        if ( res.data.code !== 0 ) {
            if ( typeof errorCallback === 'function' ) {
                errorCallback(res.data.code);
            }
        } else {
            successCallback(res.data.data);
        }
    }).catch(errorCallback);
}

function move(uri) {
    location.href = uri;
}

const AttributeBinding = {
    data() {
        return {
            register: {},
            login: {},
            user: null,
            // loggedIn: user.session_id,
            // notLoggedIn: !this.$data.user.loggedIn,
            pushNotification: {
                sendTo: 'topic'
            },
            modal: {
                active: false,
                eventName: '',
                title: '',
                content: '',
            }
        }
    },
    created() {
        console.log('created!');
        this.getUser();
    },
    mounted() {
        console.log('mounted!');
    },
    methods: {
        isAdmin() {
            return this.$data.user && this.$data.user.admin;
        },
        loggedIn() {
            return this.$data.user !== null && this.$data.user.session_id;
        },
        sessionId() {
            return this.$data.user.session_id;
        },
        notLoggedIn() {
            return ! this.loggedIn();
        },
        onRegisterFormSubmit() {
            console.log('register form submitted');
            console.log(this.$data.register);
            request('user.register', this.$data.register, this.setUser, this.error);
        },
        onLoginFormSubmit() {
            request('user.login', this.$data.login, function(profile) {
                app.setUser(profile);
                move('/');
            }, this.error);
        },
        logout() {
            localStorage.removeItem('user');
            this.$data.user = null;
        },
        error(e) {
            console.log('e');
            alert(e);
        },
        setUser(profile) {
            this.set('user', profile);
            this.$data.user = profile;
        },
        getUser() {
            this.$data.user = this.get('user');
            return this.$data.user;
        },
        set(name, value) {
            value = JSON.stringify(value);
            localStorage.setItem(name, value);
        },
        get(name) {
            const val = localStorage.getItem(name);
            if ( val ) {
                return JSON.parse(val);
            } else {
                return val;
            }
        },
        alert(title, body) {
            alert(title + "\n" + body);
        },
        saveToken(token, topic = '') {
            request('notification.updateToken', { token: token, topic: topic }, function (re) {
                // console.log(re);
            }, this.error);
        },
        /**
         * Transform form event data to an object.
         * 
         * @param {event} event 
         */
        getFormData(event) {
            const formData = new FormData(event.target); // reference to form element
            const data = {}; // need to convert it before using not with XMLHttpRequest
            for (let [key, val] of formData.entries()) {
              Object.assign(data, { [key]: val })
            }
            return data;
        },
        /**
         * Request call for editting(creating / updating) post.
         * 
         * @param {event} event 
         */
        onPostEditFormSubmit(event) {
            const data = this.getFormData(event);
            console.log('Post Edit Form Data', data);
            request('forum.editPost', data, function(post) {
                console.log('post edit', post);
                window.location.replace(post['guid']);
            }, this.error);
        },
        /**
         * Request call for deleting post.
         * 
         * @param {string|number} ID 
         * @param {string} category 
         */
        onPostDelete(ID, category) {
            const conf = confirm('Delete Post?');
            if (conf == false) return; 
            request('forum.deletePost', { ID: ID }, function(post) {
                console.log('post delete', post);
                move("/?page=forum/list&category=" + category);
            }, this.error);
        },
        /**
         * Request call for editting(creating / updating) comment.
         * 
         * @param {event} event 
         */
        onCommentEditFormSubmit(event) {
            const data = this.getFormData(event);
            console.log('Post Edit Form Data', data);
            request('forum.editComment', data, function(comment) {
                console.log('comment edit', comment);
                /// TODO: insert to post comments
            }, this.error);
        },
        /**
         * Request call for deleting comment.
         * 
         * @param {string|number} ID
         */
        onCommentDelete(ID) {
            const conf = confirm('Delete Comment?');
            if (conf == false) return; 
            request('forum.deleteComment', { comment_ID: ID }, function(post) {
                console.log('comment delete', post);
                var el = document.getElementById("comment_" + ID);
                el.remove();
            }, this.error);
        },
        sendPushNotification() {
            // console.log(this.$data.pushNotification.title);
            // if (this.$data.pushNotification.title === void 0 && this.$data.pushNotification.title === void 0) return alert('Title or Body is missing');
            console.log("sendPushNotification::", this.$data.pushNotification);

            let route = '';
            const data = {
                title: this.$data.pushNotification.title,
                body: this.$data.pushNotification.body
            };
            if (this.$data.pushNotification.sendTo === 'topic' ) {
                route = 'notification.sendMessageToTopic';
                data['topic'] = this.$data.pushNotification.receiverInfo;
            } else if (this.$data.pushNotification.sendTo === 'tokens' ) {
                route = 'notification.sendMessageToTokens';
                data['tokens'] = this.$data.pushNotification.receiverInfo;
            } else if (this.$data.pushNotification.sendTo === 'users' ) {
                route = 'notification.sendMessageToUsers';
                data['users'] = this.$data.pushNotification.receiverInfo;
            }
            request(route, data, function(res) {
                // console.log(res);
            }, this.error);
        },
        showModal(eventName) {
            this.$data.modal.active = true;
            this.$data.modal.eventName = eventName;
        },
        hideModal(trigger = false) {
            this.$data.modal.active = false;
            if (trigger) {
                this.$emit(this.$data.modal.eventName)
                console.log(this.$data.modal.eventName);
            }
        },
    }
};
const app = Vue.createApp(AttributeBinding).mount('#layout');


