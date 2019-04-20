<?php
/**
 * @var \yii\web\View $this
 */

$this->title = 'Пополнение баланса';
?>

<h1><?= $this->title ?></h1>

<?= \yii\helpers\Html::a(
    'Пополнить VK-COIN',
    Yii::$app->vkCoinClient->generatePayLink(10, 0, false),
    ['class' => 'btn btn-success']) ?>
<p>
    Если вы с телефона, то перейдите с мобильного по ссылке:
    <input type="text" id="vk-coin-payment-link" value="<?= Yii::$app->vkCoinClient->generatePayLink(0, 0, false) ?>">
    <button class="btn btn-info" onclick="copyText('vk-coin-payment-link')">
        Скопировать
    </button>
</p>

<?= $this->render('_check_transaction_form') ?>

<script>

</script>