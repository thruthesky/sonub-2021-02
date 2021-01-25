const translationMixin = {
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
         * @param {string[]} languages 
         * @param {event} event 
         * 
         * TODO:  code update
         */
        onTranslationEditFormSubmit(languages, event) {
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
            const conf = confirm('Delete Translation?');
            if (conf === false) return;

            request('translation.delete', { 'code': code }, function(data) {
                console.log('Translation deleted :', data);
                alert("Translation Deleted!");
                refresh();
            }, this.error);
        }
    }
}
