function request(route, data, successCallback, errorCallback) {
    data = Object.assign({}, data, {route: route});
    if (this.loggedIn) {
        data['session_id'] = this.$data.user.session_id;
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
            post: {},
            user: null,
            // loggedIn: user.session_id,
            // notLoggedIn: !this.$data.user.loggedIn,
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
        notLoggedIn() {
            return ! this.loggedIn();
        },
        onRegisterFormSubmit() {
            console.log('register form submitted');
            console.log(this.$data.register);
            request('user.register', vm.$data.register, this.setUser, this.error);
        },
        onLoginFormSubmit() {
            request('user.login', vm.$data.login, function(profile) {
                vm.setUser(profile);
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
        saveToken(token) {
            console.log('token::\n', token);
            request('notification.updateToken', { token: token }, function (re) {
                console.log(re);
            }, this.error);
        },

        /// Forum
        onPostEditFormSubmit(category) {
            this.$data.post.category = category;
            this.$data.post.session_id = this.$data.user.session_id;
            console.log(this.$data.post);

            request('forum.editPost', vm.$data.post, function(post) {
                console.log('post created', post);
                window.location.href = "/?page=forum/list&category=" + category;
            }, this.error);
        },
    }
};
const vm = Vue.createApp(AttributeBinding).mount('#layout');
