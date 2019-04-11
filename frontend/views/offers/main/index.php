<?php
/**
 * @var \common\models\offers\OffersProvider $provider
 * @var string $mediumPrice
 * @var \yii\db\ActiveRecord $searchModel
 */

$model = $provider->getModels()[0];
/** @var \common\models\offers\Offers $model */
$mediumLabel = 'Цена за 1 ' . $model->fromCurrency->getLabel() . '(' . $model->toCurrency->getLabel() . ')';
$fromLabel = $model->fromCurrency->getLabel();
$toLabel = $model->toCurrency->getLabel();

try {
    echo \yii\grid\GridView::widget([
        'dataProvider' => $provider,
        'rowOptions' => function ($model) {
            return [
                'id' => 'row-offer-id-' . $model->getId()
            ];
        },
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
                'attribute' => 'from_value',
                'value' => function ($model) {
                    /** @var \common\models\offers\Offers $model */
                    return $model->getFromValue();
                }
            ],
            [
                'label' => 'Спрос (' . $toLabel . ')',
                'attribute' => 'to_value',
                'value' => function ($model) {
                    /** @var \common\models\offers\Offers $model */
                    return $model->getToValue();
                }
            ],
            [
                'label' => $mediumLabel,
                'attribute' => 'price',
                'value' => function ($model) use ($mediumPrice) {
                    /** @var \common\models\offers\Offers $model */
                    $price = $model->getToValue() / $model->getFromValue();
                    return '<span class="price ' . ($price > $mediumPrice ? 'bad-price' : 'good-price') . '">~' . Yii::$app->formatter->asDecimal($price)
                        . ($price > $mediumPrice ? '<i class="glyphicon glyphicon-arrow-down"></i>' : '<i class="glyphicon glyphicon-arrow-up"></i>')
                        . '</span>
                            <span class="show-tooltip" data-toggle="tooltip" data-placement="top" title="По сравнению со средней ценой (' . $mediumPrice . ')"><i class="glyphicon glyphicon-question-sign"></i></span>';
                },
                'format' => 'raw'
            ],
            'date_created:datetime',
            [
                'label' => 'Операции',
                'content' => function ($model) {
                    /** @var \common\models\offers\Offers $model */
                    return $model->getOwnerUserId() === Yii::$app->user->getId() ? '' : \yii\helpers\Html::a('Купить', [
                        '/offers/manage/buy',
                        'offerId' => $model->getId()
                    ], [
                        'class' => 'ajax-send confirm-first',
                        'data-offer-id' => $model->getId(),
                        'data-method' => 'POST'
                    ]);
                }
            ]
        ]
    ]);
} catch (Exception $e) {
    echo 'Error';
    echo $e->getMessage();
}
?>