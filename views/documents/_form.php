<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use app\models\Organization;
use app\models\User;
use app\models\DocumentTypes;

/** @var yii\web\View $this */
/** @var app\models\Documents $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="documents-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data', 'id' => 'form-document'], // Enable file upload
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organization_id')->dropDownList(
        Organization::find()
            ->joinWith('userOrganizations') // Assuming a relation exists between Organization and UserOrganization
            ->where(['user_organization.user_id' => Yii::$app->user->id]) // Filter by the active user's ID
            ->select(['organizations.name', 'organizations.id'])
            ->indexBy('id')
            ->column(),
        ['prompt' => 'Изаберите организацију']
    ) ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <div class="form-group">
        <label>Корисник</label>
        <input type="text" class="form-control" value="<?= Yii::$app->user->identity->name ?>" readonly>
    </div>

    <?= $form->field($model, 'type_id')->dropDownList(
        DocumentTypes::find()->select(['name', 'id'])->indexBy('id')->column(),
        ['prompt' => 'Изаберите тип документа']
    ) ?>

    <?= $form->field($model, 'date_create')->input('date') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::button('Убаци фајл и генериши нови URL', ['class' => 'btn btn-info', 'id' => 'uploadform-button']) ?>
    </div>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'id' => 'url-field']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сачувај', ['class' => 'btn btn-success', 'id' => 'submit-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$uploadUrl = \yii\helpers\Url::to(['documents/upload-file']); // URL for file upload action
$csrfToken = Yii::$app->request->csrfToken; // Get CSRF token
$js = <<<JS
// Initialize the jQuery dialog
$('#file-upload-dialog').dialog({
    autoOpen: false,
    modal: true,
    title: 'Upload File',
    width: 400,
    buttons: {
        "Upload": function() {
            var formData = new FormData();
            formData.append('file', $('#file-input')[0].files[0]);
            formData.append('_csrf', '$csrfToken'); // Append CSRF token for security

            $.ajax({
                url: '$uploadUrl',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        $('#url-field').val(data.fileUrl); // Set the uploaded file URL
                        alert('Фајл је успешно отпремљен.');
                        $('#file-upload-dialog').dialog('close'); // Close the dialog
                    } else {
                        alert('Грешка при отпремању фајла: ' + (data.error || 'Unknown error.'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Грешка при отпремању фајла.');
                }
            });
        },
        "Cancel": function() {
            $(this).dialog('close');
        }
    }
});

$('#uploadform-button').on('click', function(event) {    
    $('#file-upload-dialog').dialog('open'); // Open the dialog
});
JS;

$this->registerJs($js);
?>

<div id="file-upload-dialog" style="display: none;">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data', 'id' => 'upload-form'], // Enable file upload
    ]); ?>
    <p>Молимо вас да изаберете фајл за отпремање:</p>
    <?= Html::fileInput('file', null, ['id' => 'file-input']) ?>
    <?php ActiveForm::end(); ?>
</div>