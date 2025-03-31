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
        'options' => ['enctype' => 'multipart/form-data'], // Enable file upload
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organization_id')->dropDownList(
        Organization::find()
            ->joinWith('userOrganizations') // Assuming a relation exists between Organization and UserOrganization
            ->where(['user_organization.user_id' => Yii::$app->user->id]) // Filter by the active user's ID
            ->select(['Organizations.name', 'Organizations.id'])
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

    <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'id' => 'url-field']) ?>

    <div id="file-upload-section" style="display: none;">
        <?= $form->field($model, 'file')->fileInput(['id' => 'file-input']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сачувај', ['class' => 'btn btn-success', 'id' => 'submit-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$uploadUrl = \yii\helpers\Url::to(['documents/upload-file']); // URL for file upload action
$csrfToken = Yii::$app->request->csrfToken; // Get CSRF token
$js = <<<JS
document.getElementById('submit-button').addEventListener('click', function(event) {
    var urlField = document.getElementById('url-field');
    if (!urlField.value) {
        event.preventDefault(); // Prevent form submission
        if (confirm('Да ли желите да убаците нови фајл?')) {
            document.getElementById('file-upload-section').style.display = 'block'; // Show file upload section
        } else {
            alert('Слање је отказано.');
        }
    }
});

document.getElementById('file-input').addEventListener('change', function () {
    var fileInput = this;
    var formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('_csrf', '$csrfToken'); // Add CSRF token to the request

    fetch('$uploadUrl', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('url-field').value = data.fileUrl; // Set the uploaded file URL
            alert('Фајл је успешно отпремљен.');
            document.getElementById('file-upload-section').style.display = 'none'; // Hide file upload section
        } else {
            alert('Грешка при отпремању фајла: ' + (data.error || 'Unknown error.'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Грешка при отпремању фајла.');
    });
});
JS;

$this->registerJs($js);
?>