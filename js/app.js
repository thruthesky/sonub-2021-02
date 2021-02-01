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
        sendTo: "topic",
      },


      // progress bar of file uploads.
      //
      // Globally shared var.
      // it is shared for all kinds of file(photo) upload including profile photo, forum photo.

      uploadPercentage: 0,


      token: null,
    };
  },
  created() {
    console.log("created!");
    this.getUser();
  },
  mounted() {
    console.log("mounted!");
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
      if (typeof id === "undefined") id = "default";
      clearTimeout(_inDebounce[id]);
      _inDebounce[id] = setTimeout(function() {
        fn(id);
      }, delay);
    },
    isAdmin() {
      return this.user && this.user.admin;
    },
    loggedIn() {
      return this.user !== null && this.user.session_id;
    },
    sessionId() {
      return this.user && this.user.session_id;
    },
    notLoggedIn() {
      return !this.loggedIn();
    },
    onRegisterFormSubmit() {
      console.log("register form submitted");
      const data = Object.assign({}, this.$data.register);
      if ( this.$data.token ) {
          data['token'] = this.$data.token;
      }
      console.log(data);
      request(
        "user.register",
        data,
        function (profile) {
          app.setUser(profile);
          // todo: let the form controll to move to which page like 'home' or 'user/profile'.
          move("/");
        },
        this.error
      );
    },
    onLoginFormSubmit() {
      request(
        "user.login",
        this.$data.login,
        function (profile) {
          app.setUser(profile);
          move("/");
        },
        this.error
      );
    },
    /**
     * Loads user profile data and set it on `app.profile` in vue.
     */
    loadProfileUpdateForm() {
      request(
        "user.profile",
        {},
        function (profile) {
          console.log("loadProfileUpdateForm: ", profile);
          app.profile = profile;
        },
        this.error
      );
    },
    /**
     * This updates user profile to backend.
     *
     * @logic
     *  - It saves input `data` into backend.
     *    - If there is erorr, it handles error.
     *    - If there is no error, it updates the localStorage with new data.
     * @param data
     */
    userProfileUpdate(data, onSuccessCallback) {
      request(
          "user.profileUpdate",
          data,
          function (profile) {
            console.log("userProfileUpdate success. saving: ", profile);
            app.setUser(profile);
            if ( typeof onSuccessCallback === 'function' ) onSuccessCallback(profile);
          },
          this.error
      );
    },
    onProfileUpdateFormSubmit() {
      console.log(this.$data.profile);
      this.userProfileUpdate(this.$data.profile);
      // request(
      //   "user.profileUpdate",
      //   this.$data.profile,
      //   function (profile) {
      //     console.log("profile saved: ", profile);
      //     app.setUser(profile);
      //     app.alert("saved");
      //   },
      //   this.error
      // );
    },
    // onProfileMetaUpdateSubmit(data) {
    //   request('user.profileUpdate', data, this.setUser, this.error);
    // },
    /**
     * It's a wrapper of calling global fileUpload function.
     * @param event
     * @param successCallback
     */
    onFileUpload(event, successCallback) {
      if (event.target.files.length === 0) {
        console.log("User cancelled upload");
        return;
      }
      const file = event.target.files[0];
      app.uploadPercentage = 0;
      const options = {
        onUploadProgress: function (progressEvent) {
          app.uploadPercentage = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );
        },
      };
      // Upload photo
      fileUpload(
        file,
        options,
        function (res) {
          console.log("success: res.url: ", res.url);
          app.uploadPercentage = 0;
          successCallback(res);
        },
        this.error
      );
    },
    /**
     *
     * @param event
     */
    onProfilePhotoUpload(event) {
      this.onFileUpload(event, function (res) {
        // Update user profile photo url
        request(
          "user.profileUpdate",
          { profile_photo_url: res.url },
          function (profile) {
            console.log("new profile: ", profile);
            app.profile.profile_photo_url = profile.profile_photo_url;
            app.user.profile_photo_url = profile.profile_photo_url;
            app.setUser(profile);
          },
          this.error
        );
      });
    },

    /**
     * Delete file.
     * 
     * @param {string} ID
     * @param {function} successCallback
     */
    onFileDelete(ID, successCallback) {
      const conf = confirm("Delete File?");
      if (conf === false) {
        return;
      }
      request("file.delete", { ID: ID }, successCallback, this.error);
    },

    logout() {
      Cookies.remove('session_id', {domain: config.cookie_domain});
      Cookies.remove('nickname', {domain: config.cookie_domain});
      Cookies.remove('profile_photo_url', {domain: config.cookie_domain});
      this.user = null;
    },
    error(e) {
      console.log("error(e)", e);
      alert(e);
    },
    /**
     * Set user profile on browser cookie which can be used by PHP.
     * @note when user upload photo, the url is saved in cookie but it will be available in php on next page load.
     * @param profile
     */
    setUser(profile) {
      console.log(profile);
      Cookies.set('session_id', profile.session_id, {domain: config.cookie_domain});
      Cookies.set('nickname', profile.nickname, {domain: config.cookie_domain});
      Cookies.set('profile_photo_url', profile.profile_photo_url, {domain: config.cookie_domain});
      Cookies.set('admin', profile.admin, {domain: config.cookie_domain});

      this.user = {
        'session_id': profile.session_id,
        'nickname': profile.nickname,
        'profile_photo_url': profile.profile_photo_url,
        'admin':profile.admin
      };
      this.user = profile;
    },

    /**
     * Get user information.
     */
    getUser() {
      const id = Cookies.get('session_id');
      if ( id ) {
        this.user = {
          'session_id': id,
          'nickname': Cookies.get('nickname'),
          'profile_photo_url': Cookies.get('profile_photo_url'),
          'admin': Cookies.get('admin'),
        };
      }
    },
    /**
     * alert
     * @param title
     * @param body
     */
    alert(title, body = '') {
      alert(title + "\n" + body);
    },
    saveToken(token, topic = "") {
      request(
        "notification.updateToken",
        { token: token, topic: topic },
        function (re) {
          // console.log(re);
        },
        this.error
      );
    },
    onChangeSubscribeOrUnsubscribeTopic(topic, subscribe) {
      if (this.notLoggedIn()) {
        subscribe.target.checked = false;
        return this.alert('Must Login first');
      }

      request('notification.topicSubscription',
          {topic: topic, subscribe: subscribe.target.checked ? 'Y' : 'N' },
          function (res) {
            // this.$data.user[topic] = mode ? "Y" : "N";
          }, this.error);
    },
  },
};

const _app = Vue.createApp(AttributeBinding);
if (typeof forumMixin !== "undefined") _app.mixin(forumMixin);
if (typeof mixin !== "undefined") {
  _app.mixin(mixin);
}
const app = _app.mount("#app");
