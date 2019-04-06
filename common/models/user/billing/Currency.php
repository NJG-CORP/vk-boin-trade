<?php

namespace common\models\user\billing;

use common\models\user\billing\Query\CurrencyQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id [int(11)]
 * @property string $label [varchar(64)]
 */
class Currency extends ActiveRecord
{

    public static function find()
    {
        return new CurrencyQuery(get_called_class());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Currency
     */
    public function setId(int $id): Currency
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Currency
     */
    public function setLabel(string $label): Currency
    {
        $this->label = $label;
        return $this;
    }

}