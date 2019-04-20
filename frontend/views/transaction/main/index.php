<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $provider
 */
try {
    $this->title = 'Транзакции';

    echo '<h1>' . $this->title . '</h1>';

    echo \yii\grid\GridView::widget([
        'dataProvider' => $provider,
        'rowOptions' => function ($model) {
            return [
                'id' => 'row-transaction-id-' . $model->getId()
            ];
        },
        'columns' => [
            [
                'label' => '#',
                'attribute' => 'id'
            ],
            [
                'label' => 'Валюта',
                'attribute' => 'currency.label'
            ],
            [
                'label' => 'Сумма',
                'content' => function ($model) {
                    /** @var \common\models\transaction\Transaction $model */
                    return '<span class="price ' . ($model->isDecrease() ? 'bad-price' : 'good-price') . '">~' .
                        Yii::$app->formatter->asDecimal($model->getValue())
                        . ($model->isDecrease() ?
                            '<i class="glyphicon glyphicon-arrow-down"></i>' :
                            '<i class="glyphicon glyphicon-arrow-up"></i>')
                        . '</span>';
                }
            ],
            [
                'label' => 'Причина',
                'content' => function ($model) {
                    /** @var \common\models\transaction\Transaction $model */
                    return \common\models\transaction\TransactionDictionary::getLabelForReason($model->getReason());
                }
            ],
            'date_created:datetime'
        ]
    ]);
} catch (Exception $e) {
    dump($e->getMessage());
}