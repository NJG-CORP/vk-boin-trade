<?php
/**
 * @var \yii\web\View $this
 */

use yii\helpers\Html; ?>

<form action="<?= \yii\helpers\Url::toRoute('/payment/out/vk-coin-out') ?>" method="post">
    <?= Html::hiddenInput(\Yii::$app->getRequest()->csrfParam, \Yii::$app->getRequest()->getCsrfToken(), []); ?>
    <input type="text" name="amount">
    <button type="submit" class="btn btn-success">
        Отправить
    </button>
</form>
