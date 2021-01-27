<?php

class ForumRoute
{

    public function search($in)
    {
        return forum_search($in);
    }


    /**
     * @note if in('ID') is set, then it will update the post. And category can be changed.
     * @return array|mixed|string
     *
     * @note for updating, post_title, post_content, category are optional.
     *  These properties are remained.
     * @note for creating, category, post_title, post_content are required.
     */
    public function editPost($in)
    {
        return api_edit_post($in);

    }



    /**
     * Get a post.
     *
     * @see wordpress-api.service.ts::postGet() for more details.
     *
     */
    public function getPost()
    {
        return post_response(in('id'));
    }


    public function deletePost()
    {

        if (!is_my_post(in('ID'))) return ERROR_NOT_YOUR_POST;
        /**
         * In the official doc, it is stated that attachments are removed or trashed when post is deleted with method.
         */
        $re = wp_delete_post(in('ID'));
        if ($re) {
            return ['ID' => $re->ID];
        } else {
            return ERROR_DELETE_POST;
        }
    }





    public function editComment()
    {
        $user = wp_get_current_user();


        if ( in('comment_post_ID') == null ) return ERROR_EMPTY_COMMENT_POST_ID;

        if (in('comment_ID') == null) {
            $commentdata = [
                'comment_post_ID' => in('comment_post_ID'),
                'comment_content' => in('comment_content'),
                'comment_parent' => in('comment_parent'),
                'user_id' => $user->ID,
                'comment_author' => $user->nickname,
                'comment_author_url' => $user->user_url,
                'comment_author_email' => $user->user_email,

                /// if removed, will cause error: Undefined index: comment_type.
                'comment_type' => '',
            ];
            $comment_id = wp_new_comment($commentdata, true);
            if (is_wp_error($comment_id)) {
                $msg = $comment_id->get_error_message();
                if (strpos($msg, 'too quickly') !== false) {
                    return ERROR_SLOW_DOWN_ON_COMMENTING;
                }
                return ERROR_COMMENT_EDIT . ':' . $msg;
            }
        } else {
            if (!is_my_comment(in('comment_ID'))) return ERROR_NOT_YOUR_COMMENT;
            /**
             * There is no error on wp_update_comment.
             */
            $re = wp_update_comment([
                'comment_ID' => in('comment_ID'),
                'comment_content' => in('comment_content')
            ], true);
            if (is_wp_error($re)) {
                return ERROR_COMMENT_EDIT . ':' . $re->get_error_message();
            }
            if ($re == 0) {
                return ERROR_COMMENT_NOT_UPDATED;
            }
            $comment_id = in('comment_ID');
        }

        if (in('files')) {
            attach_files($comment_id, in('files'), COMMENT_ATTACHMENT);
        }

        return comment_response($comment_id);
    }

    public function deleteComment()
    {
        if (!in('comment_ID')) return ERROR_EMPTY_COMMENT_ID;
        if (!is_my_comment(in('comment_ID'))) return ERROR_NOT_YOUR_COMMENT;
        $re = wp_delete_comment(in('comment_ID'));
        if ($re) return ['comment_ID' => intval(in('comment_ID'))];
        else return ERROR_DELETE_COMMENT;
    }


    /**
     * Set the image of $in['featured_image_ID'] as the featured image of the post.
     *
     * @todo let user put the featured image of his own.
     * @param $in
     * @return array|string
     */
    public function setFeaturedImage($in)
    {
        if (!is_my_post($in['ID'])) return ERROR_NOT_YOUR_POST;
        $re = set_post_thumbnail($in['ID'], $in['featured_image_ID']);
        if ($re) return ['ID' => $in['ID'], 'featured_image_ID' => $in['featured_image_ID']];
        else return ERROR_SET_FEATURE_IMAGE;
    }


    public function updateCategory($in)
    {

        if (!isset($in['cat_ID'])) return ERROR_EMPTY_TERM_ID;
        if (!isset($in['name'])) return ERROR_EMPTY_NAME;
        if (!isset($in['value'])) return ERROR_EMPTY_VALUE;

        if ( in_array($in['name'], ['cat_name', 'category_description']) ) {
            $re = wp_insert_category(['cat_ID' => $in['cat_ID'], $in['name'] => $in['value']], true);
            if ( is_wp_error($re) ) {
                return $re->get_error_message();
            }
        } else {
            $re = update_term_meta($in['term_id'], $in['name'], $in['value']);
            if ( is_wp_error($re) ) {
                return $re->get_error_message();
            }
        }

        return $in;
    }
}
