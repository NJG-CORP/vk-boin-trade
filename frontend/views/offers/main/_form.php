<?php
/**
 * @var int $fromCurrencyId
 * @var int $toCurrencyId
 * @var Offers|null $model
 */

use common\models\offers\Offers;
use common\models\user\billing\PaymentAccountBalanceManager;
use yii\widgets\ActiveForm;

if (!$model) {
    $model = new Offers();

    $model->setToCurrencyId($toCurrencyId);
    $model->setFromCurrencyId($fromCurrencyId);
}

$maxFrom = Yii::$app->user->isGuest ? 0 : (new PaymentAccountBalanceManager(Yii::$app->user->getId()))->getBalance($fromCurrencyId);

?>
<div class="collapse" id="collapseForm">
    <?php \yii\widgets\Pjax::begin(['enablePushState' => false]); ?>
    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['data-pjax' => true], 'action' => \yii\helpers\Url::toRoute('/create-offer')]) ?>

    <?= $form->field($model, 'from_currency_id')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'to_currency_id')->hiddenInput()->label(false); ?>

    <?= $form->field($model, 'from_value')->input('number', [
        'min' => 1,
        'max' => $maxFrom,
        'step' => '0.01'
    ])->label('Я предлагаю (до ' . Yii::$app->formatter->asDecimal($maxFrom) . ')'); ?>

    <?= $form->field($model, 'to_value')->input('number', [
        'min' => 1,
        'step' => '0.01'
    ])->label('Я хочу'); ?>


    <?=
    \yii\helpers\Html::submitButton('Создать', ['class' => 'btn btn-success']);
    ?>
    <?php ActiveForm::end(); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>