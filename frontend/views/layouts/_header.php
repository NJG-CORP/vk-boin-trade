
<?php

use common\components\user\auth\VkService;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

NavBar::begin([
    'brandLabel' => 'VK Coin Trade',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
$menuItems = [
    ['label' => 'Купить', 'url' => ['/offers/main/index', 'from' => 1, 'to' => 2]],
    ['label' => 'Продать', 'url' => ['/offers/main/index', 'from' => 2, 'to' => 1]],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Login', 'url' => (new VkService())->getAuthUrl()];
} else {
    $menuItems[] = ['label' => 'Пополнить', 'url' => ['/payment/in/index']];
    $menuItems[] = ['label' => 'Вывести', 'url' => ['/payment/out/index']];
    $menuItems[] = ['label' => 'История', 'url' => ['/transaction/main/index']];
    $menuItems[] = ['label' => Yii::$app->user->identity->getUsername() . ' ' . $this->render('_balance_label', [
            'account' => Yii::$app->user->identity->paymentAccount
        ]), 'encode' => false];
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Выйти',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();
?>