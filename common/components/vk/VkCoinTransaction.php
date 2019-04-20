<?php

namespace common\components\vk;

use common\components\base\BaseActiveRecord;
use yii\db\ActiveRecord;

/**
 * @property int $id [int(11)]
 * @property int $from_id [int(11)]
 * @property int $to_id [int(11)]
 * @property string $amount [varchar(128)]
 * @property int $type [int(11)]
 * @property string $payload [varchar(40)]
 * @property int $external_id [int(11)]
 * @property int $created_at [int(11)]
 */
class VkCoinTransaction extends BaseActiveRecord
{
    public function rules()
    {
        return [
            [['id','created_at','from_id', 'to_id', 'type', 'external_id', 'payload'],'integer'],
            [['amount'], 'string']
        ];
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFromId()
    {
        return $this->from_id;
    }

    /**
     * @return mixed
     */
    public function getToId()
    {
        return $this->to_id;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount / 1000;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return mixed
     */
    public function getExternalId()
    {
        return $this->external_id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}