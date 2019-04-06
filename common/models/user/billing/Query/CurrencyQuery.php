<?php


namespace common\models\user\billing\Query;


use yii\db\ActiveQuery;

class CurrencyQuery extends ActiveQuery
{
    public function filterById(int $id)
    {
        return $this->andWhere([
            'id' => $id
        ]);
    }

}