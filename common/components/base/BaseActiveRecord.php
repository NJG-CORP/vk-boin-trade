<?php


namespace common\components\base;


use yii\db\ActiveRecord;

class BaseActiveRecord extends ActiveRecord
{

    public function getLastError(): string
    {
        return current($this->getFirstErrors());
    }
}