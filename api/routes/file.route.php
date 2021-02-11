<?php

class FileRoute {

    /**
     * Upload
     *
     * upload image and generates resized image.
     *
     * @note you may set `post_type` by passing `$_REQUEST['post_type']`
     */
    public function upload()
    {

        if (empty($_FILES)) return ERROR_EMPTY_FILES;

        debug_log($_FILES);
        debug_log($_REQUEST);
        $file = $_FILES['userfile'];
        if ($file['error']) {
            $msg = file_upload_error_code_message($file['error']);
            $err = ERROR_FILE_UPLOAD + ":{$file['error']} - $msg";
            return $err;
        }

        // Prepare to save
        $file_type = wp_check_filetype(basename($file["name"]), null); // get file type
        $file_name = get_safe_filename($file["name"]); // get save filename to save -----
        $dir = wp_upload_dir(); // Get WordPress upload folder.
        $file_path = $dir['path'] . "/$file_name"; // Get Path of uploaded file. ----
        $file_url = $dir['url'] . "/$file_name"; // Get url of uploaded file.

        if (!move_uploaded_file($file['tmp_name'], $file_path)) {
            return ERROR_MOVE_UPLOADED_FILE;
        }

        // Create a post of attachment type.
        $attachment = array(
            'guid'              => $file_url,
            'post_author'       => wp_get_current_user()->ID,
            'post_mime_type'    => $file_type['type'],
            'post_name'         => $file['name'],
            'post_title'        => $file_name,
            'post_content'      => '',
            'post_status'       => 'inherit',
            // 'post_parent'       => 8
        );

        if ( isset($_REQUEST['post_type']) ) {
            $attachment['post_type'] = $_REQUEST['post_type'];
        }



        // xlog($attachment);
        // This does not upload a file but creates a 'attachment' post type in wp_posts.
        $attach_id = @wp_insert_attachment($attachment, $file_name);
        if ($attach_id == 0 || is_wp_error($attach_id)) {
            return ERROR_ATTACH_FILE_TO_POST;
        }

        debug_log("attach_id: $attach_id");
        update_attached_file($attach_id, $file_path); // update post_meta for the use of get_attached_file(), get_attachment_url();
        require_once ABSPATH . 'wp-admin/includes/image.php';

        /**
         * Generating attachment metadata and Resized images derived from the original (thumbnails).
         *
         * @note - `wp_generate_attachment_metadata` will also generates different sizes of the image.
         *          meaning, it will generate several files.
         *
         * @note - it will only generates sizes according to default WP sizes and custom sizes added on functions.php.
         *
         */
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
        wp_update_attachment_metadata($attach_id,  $attach_data);

        return get_uploaded_file($attach_id);
    }


    /**
     * Deletes a file
     *
     * @return mixed
     *  - file record information on success.
     *  - ErrorObject otherwise.
     *
     * TODO: when a file is deleted, delete it from the meta.
     */
    public function delete()
    {


        if (!in('ID')) return ERROR_EMPTY_ID;

        if (admin() || is_my_file(in('ID'))) {
            // pass
        } else {
            return ERROR_NOT_YOUR_FILE;
        }

        /// get file
        $file = get_post(in('ID'));

        /**
         * For comment files, the post type is COMMENT_ATTACHMENT and it cannot be deleted.
         * @see attachFiles() for details.
         */
        global $wpdb;
        $result = $wpdb->update($wpdb->posts, ['post_type' => 'attachment'], ['ID' => in('ID')]);

        $re = wp_delete_attachment(in('ID'), true);

        // TODO: 부모 글의 META 정보에서, 업로드 파일 ID 들에서, 삭제된 파일 아이디를 없앤다.
//        $file->post_parent;
        if ( $re ) return ['ID' => $re->ID];
        else return ERROR_DELETE_FILE;
    }


}