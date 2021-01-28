var _inDebounce = {};

const AttributeBinding = {
    components: getComponents(),
    data() {
        return {
            // user register form data object.
            register: {},
            // user login form data object.
            login: {},
            // user profile update form data object.
            profile: {},

            // User information loaded from localStorage.
            user: null,
            pushNotification: {
                sendTo: 'topic'
            },

            // progress bar of file uploads. it is shared for all kinds of file(photo) upload including profile photo, forum photo.
            uploadPercentage: 0,
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
            request('user.register', this.$data.register, function(profile) {
                app.setUser(profile);
                // todo: let the form controll to move to which page like 'home' or 'user/profile'.
                move('/');
            }, this.error);
        },
        onLoginFormSubmit() {
            request('user.login', this.$data.login, function(profile) {
                app.setUser(profile);
                move('/');
            }, this.error);
        },
        loadProfileUpdateForm() {
            request('user.profile', {}, function(profile) {
                console.log('loadProfileUpdateForm: ', profile);
                app.$data.profile = profile;
            }, this.error);
        },
        onProfileUpdateFormSubmit() {
            console.log(this.$data.profile);
            request('user.profileUpdate', this.$data.profile, function(profile) {
                console.log('profile saved: ', profile);
                app.setUser(profile);
                app.alert('saved');
            }, this.error);
        },

        /**
         * It's a wrapper of calling global fileUpload function.
         * @param event
         * @param successCallback
         */
        onFileUpload(event, successCallback) {

            if ( event.target.files.length === 0 ) {
                console.log('User cancelled upload');
                return;
            }
            const file = event.target.files[0];
            app.uploadPercentage = 0;
            const options = {
                onUploadProgress: function(progressEvent) {
                    app.uploadPercentage = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                }
            };


            // Upload photo
            fileUpload(file, options, function(res) {
                console.log('success: res.url: ', res.url);
                successCallback(res);
            }, this.error);
        },

        /**
         *
         * @param event
         */
        onProfilePhotoUpload(event) {
            this.onFileUpload(event, function(res) {
                // Update user profile photo url
                request('user.profileUpdate', {'profile_photo_url': res.url}, function(profile) {
                    console.log('new profile: ', profile);
                    app.profile.profile_photo_url = profile.profile_photo_url;
                    app.user.profile_photo_url = profile.profile_photo_url;
                    app.setUser(profile);
                    app.uploadPercentage = 0;
                }, this.error);
            });
        },
        /**
         * Forum file upload.
         * @param event
         */
        onForumFileUpload(event){
            this.onFileUpload(event, function(res) {
                // Update user profile photo url
                // request('user.profileUpdate', {'profile_photo_url': res.url}, function(profile) {
                //     console.log('new profile: ', profile);
                //     app.profile.profile_photo_url = profile.profile_photo_url;
                //     app.user.profile_photo_url = profile.profile_photo_url;
                //     app.setUser(profile);
                //     app.uploadPercentage = 0;
                // }, this.error);
            });
        },
        logout() {
            localStorage.removeItem('user');
            this.$data.user = null;
        },
        error(e) {
            console.log('error(e)', e);
            alert(e);
        },
        setUser(profile) {
            setLocalStorage('user', profile);
            this.$data.user = profile;
        },
        getUser() {
            this.$data.user = getLocalStorage('user');
            return this.$data.user;
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
if ( typeof forumMixin !== 'undefined' ) _app.mixin(forumMixin);
if ( typeof mixin !== 'undefined' ) {
    _app.mixin(mixin);
}
const app = _app.mount('#app');

