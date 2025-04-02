<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$this->registerCssFile('https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/moment/min/moment.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            //'brandLabel' => Yii::$app->name,
            //'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => array_filter([
                ['label' => 'Сви Документи', 'url' => ['/documents/index']],
                Yii::$app->user->identity && Yii::$app->user->identity->status === 'admin' ? [
                    'label' => 'Корисници',
                    'url' => ['/user/index'],
                    'items' => [
                        ['label' => 'Kорисници', 'url' => ['/user/index']],
                        ['label' => 'Додај корисникa', 'url' => ['/user/create']],
                    ],
                ] : null,
                Yii::$app->user->identity && Yii::$app->user->identity->status === 'admin' ? [
                    'label' => 'Типови докумената',
                    'url' => ['/document-type/index'],
                    'items' => [
                        ['label' => 'Листа типова', 'url' => ['/document-type/index']],
                        ['label' => 'Додај тип', 'url' => ['/document-type/create']],
                    ],
                ] : null,
                Yii::$app->user->identity && Yii::$app->user->identity->status === 'admin' ? [
                    'label' => 'Организације',
                    'url' => ['/organization/index'],
                    'items' => [
                        ['label' => 'Листа организација', 'url' => ['/organization/index']],
                        ['label' => 'Додај организацију', 'url' => ['/organization/create']],
                    ],
                ] : null,
                ['label' => 'Контакт', 'url' => ['/site/contact']],
                Yii::$app->user->isGuest
                    ? ['label' => 'Пријава', 'url' => ['user/login']]
                    : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Одјава (' . Yii::$app->user->identity->name . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>',
            ]),
        ]);

        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; Зоран & Драган<?= date('Y') ?></div>
                <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>