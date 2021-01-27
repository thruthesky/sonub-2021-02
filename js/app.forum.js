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




