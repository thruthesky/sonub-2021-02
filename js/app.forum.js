/**
 * @file app.forum.js
 * @description forum related Vue.js 3 javascript. It can only be included on forum pages.
 * @type {{data(): {commentEditForm: {}, replyNo: number, editNo: number}, methods: {onPostEditFormSubmit(event): void, commentEditFormCanSubmit(): forumMixin.methods.$data.commentEditForm.comment_content, onCommentEditFormSubmit(event): void, onFileUpload(), toggleCommentReplyDisplay(string, string): void, getFormData(event): {}, onPostDelete((string|number), string): (undefined), onCommentDelete((string|number)): (undefined), toggleCommentEditDisplay(string, string): void}}}
 */

const forumMixin = {
  data() {
    return {
      replyNo: 0,
      editNo: 0,

      /**
       * `files` is used for displaying uploaded files and keeping them.
       * To display, uploaded files, do `app.files = [ ... files ... ]`;
       * To save files to post, get the ID(s) from app.files and pass it over route.
       */
      files: [],
      currentListCategory: '',
      alertOnNewPost: false,
      alertOnNewComment: false,
    };
  },
  methods: {


    commentEditFormCanSubmit() {
      return this.$data.commentEditForm.comment_content;
    },
    /**
     * Create or update post.
     *
     * Note that, post create(or update) does not use v-model. So, it needs to get data from the from.
     * But, it uses `files` data binding to display and keep the files that are uploaded.
     *
     * When a file is uploaded, the file goes in `files` array through `onPostEditUploadSuccess`
     *
     * @param {event} event
     */
    onPostEditFormSubmit(event) {
      const data = serializeFormEvent(event);
      data.files = [];
      this.files.forEach(function(v) {
        data.files.push(v.ID);
      });
      request('forum.editPost', data, function(post) {
        console.log('post edit', post);
        move(post['url']);
      }, this.error);
    },
    onPostEditUploadSuccess(res) {
      this.files.push(res);
    },


    /**
     * Request call for deleting post.
     *
     * @param {string|number} ID
     * @param {string} category
     */
    onPostDelete(ID, category) {
      const conf = confirm('Delete Post?');
      if (conf === false) return;
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
      const data = serializeFormEvent(event);
      console.log('Comment Edit Form Data', data);
      request('forum.editComment', data, function(comment) {
        console.log('comment edit', comment);
        refresh();
      }, this.error);
    },
    /**
     * Request call for deleting comment.
     *
     * @param {string|number} ID
     */
    onCommentDelete(ID) {
      const conf = confirm('Delete Comment?');
      if (conf === false) return;
      request('forum.deleteComment', { comment_ID: ID }, function(post) {
        console.log('comment delete', post);
        refresh();
      }, this.error);
    },
    onChangeAlertOnNewPost(category) {
        const topic = config.post_notification_prefix + category;
        const notificationRoute = this.$data.alertOnNewPost === true
            ? "notification.subscribeTopic"
            : "notification.unsubscribeTopic";
        request(notificationRoute, {topic: topic}, function (res) {
            console.log(res);
        }, this.error);
    },
    onChangeAlertOnNewComment(category) {
        const topic = config.comment_notification_prefix + category;
        const notificationRoute = this.$data.alertOnNewComment === true
            ? "notification.subscribeTopic"
            : "notification.unsubscribeTopic";
        request(notificationRoute, {topic: topic}, function (res) {
            console.log(res);
        }, this.error);
    }
  },
};



const commentForm = {
  props: ['comment_id', 'comment_parent', 'comment_post_id'],
  template: '' +

      '<form @submit.prevent="onSubmit">' +
      ' <div class="d-flex bg-light">' +
      '   <div class="position-relative d-inline-block of-hidden">' +
      '     <i class="fa fa-camera fs-xl"></i>' +
      '     <input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onCommentFileUpload($event)">' +
      '   </div>' +
      '   <textarea class="w-100" v-model="form.comment_content"></textarea>' +
      ' </div>' +

      ' <button class="btn btn-secondary ml-2" type="button" @click="onCancel()" v-if="canCancel()">Cancel</button>' +
      ' <button class="btn btn-success ml-2" type="submit" v-if="canSubmit()">Submit</button>' +
      '</form>' +

      '<div>' +
      ' <div class="progress mt-3" style="height: 5px;" v-if="$root.uploadPercentage > 0">' +
      '   <div class="progress-bar" role="progressbar" :style="{width: $root.uploadPercentage + \'%\'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>' +
      ' </div>' +
      ' <div class="uploaded-files d-flex">' +
      '   <div class="position-relative p-1" v-for="file in uploaded_files">' +
      '     <img class="size-100" :src="file.url">' +
      '     <i class="fa fa-trash fs-lg position-absolute top left"></i>' +
      '   </div>' +
      ' </div>' +
      ' {{ uploaded_files }}' +
      '</div>',
  data() {
    return {
      form: {
        comment_ID: this.comment_id,
        comment_parent: this.comment_parent,
        comment_post_ID: this.comment_post_id,
        comment_content: '',
        files: [],
      },
      uploaded_files: [],
    };
  },
  created() {
    /**
     * For comment edit, Vue will strip out all the special chars of 'comment_content' like quotes comment from from PHP
     * So, it loads content from backend and fills in textarea.
     */
    if ( this.form.comment_ID ) {
      const $this = this;
      request('forum.getComment', {comment_ID: this.form.comment_ID}, function (cmt) {
        console.log(cmt);
        $this.form.comment_content = cmt.comment_content;
        $this.uploaded_files = cmt.files;
      }, this.error);
    }
  },
  methods: {
    canCancel() {
      return !!this.$data.form.comment_ID || !!this.$data.form.comment_parent || this.canSubmit();
    },
    canSubmit() {
      return !!this.$data.form.comment_content || this.$data.form.files.length > 0;
    },
    onCancel() {
      this.$root.replyNo = 0;
      this.$root.editNo = 0;
      this.$data.form.comment_content = '';
      this.$data.form.files = [];
      this.$data.uploaded_files = [];
    },
    onSubmit() {
      // Get file ID(s) of the comment.
      const $this = this;
      $this.uploaded_files.forEach(function(f) {
        $this.form.files.push( f.ID );
      });
      // Save.
      request('forum.editComment', this.$data.form, refresh, app.error);
    },
    onCommentFileUpload(event) {
      const $this = this;
      this.$root.onFileUpload(event, function(res) {
        $this.$data.uploaded_files.push(res);
      });
    },
  },
};
addComponent('comment-form', commentForm);



