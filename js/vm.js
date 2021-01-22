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
        /// Add or Update Post.
        onPostEditFormSubmit(event) {
            const formData = new FormData(event.target); // reference to form element
            const data = {}; // need to convert it before using not with XMLHttpRequest
            data.session_id = this.$data.user.session_id; // add session id
            for (let [key, val] of formData.entries()) {
              Object.assign(data, { [key]: val })
            }
            console.log('Post Edit Form Data', data);
            request('forum.editPost', data, function(post) {
                console.log('post updated', post);
                window.location.replace(post['guid']);
            }, this.error);
        },
        /// Delete Post.
        onPostDelete(ID, category) {
            const conf = confirm('Delete Post?');
            if (conf == false) return; 

            const data = {
                session_id: this.$data.user.session_id,
                ID: ID,
            };

            request('forum.deletePost', data, function(post) {
                console.log('post delete', post);
                window.location.href = "/?page=forum/list&category=" + category;
            }, this.error);
        }
    }
};
const vm = Vue.createApp(AttributeBinding).mount('#layout');
