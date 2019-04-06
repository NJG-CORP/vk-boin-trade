<?php


namespace common\models\offers;


class OffersProviderFactory
{
    public static function create(int $from, int $to)
    {
        return new OffersProvider([
            'query' => Offers::find()
                ->filterByIds($from, $to)
                ->filterOnlyActive()
        ]);
    }

}