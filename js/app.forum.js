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
    };
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

    fileUpload(
      file,
      function (progress) {
        this.$data.uploadProgress = progress;
      },
      function (data) {
        console.log(data);
        // TODO: add to post or comment
      },
      this.error
    );
  },
};

/**
 * post form component
 */
const postEditForm = {
  props: ["post_id", "category", "post_title", "post_content", "files"],
  template:
    '<form @submit.prevent="onSubmit">' +
    '<div class="form-group">' +
    '<label for="post_title">Title</label>' +
    '<input type="text" class="form-control" id="post_title" name="post_title" v-model="form.post_title" />' +
    "</div>" +
    '<div class="form-group">' +
    '<label for="post_content">Content</label>' +
    '<input type="text" class="form-control" id="post_content" name="post_content" v-model="form.post_content" />' +
    "</div>" +
    '<div class="d-flex justify-content-between mt-3">' +
    '<div class="position-relative d-inline-block of-hidden">' +
    '<i class="fa fa-camera fs-xl"></i>' +
    '<input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onPostFileUpload($event)">' +
    "</div>" +
    '<button type="submit" class="btn btn-primary">Submit</button>' +
    "</div>" +
    "</form>" +
    '<div class="progress mt-3" style="height: 5px;" v-if="$root.uploadPercentage > 0">' +
    '   <div class="progress-bar" role="progressbar" :style="{width: $root.uploadPercentage + \'%\'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>' +
    "</div>" +
    '<div class="uploaded-files d-flex mt-2">' +
    '<div class="position-relative p-1" v-for="(file, index) in uploaded_files">' +
    '<img class="size-100" :src="file.url">' +
    '<i class="fa fa-trash fs-lg position-absolute top left red pointer" @click="onPostFileDelete(file.ID, index)"></i>' +
    "</div>" +
    "</div>",
  data() {
    return {
      form: {
        ID: this.post_id,
        category: this.category,
        post_title: this.post_title,
        post_content: this.post_content,
        files: [],
      },
      uploaded_files: this.files ?? [],
    };
  },
  created() {
    if (this.$data.uploaded_files && this.$data.uploaded_files.length > 0) {
      const $this = this;
      this.$data.uploaded_files.forEach(function (v) {
        $this.$data.form.files.push(v.ID);
      });
    }
  },
  methods: {
    /**
     * create or update post.
     */
    onSubmit() {
      console.log(this.$data.form);
      request(
        "forum.editPost",
        this.$data.form,
        function (post) {
          console.log("post edit", post);
          move(post["url"]);
        },
        this.error
      );
    },
    onPostFileUpload(event) {
      const $this = this;
      app.uploadPercentage = 0;
      this.$root.onFileUpload(event, function (res) {
        console.log("file upload: ", res);
        $this.$data.form.files.push(res.ID);
        $this.$data.uploaded_files.push(res);
        app.uploadPercentage = 0;
      });
    },
    onPostFileDelete(ID, index) {
      const $this = this;
      this.$root.onFileDelete(ID, function (data) {
        console.log("deleted file :", data);
        alert("File deleted!");
        $this.$data.uploaded_files.splice(index, 1);
      });
    },
  },
};
addComponent("post-edit-form", postEditForm);

/**
 * comment form component
 */
const commentForm = {
  props: [
    "comment_id",
    "comment_parent",
    "comment_content",
    "comment_post_id",
    "files",
  ],
  template:
    '<form @submit.prevent="onSubmit">' +
    '<div class="d-flex bg-light">' +
    '<div class="position-relative d-inline-block of-hidden">' +
    '<i class="fa fa-camera fs-xl"></i>' +
    '<input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onCommentFileUpload($event)">' +
    "</div>" +
    '<textarea class="w-100" v-model="form.comment_content"></textarea>' +
    "</div>" +
    '<button class="btn btn-secondary ml-2" type="button" @click="onCancel()" v-if="canCancel()">Cancel</button>' +
    '<button class="btn btn-success ml-2" type="submit" v-if="canSubmit()">Submit</button>' +
    "</form>" +
    "<div>" +
    '<div class="progress mt-3" style="height: 5px;" v-if="$root.uploadPercentage > 0">' +
    '   <div class="progress-bar" role="progressbar" :style="{width: $root.uploadPercentage + \'%\'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>' +
    "</div>" +
    '<div class="uploaded-files d-flex">' +
    '<div class="position-relative p-1" v-for="(file, index) in uploaded_files">' +
    '<img class="size-100" :src="file.url">' +
    '<i class="fa fa-trash fs-lg position-absolute top left red pointer" @click="onCommentFileDelete(file.ID, index)"></i>' +
    "</div>" +
    "</div>" +
    "</div>",
  data() {
    return {
      form: {
        comment_ID: this.comment_id,
        comment_parent: this.comment_parent,
        comment_post_ID: this.comment_post_id,
        comment_content: this.comment_content,
        files: [],
      },
      uploaded_files: this.files ?? [],
    };
  },
  created() {
    if (this.$data.uploaded_files && this.$data.uploaded_files.length > 0) {
      const $this = this;
      this.$data.uploaded_files.forEach(function (v) {
        $this.$data.form.files.push(v.ID);
      });
    }
  },
  methods: {
    canCancel() {
      return (
        !!this.$data.form.comment_ID ||
        !!this.$data.form.comment_parent ||
        this.canSubmit()
      );
    },
    canSubmit() {
      return (
        !!this.$data.form.comment_content || this.$data.form.files.length > 0
      );
    },
    onCancel() {
      this.$root.replyNo = 0;
      this.$root.editNo = 0;
      this.$data.form.comment_content = "";
      this.$data.form.files = [];
      this.$data.uploaded_files = [];
    },
    onSubmit() {
      request("forum.editComment", this.$data.form, refresh, app.error);
    },
    onCommentFileUpload(event) {
      const $this = this;
      app.uploadPercentage = 0;
      this.$root.onFileUpload(event, function (res) {
        console.log("file upload: ", res);
        $this.$data.form.files.push(res.ID);
        $this.$data.uploaded_files.push(res);
        app.uploadPercentage = 0;
      });
    },
    onCommentFileDelete(ID, index) {
      const $this = this;
      this.$root.onFileDelete(ID, function (data) {
        console.log("deleted file :", data);
        alert("File deleted!");
        $this.$data.uploaded_files.splice(index, 1);
      });
    },
  },
};
addComponent("comment-form", commentForm);
