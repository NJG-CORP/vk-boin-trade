<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\offers\OffersProvider $provider
 * @var string $mediumPrice
 * @var \yii\db\ActiveRecord $searchModel
 */

$model = $provider->getModels()[0];
/** @var \common\models\offers\Offers $model */
$mediumLabel = 'Цена';
?>

<a class="btn btn-success" data-toggle="collapse" href="#collapseForm">
    Поменять <?= $fromLabel ?> на <?= $toLabel ?>
</a>
<?php

try {
    echo $this->render('_form', [
        'toCurrencyId' => $toCurrencyId,
        'fromCurrencyId' => $fromCurrencyId,
        'model' => null
    ]);
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
                        'class' => 'ajax-send confirm-first' . (Yii::$app->user->isGuest ? ' need-auth' : ''),
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
