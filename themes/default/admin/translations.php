<?php
$trans = api_get_translations([]);

$languages = $trans['languages'];
$translations = $trans['translations'];


print_r($translations);

?>
<hr>
<h3 class="mt-5">Add Language</h3>
<form class="d-flex w-25" @submit.prevent="onNewLanguageFormSubmit">
    <input class="form-control" type="text" placeholder="Language" v-model="newLanguage" />
    <button type="submit" class="btn btn-success btn-sm">Add</button>
</form>


<h3 class="mt-5">Add Translation</h3>
<form class="d-flex w-100" @submit.prevent='onTranslationEditFormSubmit(null)'>
    <input class="form-control" type="text" placeholder="Translation Code" v-model="newTranslation.code" />
    <?php foreach ($languages as $ln) { ?>
        <input class="form-control" type="text" placeholder="<?php echo $ln ?>" v-model="newTranslation.<?php echo $ln ?>" />
    <?php } ?>
    <button type="submit" class="btn btn-success btn-sm">Add</button>
</form>

<h3 class="mt-5">Translation Table</h3>
<?php foreach ($translations as $translation) { ?>
    <form class="table table-striped" @submit.prevent='onTranslationEditFormSubmit($event)'>
        <input type="hidden" name="code" value="<?php echo $translation['code'] ?>">
        <tbody>
            <tr>
                <td><button type="button" class="text-button" @click="onClickCode('<?php echo $translation['code'] ?>')"><?php echo $translation['code'] ?></button></td>
                <?php foreach ($languages as $ln) { ?>
                    <td><input type="text" name="<?php echo $ln ?>" value="<?php echo $translation[$ln] ?>"></td>
                <?php } ?>
                <td>
                    <button type="submit" class="btn btn-success"> Save </button>
                    <button type="button" @click='onTranslationDelete("<?php echo $translation["code"] ?>")' class="btn btn-warning"> Delete </button>
                </td>
            </tr>
        </tbody>
    </form>
<?php } ?>

<script>
    const languages = <?php echo json_encode($languages); ?>;
    const mixin = {
        data() {
            return {
                newLanguage: '',
                newTranslation: {
                    code: '',
                },
            }
        },
        methods: {
            /**
             * Create new Language for translation.
             */
            onNewLanguageFormSubmit() {
                console.log('onNewLanguageFormSubmit :', this.$data.newLanguage);
                if (this.$data.newLanguage === '') {
                    return this.error("PLEASE INPUT LANGUAGE");
                }

                request('translation.addLanguage', { 'language': this.$data.newLanguage }, function(data) {
                    console.log('new language added :', data);
                    alert("Language Added!");
                    refresh();
                }, this.error);
            },
            /**
             *
             * @param {*} form
             */
            getFormData(form) {
                const formData = new FormData(form); // reference to form element
                const data = {}; // need to convert it before using not with XMLHttpRequest
                for (let [key, val] of formData.entries()) {
                    Object.assign(data, { [key]: val });
                }
                return data;
            },
            /**
             * @param {event} event
             *
             * TODO:  code update
             */
            onTranslationEditFormSubmit(event) {
                translation = {};

                if (event !== null) {
                    translation =  this.getFormData(event.target);
                } else {
                    translation = this.$data.newTranslation;
                }

                console.log('onNewTranslationFormSubmit :', translation, languages);
                if (translation.code === '') {
                    return this.error("PLEASE INPUT TRANSLATION CODE");
                }
                this.onTranslationEdit(languages, translation);
            },
            /**
             * Create new translation set.
             *
             * @param {string[]} languages
             */
            onTranslationEdit(languages, translation) {
                _this = this;
                languages.forEach(function(ln) {
                    const req = {
                        'code': translation.code,
                        'language': ln,
                        'value': translation[ln],
                    }
                    console.log(req);
                    request('translation.edit', req, function(data) {
                        console.log('new translation added :', data);
                        refresh();
                    }, this.error);
                });
                refresh();
            },
            /**
             * Delete Translation for all language.
             *
             * @param {string} code
             */
            onTranslationDelete(code) {
                console.log('onTranslationDelete :', code);
                const conf = confirm('Delete Translation - ' + code + '?');
                if (conf === false) return;

                request('translation.delete', { 'code': code }, function(data) {
                    console.log('Translation deleted :', data);
                    alert("Translation Deleted!");
                    refresh();
                }, this.error);
            },
            onClickCode(code) {
                const re = prompt('Input new code', code);
                if ( ! re ) return;
                if ( re === code ) return;
                console.log('TODO: change code from: ' + code + ', to: ' + re);
            }
        }
    }

</script>
