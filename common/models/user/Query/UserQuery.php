<?php

namespace common\models\user\Query;

use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{

    public function filterByVkId(int $vkId)
    {
        return $this->andWhere([
            'vk_id' => $vkId
        ]);
    }
}