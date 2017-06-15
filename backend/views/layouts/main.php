<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '后台管理',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $leftMenuItems = [];


    $menuItems = [];
    if (Yii::$app->user->isGuest) {

        $menuItems[] = ['label' => '登录', 'url' => ['/user/login']];
    } else {
        $leftMenuItems = [
            ['label'=>'商品管理','items'=>[
                ['label'=>'商品列表','url'=>['goods/index']],
                ['label'=>'添加商品','url'=>['goods/add']],
            ]],
            ['label'=>'品牌管理','items'=>[
                ['label'=>'品牌列表','url'=>['brand/index']],
                ['label'=>'添加品牌','url'=>['brand/add']],
            ]],
            ['label'=>'商品分类','items'=>[
                ['label'=>'分类列表','url'=>['goods-category/index']],
                ['label'=>'添加分类','url'=>['goods-category/add']],
            ]],
            ['label'=>'文章管理','items'=>[
                ['label'=>'文章列表','url'=>['article/index']],
                ['label'=>'添加文章','url'=>['article/add']],
            ]],
            ['label'=>'用户管理','items'=>[
                ['label'=>'用户列表','url'=>['user/index']],
                ['label'=>'添加用户','url'=>['user/add']],
            ]]
        ];
        $menuItems[] = '<li>'
            . Html::beginForm(['/user/logout'], 'post')
            . Html::submitButton(
                '注销 (' . Yii::$app->user->identity->username . '用户)',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>'
            .'<li>'.
            Html::a('修改密码',['user/reset-pwd'])
            .'</li>';
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $leftMenuItems,
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
