<?php

namespace common\models\user\billing;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id [int(10) unsigned]
 * @property int $user_id [int(11)]
 * @property int $date_created [int(11)]
 * @property int $date_updated [int(11)]
 * @property int $date_deleted [int(11)]
 * @property PaymentAccountBalance[] $balances
 */
class PaymentAccount extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => 'date_updated',
                'createdAtAttribute' => 'date_created',
            ]
        ];
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
     * @return PaymentAccount
     */
    public function setId(int $id): PaymentAccount
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return PaymentAccount
     */
    public function setUserId(int $user_id): PaymentAccount
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateDeleted(): int
    {
        return $this->date_deleted;
    }

    /**
     * @param int $date_deleted
     * @return PaymentAccount
     */
    public function setDateDeleted(int $date_deleted): PaymentAccount
    {
        $this->date_deleted = $date_deleted;
        return $this;
    }

    public function getBalances()
    {
        return $this->hasMany(PaymentAccountBalance::class, ['payment_account_id' => 'id']);
    }
}