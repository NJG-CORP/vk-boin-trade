<?php


namespace common\models\offers;


use yii\data\Pagination;
use yii\data\Sort;

class OffersProviderFactory
{
    public static function create(int $from, int $to)
    {
        return new OffersProvider([
            'query' => Offers::find()
                ->filterByCurrencyIds($from, $to)
                ->with(['owner', 'fromCurrency', 'toCurrency'])
                ->filterOnlyActive(),
            'sort' => new Sort([
                'attributes' => [
                    'from_value',
                    'to_value',
                    'price' => [
                        'asc' => ['(from_value/to_value)' => SORT_ASC],
                        'desc' => ['(from_value/to_value)' => SORT_DESC],
                        'default' => SORT_DESC,
                    ]
                ]
            ]),
            'totalCount' => \Yii::$app->cache->getOrSet('offers_total_count', function () use ($from, $to) {
                return Offers::find()
                    ->filterByCurrencyIds($from, $to)
                    ->filterOnlyActive()
                    ->count();
            }, 100),
            'pagination' => new Pagination([
                'pageSizeLimit' => [1, 100],
                'pageSize' => 50
            ])
        ]);
    }

}