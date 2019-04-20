<?php

namespace common\models\transaction;

use common\models\transaction\Query\TransactionQuery;
use common\models\user\billing\Currency;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id [bigint(20) unsigned]
 * @property int $currency_id [int(11)]
 * @property string $value [decimal(10,2)]
 * @property int $type [tinyint(4)]
 * @property int $reason [tinyint(4)]
 * @property int $date_created [int(11)]
 * @property int $date_updated [int(11)]
 * @property int $payment_account_id [int(11)]
 * @property int $object_id [int(10) unsigned]
 * @property Currency $currency
 */
class Transaction extends ActiveRecord
{
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public static function find()
    {
        return new TransactionQuery(get_called_class());
    }

    public function isDecrease()
    {
        return $this->type === TransactionDictionary::TYPE_DECREASE;
    }

    /**
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->object_id;
    }

    /**
     * @param int $object_id
     * @return Transaction
     */
    public function setObjectId(int $object_id): Transaction
    {
        $this->object_id = $object_id;
        return $this;
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => 'date_updated',
                'createdAtAttribute' => 'date_created'
            ]
        ];
    }

    /**
     * @return int
     */
    public function getPaymentAccountId(): int
    {
        return $this->payment_account_id;
    }

    /**
     * @param int $payment_account_id
     * @return Transaction
     */
    public function setPaymentAccountId(int $payment_account_id): Transaction
    {
        $this->payment_account_id = $payment_account_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrencyId(): int
    {
        return $this->currency_id;
    }

    /**
     * @param int $currency_id
     * @return Transaction
     */
    public function setCurrencyId(int $currency_id): Transaction
    {
        $this->currency_id = $currency_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Transaction
     */
    public function setValue(string $value): Transaction
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return Transaction
     */
    public function setType(int $type): Transaction
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getReason(): int
    {
        return $this->reason;
    }

    /**
     * @param int $reason
     * @return Transaction
     */
    public function setReason(int $reason): Transaction
    {
        $this->reason = $reason;
        return $this;
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id']);
    }
}