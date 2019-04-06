<?php
/**
 * @var \common\models\offers\OffersProvider $provider
 * @var float $mediumPrice
 */

$mediumPrice = 1.22;

$model = $provider->getModels()[0];
/** @var \common\models\offers\Offers $model */
$mediumLabel = 'Цена за 1 ' . $model->fromCurrency->getLabel() . '(' . $model->toCurrency->getLabel() . ')';
$fromLabel = $model->fromCurrency->getLabel();
$toLabel = $model->toCurrency->getLabel();

try {
    echo \yii\grid\GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            [
                'label' => '#',
                'attribute' => 'id'
            ],
            [
                'label' => 'Кто',
                'value' => function ($model) {
                    /** @var \common\models\offers\Offers $model */
                    return \yii\helpers\Html::a($model->owner->getUsername(), $model->owner->getVkUrl(), ['target' => '_blank']);
                },
                'format' => 'raw'
            ],
            [
                'label' => 'Предложение (' . $fromLabel . ')',
                'attribute' => 'from_currency_value',
                'value' => function ($model) {
                    /** @var \common\models\offers\Offers $model */
                    return $model->getFromValue();
                }
            ],
            [
                'label' => 'Спрос (' . $toLabel . ')',
                'attribute' => 'from_currency_value',
                'value' => function ($model) {
                    /** @var \common\models\offers\Offers $model */
                    return $model->getToValue();
                }
            ],
            [
                'label' => $mediumLabel,
                'value' => function ($model) use ($mediumPrice) {
                    /** @var \common\models\offers\Offers $model */
                    $price = $model->getToValue() / $model->getFromValue();
                    return '<span class="price ' . ($price > $mediumPrice ? 'bad-price' : 'good-price') . '">' . Yii::$app->formatter->asDecimal($price)
                        . ($price > $mediumPrice ? '<i class="glyphicon glyphicon-arrow-down"></i>' : '<i class="glyphicon glyphicon-arrow-up"></i>')
                        . '</span>
                            <span class="show-tooltip" data-toggle="tooltip" data-placement="top" title="По сравнению со средней ценой (' . $mediumPrice . ')"><i class="glyphicon glyphicon-question-sign"></i></span>';
                },
                'format' => 'raw'
            ],
            'date_created:datetime'
        ]
    ]);
} catch (Exception $e) {
    echo 'Error';
    echo $e->getMessage();
}
?>