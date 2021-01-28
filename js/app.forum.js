/**
 * @file app.forum.js
 * @description forum related Vue.js 3 javascript. It can only be included on forum pages.
 * @type {{data(): {commentEditForm: {}, replyNo: number, editNo: number}, methods: {onPostEditFormSubmit(event): void, commentEditFormCanSubmit(): forumMixin.methods.$data.commentEditForm.comment_content, onCommentEditFormSubmit(event): void, onFileUpload(), toggleCommentReplyDisplay(string, string): void, getFormData(event): {}, onPostDelete((string|number), string): (undefined), onCommentDelete((string|number)): (undefined), toggleCommentEditDisplay(string, string): void}}}
 */

const forumMixin = {
    data() {
        return {
            commentEditForm: {},
            replyNo: 0,
            editNo: 0,
        };
    },
    /**
     * create or update post.
     *
     * @param {event} event
     */
    onPostEditFormSubmit(event) {
      const data = this.getFormData(event);
      request(
        "forum.editPost",
        data,
        function (post) {
          console.log("post edit", post);
          move(post["url"]);
        },
        this.error
      );
    },
    /**
     * Request call for deleting post.
     *
     * @param {string|number} ID
     * @param {string} category
     */
    onPostDelete(ID, category) {
      const conf = confirm("Delete Post?");
      if (conf === false) return;
      request(
        "forum.deletePost",
        { ID: ID },
        function (post) {
          console.log("post delete", post);
          move("/?page=forum/list&category=" + category);
        },
        this.error
      );
    },
    /**
     * Request call for deleting comment.
     *
     * @param {string|number} ID
     */
    onCommentDelete(ID) {
      const conf = confirm("Delete Comment?");
      if (conf === false) return;
      request(
        "forum.deleteComment",
        { comment_ID: ID },
        function (post) {
          console.log("comment delete", post);
          var el = document.getElementById("comment_" + ID);
          el.remove();
        },
        this.error
      );
    },
    /**
     * onFileChange
     *
     * @param {event} event
     */
    onFileChange(event) {
      const file = event.target.files[0];
      if (!file) return;

      //   console.log("files", files);
      _this = this;
      fileUpload(
        file,
        function (progress) {
          _this.$data.uploadProgress = progress;
        },
        function (data) {
          console.log(data);
          _this.$data.uploadProgress = 0;
          // TODO: add to post or comment
        },
        this.error
      );
    },
  }
};



const commentForm = {
    props: ['comment_id', 'comment_parent', 'comment_content', 'comment_post_id'],
    template: '<form @submit.prevent="onSubmit"> parent comment id: {{ comment_ID }}' +
        '<i class="fa fa-camera fs-xl"></i>' +
        '<input type="text" v-model="comment_content">' +
        '<button class="btn btn-secondary ml-2" type="button" @click="hide" v-if="canShow">Cancel</button>' +
        '<button class="btn btn-success ml-2" type="submit">Submit</button>' +
        '</form>',
    data() {
        return {
            comment_ID: this.comment_id,
            comment_parent: this.comment_parent,
            comment_post_ID: this.comment_post_id,
            comment_content: this.comment_content,
        };
    },
    computed: {
        canShow() {
            return !!this.$data.comment_ID;
        }
    },
    watch: {

    },
    methods: {
        hide() {
            this.$root.replyNo = 0;
            this.$root.editNo = 0;
        },
        onSubmit() {
            request('forum.editComment', this.$data, refresh, app.error);
        },
        show() {
            console.log('show');
        }
    },
};
addComponent('comment-form', commentForm);




