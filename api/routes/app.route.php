<?php

class AppRoute {
    public function version() {
        return ['version'=>'0.0.1'];
    }


    /**
     * General function to update a field of a table.
     *
     * It can insert or update a field of any table.
     *
     * @requirement
     *  - The table must have `user_ID` field as unique index and its value must be the login user's ID.
     *  - The field must exists in the table.
     *  - The table must also have `createdAt` and `updatedAt` integer field.
     *  - All the fields of the table should have default value, so it would not produce SQL error while inserting.
     *
     * @note
     *  - `createdAt` will have timestamp on inserting.
     *  - `updatedAt` will have new timestamp on every update.
     *
     * @param $in array
     *  $in['table'] is the table to update.
     *  $in['user_ID'] is the login user's ID. The user may login with session ID.
     *  $in['field'] is the field to update.
     *  $in['value'] is the value to update.
     *
     * @return array|string
     *  Returns the record of the table. Becareful not to put too big data in a record.
     *
     * @example
     *  - See tests/app.update.test.php
     *
     * @note user must login before this call.
     */
    public function update(array $in) {
        if ( !isset($in['table']) ) return ERROR_EMPTY_TABLE;
        if ( !isset($in['field'] ) ) return ERROR_EMPTY_FIELD;

        if ( ! is_user_logged_in() ) return ERROR_LOGIN_FIRST;

        return table_update($in);

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
}
