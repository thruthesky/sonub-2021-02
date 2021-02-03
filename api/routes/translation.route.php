<?php


///

class TranslationRoute
{

    public function addLanguage($in)
    {
        return api_add_translation_language($in);
    }

    /**
     * List
     */
    public function list($in)
    {
        return api_get_translations($in);
    }

    /**
     * Add new code & value or replace existing one.
     */
    public function edit($in)
    {
        return api_edit_translation($in);
    }


    /**
     * Change existing code with new code.
     *
     * All the code of each language will be changed.
     *
     * Values of the code will be attached to the new code.
     */
    public function changeCode($in)
    {
        return api_change_translation_code($in);
    }

    /**
     * Translation delete
     */
    public function delete($in)
    {
        return api_delete_translation($in);
    }
}

