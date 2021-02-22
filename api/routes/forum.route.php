<?php

class ForumRoute
{

    /**
     *
     * @param $in
     * @see the params at https://developer.wordpress.org/reference/classes/wp_query/parse_query/
     * @return array|string
     */
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


    public function deletePost($in)
    {
        api_delete_post($in);
    }

    public function searchComments($in)
    {
        // if (!isset($in['user_id'])) return ERROR_EMPTY_ID;


        $comments = get_comments($in);
        $rets = [];
        foreach ($comments as $comment) {
            $rets[] = comment_response($comment->comment_ID);
        }

        return $rets;
    }


    public function getComment()
    {
        return comment_response(in('comment_ID'));
    }


    public function editComment($in)
    {
        return api_edit_comment($in);
    }


    public function deleteComment($in)
    {
        return api_delete_comment($in);
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


    /**
     * @see update_category() for details.
     * @param $in
     * @return mixed
     */
    public function updateCategory($in)
    {
        return update_category($in);
    }

    public function vote($in) {
        return api_vote($in);
    }
}

