<?php

define('TRANSLATIONS_TABLE', 'translations');

///
define('LANGUAGES', 'languages');
define('ERROR_LANGUAGE_EXISTS', 'ERROR_LANGUAGE_EXISTS');
define('ERROR_EMPTY_LANGUAGE', 'ERROR_EMPTY_LANGUAGE');
define('ERROR_EMPTY_CODE', 'ERROR_EMPTY_CODE');
define('ERROR_EMPTY_OLD_CODE', 'ERROR_EMPTY_OLD_CODE');
define('ERROR_EMPTY_NEW_CODE', 'ERROR_EMPTY_NEW_CODE');
define('ERROR_EMPTY_VALUE', 'ERROR_EMPTY_VALUE');
define('ERROR_LANGUAGE_NOT_EXISTS', 'ERROR_LANGUAGE_NOT_EXISTS');
define('ERROR_LANGUAGE_REPLACE', 'ERROR_LANGUAGE_REPLACE');
define('ERROR_CHANGE_CODE', 'ERROR_CHANGE_CODE');
define('ERROR_DELETING_TRANSLATION', 'ERROR_DELETING_TRANSLATION');
define('ERROR_TRANSLATION_NOT_EXIST', 'ERROR_TRANSLATION_NOT_EXIST');



class TranslationRoute
{

    public function addLanguage($in) {
        if ( admin() === false ) return ERROR_PERMISSION_DENIED;
        if ( ! isset($in['language']) ) return ERROR_EMPTY_LANGUAGE;
        $languages = get_option(LANGUAGES, []);
        if ( in_array($in['language'], $languages) ) return ERROR_LANGUAGE_EXISTS;
        $languages[] = $in['language'];
        update_option(LANGUAGES, $languages, false);
        return $languages;
    }

    private function get_translation_by_code($code)
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM " . TRANSLATIONS_TABLE . " WHERE code='$code'", ARRAY_A);
    }

    /**
     * List
     */
    public function list($in)
    {
        global $wpdb;
        $rows = $wpdb->get_results("SELECT * FROM " . TRANSLATIONS_TABLE . " ORDER BY code ASC", ARRAY_A);


        // VUE
        // [ 'code' => ['ko' => '...', 'en' => '...' ], 'name' => ['ko' => '이름', 'en' => 'Name' ],
        // 
        // FLUTTER (GetX recommended structure)
        // [
        //   'ko' => ['code' => '...', 'name' => '이름', ...........],
        //   'en' => ['code' => '...', 'name' => 'Name', .......],
        // ]
        
        $rets = [];
        // This is for GetX
        if ( isset($in['format']) && $in['format'] === 'language-first' ) {
            foreach($rows as $row) {
                if ( !isset($rets[$row['language']]) ) $rets[$row['language']] = [];
                $rets[$row['language']][$row['code']] = $row['value'];
            }
        } else {
            foreach($rows as $row) {
                if ( !isset($rets[$row['code']]) ) $rets[$row['code']] = [
                    'code' => $row['code'],
                ];
                $rets[$row['code']][$row['language']] = $row['value'];
            }
        }

        return ['languages' => get_option(LANGUAGES), 'translations' => $rets];
    }

    /**
     * Add new code & value or replace existing one.
     */
    public function edit($in)
    {
        if ( admin() === false ) return ERROR_PERMISSION_DENIED;
        if (!isset($in['language'])) return ERROR_EMPTY_LANGUAGE;
        if (!isset($in['code'])) return ERROR_EMPTY_CODE;
        if (!isset($in['value'])) return ERROR_EMPTY_VALUE;

        $languages = get_option(LANGUAGES, []);
        if ( ! in_array($in['language'], $languages) ) return ERROR_LANGUAGE_NOT_EXISTS;

        $data = [
            'language' => $in['language'],
            'code' => $in['code'],
            'value' => $in['value']
        ];

        global $wpdb;
        $re = $wpdb->replace(TRANSLATIONS_TABLE, $data);
        if ( $re === false ) return ERROR_LANGUAGE_REPLACE;
        return $data;
    }

    
    /**
     * Change existing code with new code.
     *
     * All the code of each language will be changed.
     *
     * Values of the code will be attached to the new code.
     */
    public function changeCode($in) {
        if ( admin() === false ) return ERROR_PERMISSION_DENIED;
        if (!isset($in['oldCode'])) return ERROR_EMPTY_OLD_CODE;
        if (!isset($in['newCode'])) return ERROR_EMPTY_NEW_CODE;
        global $wpdb;
        $re = $wpdb->update(TRANSLATIONS_TABLE, ['code' => $in['newCode'] ], ['code' => $in['oldCode']]);
        if ( $re === false ) return ERROR_CHANGE_CODE;
        return $in;
    }

    /**
     * Translation delete
     */
    public function delete($in)
    {

        if ( admin() === false ) return ERROR_PERMISSION_DENIED;
        if (!isset($in['code'])) return ERROR_EMPTY_CODE;

        global $wpdb;

        $code = $in['code'];

        /// check if it exist, return error if not.
        $tr = $this->get_translation_by_code($code);
        if (!$tr) return ERROR_TRANSLATION_NOT_EXIST;

        $re = $wpdb->delete(TRANSLATIONS_TABLE, ['code' => $code]);
        if (!$re) return ERROR_DELETING_TRANSLATION;

        return $in;
    }
}
