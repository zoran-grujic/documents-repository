<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DocumentsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Документи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documents-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Додај документ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'title',
                'format' => 'raw', // Allow raw HTML for the link or error label
                'value' => function ($model)
                {
                    if (!empty($model->url))
                    {
                        // If the URL is not empty, display the title as a link
                        return Html::a(Html::encode($model->title), $model->url, [
                            'title' => 'Погледај документ', // Tooltip text
                            'class' => 'document-link', // Optional: Add a CSS class for styling
                            'target' => '_blank', // Open the link in a new tab
                        ]);
                    }
                    else
                    {
                        // If the URL is empty, display an error label
                        return Html::tag('span', $model->title, [
                            'class' => 'text-danger', // Add a CSS class for styling the error
                            'title' => 'URL није доступан за овај документ', // Tooltip text
                        ]);
                    }
                },
            ],
            [
                'attribute' => 'organization_id',
                'value' => 'organization.name',
                'filter' =>
                Html::textInput(
                    'organization_name', // This is for the autocomplete input
                    $organizationName, // Pre-fill with the organization name
                    [
                        'class' => 'form-control',
                        'id' => 'organization-autocomplete',
                        'placeholder' => 'Претражите организацију',
                    ]
                ) .
                    Html::hiddenInput(
                        'DocumentsSearch[organization_id]', // This is the hidden input for the actual filter
                        $searchModel->organization_id,
                        ['id' => 'documentssearch-organization_id']
                    ),
            ],

            [
                'attribute' => 'user_id',
                'value' => 'user.name', // Display the user's name
                'filter' =>
                Html::textInput(
                    'user_name', // This is for the autocomplete input
                    $userName, // Pre-fill with the user name
                    [
                        'class' => 'form-control',
                        'id' => 'user-autocomplete',
                        'placeholder' => 'Претражите корисника',
                    ]
                ) .
                    Html::hiddenInput(
                        'DocumentsSearch[user_id]', // This is the hidden input for the actual filter
                        $searchModel->user_id,
                        ['id' => 'documentssearch-user_id']
                    ),
            ],

            [
                'attribute' => 'date_create',
                'value' => 'date_create',
                'filter' => Html::textInput(
                    'DocumentsSearch[date_create]',
                    $searchModel->date_create,
                    [
                        'class' => 'form-control',
                        'id' => 'date_create-range-picker',
                        'placeholder' => 'Изаберите опсег датума',
                    ]
                ),
            ],
            [
                'attribute' => 'date_insert',
                'value' => 'date_insert',
                'filter' => Html::textInput(
                    'DocumentsSearch[date_insert]',
                    $searchModel->date_insert,
                    [
                        'class' => 'form-control',
                        'id' => 'date_insert-range-picker',
                        'placeholder' => 'Изаберите опсег датума',
                    ]
                ),
            ],

            [
                'attribute' => 'type_id', // Use type_id for filtering
                'value' => 'type.name', // Display the type name
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'type_id',
                    \yii\helpers\ArrayHelper::map(\app\models\DocumentTypes::find()->all(), 'id', 'name'),
                    ['class' => 'form-control', 'prompt' => 'Изаберите тип документа'] // Add a prompt for the dropdown
                ),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key)
                    {
                        return Html::a('Погледај', $url, ['class' => 'btn btn-info btn-sm']);
                    },
                    'update' => function ($url, $model, $key)
                    {
                        return Html::a('Измени', $url, ['class' => 'btn btn-primary btn-sm']);
                    },
                    'delete' => function ($url, $model, $key)
                    {
                        return Html::a('Обриши', $url, [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Да ли сте сигурни да желите да обришете овај документ?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>

<?php
$autocompleteUrl = \yii\helpers\Url::to(['organization/organization-list']);
$userAutocompleteUrl = \yii\helpers\Url::to(['user/user-list']);
$js = <<<JS

var locale={
    format: 'YYYY-MM-DD',
    applyLabel: 'Примени',
    cancelLabel: 'Откажи',
    fromLabel: 'Од',
    toLabel: 'До',
    customRangeLabel: 'Прилагођени опсег',
    daysOfWeek: ['Нд', 'По', 'Ут', 'Ср', 'Чт', 'Пт', 'Сб'],
    monthNames: ['Јануар', 'Фебруар', 'Март', 'Април', 'Мај', 'Јун', 'Јул', 'Август', 'Септембар', 'Октобар', 'Новембар', 'Децембар'],
    firstDay: 1
};
// Initialize date range picker for date_create
$('#date_create-range-picker').daterangepicker({
    locale: locale,
    autoUpdateInput: false,
    opens: 'left',
}, function(start, end, label) {
    $('#date_create-range-picker').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));   
    $("#w0").yiiGridView("applyFilter");
});

// Initialize date range picker for date_insert
$('#date_insert-range-picker').daterangepicker({
    locale: locale,
    autoUpdateInput: false,
    opens: 'left',
}, function(start, end, label) {
    $('#date_insert-range-picker').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
    $("#w0").yiiGridView("applyFilter");
    
});

$('#organization-autocomplete').autocomplete({
    source: function(request, response) {
        $.ajax({
            url: '$autocompleteUrl',
            dataType: 'json',
            data: {
                term: request.term
            },
            success: function(data) {
                response(data);
            }
        });
    },
    minLength: 2, // Minimum characters to trigger autocomplete
    select: function(event, ui) {
        $('#organization-autocomplete').val(ui.item.label); // Display the selected name
        $('#documentssearch-organization_id').val(ui.item.value); // Set the hidden input value
        $("#w0").yiiGridView("applyFilter"); // Trigger GridView filter
        return false;
    }
});

// Reset organization_id if the input is empty
$('#organization-autocomplete').on('keyup', function() {
    if (!$(this).val()) {
        $('#documentssearch-organization_id').val(''); // Reset the hidden input value
    }
});

$('#user-autocomplete').autocomplete({
    source: function(request, response) {
        $.ajax({
            url: '$userAutocompleteUrl',
            dataType: 'json',
            data: {
                term: request.term
            },
            success: function(data) {
                response(data);
            }
        });
    },
    minLength: 2, // Minimum characters to trigger autocomplete
    select: function(event, ui) {
        $('#user-autocomplete').val(ui.item.label); // Display the selected name
        $('#documentssearch-user_id').val(ui.item.value); // Set the hidden input value
        $("#w0").yiiGridView("applyFilter"); // Trigger GridView filter
        return false;
    }
});
// Reset organization_id if the input is empty
$('#user-autocomplete').on('keyup', function() {
    if (!$(this).val()) {
        $('#documentssearch-user_id').val(''); // Reset the hidden input value
    }
});


JS;

$this->registerJs($js);
?>