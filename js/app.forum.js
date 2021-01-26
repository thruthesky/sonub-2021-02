const forumMixin = {

    data() {
        return {
            commentEditForm: {},
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
            if ( event.target.elements.comment_parent ) {
                this.$data.commentEditForm.comment_parent = event.target.elements.comment_parent.value;
            }
            const data = this.$data.commentEditForm;
            console.log('Post Edit Form Data', data);
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
    },
}