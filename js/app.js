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
            token: null
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
            const data = Object.assign({}, this.$data.register);
            if ( this.$data.token ) {
                data['token'] = this.$data.token;
            }
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
        onProfileMetaUpdateSubmit(data) {
            request('user.profileUpdate', data, this.setUser, this.error);
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
         *
         * @param event
         */
        onProfilePhotoUpload(event) {
            if ( event.target.files.length === 0 ) {
                console.log('User cancelled upload');
                return;
            }
            const file = event.target.files[0];

            const options = {
                onUploadProgress: function(progressEvent) {
                    var percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                    console.log('percentCompleted:', percentCompleted);
                }
            };
            // Upload photo
            fileUpload(file, options, function(res) {
                console.log('success: res.url: ', res.url);
                // Update user profile photo url
                request('user.profileUpdate', {'profile_photo_url': res.url}, function(profile) {
                    console.log('new profile: ', profile);
                    app.profile.profile_photo_url = profile.profile_photo_url;
                    app.user.profile_photo_url = profile.profile_photo_url;
                    app.setUser(profile);
                }, this.error);
            }, this.error);
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
            this.$data.token = token;
            request('notification.updateToken', { token: token, topic: topic }, function (re) {
                // console.log(re);
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


