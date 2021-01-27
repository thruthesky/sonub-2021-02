


var _inDebounce = {};

const AttributeBinding = {
    data() {
        return {
            // @todo name it registerForm,
            register: {},
            // @todo change it to loginForm,
            login: {},
            user: null,
            pushNotification: {
                sendTo: 'topic'
            },
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
        /**
         * Debouncing functions
         *
         * if 'id' is not set, then 'default' id will be used and only one(1) debounce can run at once.
         * if you want to run many debounce at once, use 'id'.
         *
         * @usage use case of 'id' would be that, when a user is editing title and description on the same page,
         *  only one of title or description may be edited if it does not use 'id'.
         *
         * @param fn
         * @param delay
         * @param id
         *
         * @example
         *  <input name="name" value="<?=$cat->name?>" @keyup="debounce(updateName, 3000, 'name')">
         *  <input type="text" @keyup="debounce(updateNoPosts, 3000, 'no')">
         */
        debounce(fn, delay, id) {
            if ( typeof id === 'undefined' ) id = 'default';
            clearTimeout(_inDebounce[id]);
            _inDebounce[id] = setTimeout(fn, delay);
        },
        isAdmin() {
            return this.$data.user && this.$data.user.admin;
        },
        loggedIn() {
            return this.$data.user !== null && this.$data.user.session_id;
        },
        sessionId() {
            return this.$data.user && this.$data.user.session_id;
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
            console.log('alert(e)', e);
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
    }
};
const _app = Vue.createApp(AttributeBinding);
_app.mixin(forumMixin);
if ( typeof mixin !== 'undefined' ) {
    _app.mixin(mixin);
}
const app = _app.mount('#app');

