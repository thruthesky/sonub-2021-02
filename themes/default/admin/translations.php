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
<form class="d-flex w-100" @submit.prevent='onTranslationEditFormSubmit(<?php echo json_encode($languages); ?>, null)'>
    <input class="form-control" type="text" placeholder="Translation Code" v-model="newTranslation.code" />
    <?php foreach ($languages as $ln) { ?>
        <input class="form-control" type="text" placeholder="<?php echo $ln ?>" v-model="newTranslation.<?php echo $ln ?>" />
    <?php } ?>
    <button type="submit" class="btn btn-success btn-sm">Add</button>
</form>

<h3 class="mt-5">Translation Table</h3>
<?php foreach ($translations as $translation) { ?>
    <form class="table table-striped" @submit.prevent='onTranslationEditFormSubmit(<?php echo json_encode($languages); ?>, $event)'>
        <tbody>
            <tr>
                <td><input type="hidden" name="oldCode" value="<?php echo $translation['code'] ?>"></td>
                <td><input type="text" name="code" value="<?php echo $translation['code'] ?>"></td>
                <?php foreach ($languages as $ln) { ?>
                    <td><input type="text" name="<?php echo $ln ?>" value="<?php echo $translation[$ln] ?>"></td>
                <?php } ?>
                <td>
                    <button type="submit" class="btn btn-success"> Save </button>
                    <button type="button" class="btn btn-warning"> Cancel </button>
                </td>
            </tr>
        </tbody>
    </form>
<?php } ?>