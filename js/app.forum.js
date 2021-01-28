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
    methods: {

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
        commentEditFormCanSubmit() {
            return this.$data.commentEditForm.comment_content;
        },
        /**
         * create or update post.
         * 
         * @param {event} event 
         */
        onPostEditFormSubmit(event) {
            const data = this.getFormData(event);
            request('forum.editPost', data, function(post) {
                console.log('post edit', post);
                move(post['url']);
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
            this.$data.commentEditForm.comment_post_ID = event.target.elements.comment_post_ID.value;

            /// will have value if creating comment
            if ( event.target.elements.comment_parent ) {
                this.$data.commentEditForm.comment_parent = event.target.elements.comment_parent.value;
            }
            /// will have value if updating comment
            if (event.target.elements.comment_ID ) {
                this.$data.commentEditForm.comment_ID = event.target.elements.comment_ID.value;
                this.$data.commentEditForm.comment_content = event.target.elements.comment_content.value;
            }
            const data = this.$data.commentEditForm;
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
                var el = document.getElementById("comment_" + ID);
                el.remove();
            }, this.error);
        },
        onFileUpload() {

        },
        /**
         * toggle visibility of comment update form
         * 
         * @param {string} elementID 
         * @param {string} display - 'none' | 'block
         */
        toggleCommentEditDisplay(elementID, display) {
            var el = document.getElementById('comment_content_' + elementID);
            var el2 = document.getElementById('comment_content_edit_' + elementID)
            el.style.display = display;
            el2.style.display = display == 'block' ? 'none' : 'block';
        },
        /**
         * toggle visibility of comment reply form
         * 
         * @param {string} elementID 
         * @param {string} display - 'none' | 'block
         */
        toggleCommentReplyDisplay(elementID, display) {
            var el = document.getElementById('comment_reply_' + elementID);
            el.style.display = display;
        },
    },
};



const commentForm = {
    props: ['comment_id', 'comment_parent', 'comment_content', 'comment_post_id', 'files'],
    template: '<form @submit.prevent="onSubmit">' +
        '<div class="d-flex bg-light">' +
        '<div class="position-relative d-inline-block of-hidden">' +
        '<i class="fa fa-camera fs-xl"></i>' +
        '<input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onCommentFileUpload($event)">' +
        '</div>' +
        '<textarea class="w-100" v-model="form.comment_content"></textarea>' +
        '</div>' +

        '<button class="btn btn-secondary ml-2" type="button" @click="onCancel()" v-if="canCancel()">Cancel</button>' +
        '<button class="btn btn-success ml-2" type="submit" v-if="canSubmit()">Submit</button>' +
        '</form>' +
        '<div>' +
        '<div class="progress mt-3" style="height: 5px;" v-if="$root.uploadPercentage > 0">' +
        '   <div class="progress-bar" role="progressbar" :style="{width: $root.uploadPercentage + \'%\'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>' +
        '</div>' +
        '<div class="uploaded-files d-flex">' +
        '<div class="position-relative p-1" v-for="file in uploaded_files">' +
        '<img class="size-100" :src="file.url">' +
        '<i class="fa fa-trash fs-lg position-absolute top left"></i>' +
        '</div>' +
        '</div>' +
        '{{ uploaded_files }}' +
        '</div>',
    data() {
        return {
            form: {
                comment_ID: this.comment_id,
                comment_parent: this.comment_parent,
                comment_post_ID: this.comment_post_id,
                comment_content: this.comment_content,
                files: [],
            },
            uploaded_files: this.files,
        };
    },
    created() {
        if ( this.$data.uploaded_files && this.$data.uploaded_files.length > 0 ) {
            const $this = this;
            this.$data.uploaded_files.forEach(function(v) {
               $this.$data.form.files.push( v.ID );
            });
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
            request('forum.editComment', this.$data.form, refresh, app.error);
        },
        onCommentFileUpload(event) {
            const $this = this;
            app.uploadPercentage = 0;
            this.$root.onFileUpload(event, function(res) {
                console.log('file upload: ', res);
                $this.$data.form.files.push(res.ID);
                $this.$data.uploaded_files.push(res);
                app.uploadPercentage = 0;
            });
        },
    },
};
addComponent('comment-form', commentForm);




