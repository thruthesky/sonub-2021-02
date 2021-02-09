<?php
/**
 * @file app.route.php
 */
/**
 * Class AppRoute
 */
class AppRoute {

    /**
     * Returns API version to client end.
     * @return array
     */
    public function version() {
        return ['version' => api_version()];
    }


    /**
     * @deprecated No use case for this method.
     * @see table_updates()
     */
    public function updates(array $in) {
        if ( !isset($in['session_id']) ) return ERROR_EMPTY_SESSION_ID;
        if ( notLoggedIn() ) return ERROR_LOGIN_FIRST;
        if ( !isset($in['table']) ) return ERROR_EMPTY_TABLE;
        if ( ! is_user_logged_in() ) return ERROR_LOGIN_FIRST;
        return table_updates($in);
    }

    /**
     * Get the record of user.
     *
     * It can be used with combination of update.
     *
     * @param array $in
     * @return array|object|string|void|null
     *
     * - possible error code
     *  - ERROR_EMPTY_RESPONSE when there is no record.
     *  - ERROR_EMPTY_TABLE when there is no table.
     */
    public function get(array $in) {

        if ( !isset($in['table']) ) return ERROR_EMPTY_TABLE;
        $row = table_get($in);
        if ( ! $row ) return ERROR_APP_GET_NO_RECORD;
        return $row;
    }

    // TODO: Only admin can update.
    public function config(array $in) {
        return $this->update([
            'table' => 'config',
            'field' => $in['code'],
            'value' => $in['value']
        ]);
    }


    /**
     * @param $in
     * @return array|object|string|null
     */
    public function query($in) {
        return sql_query($in);
    }

    public function settings() {
        return api_get_settings();
    }
}
