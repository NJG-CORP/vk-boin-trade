<?php

namespace common\models\offers\Query;

use common\models\offers\Offers;
use yii\db\ActiveQuery;

class OffersQuery extends ActiveQuery
{
    public function filterByIds(int $from, int $to)
    {
        return $this->andWhere([
            'from_currency_id' => $from,
            'to_currency_id' => $to
        ]);
    }

    public function filterOnlyActive()
    {
        return $this->andWhere([
            'status' => Offers::STATUS_NEW
        ]);
    }

}