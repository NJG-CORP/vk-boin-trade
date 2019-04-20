<?php
/**
 * @var \common\models\user\billing\PaymentAccount $account
 */

$balances = $account->balances;
$labels = [];
foreach ($balances as $balance) {
    $labels[] = $balance->currency->getLabel() . ': ' . Yii::$app->formatter->asDecimal($balance->getValue());
}

?>
<span>(<?= implode(', ', $labels) ?>)</span>
