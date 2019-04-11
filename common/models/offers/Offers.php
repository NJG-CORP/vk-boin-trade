<?php

namespace common\models\offers;

use common\models\offers\Query\OffersQuery;
use common\models\user\billing\Currency;
use common\models\user\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id [bigint(20) unsigned]
 * @property int $from_currency_id [int(11)]
 * @property int $to_currency_id [int(10) unsigned]
 * @property string $from_value [decimal(10,2)]
 * @property string $to_value [decimal(10,2)]
 * @property int $date_created [int(11)]
 * @property int $date_updated [int(11)]
 * @property int $date_closed [int(11)]
 * @property int $status [tinyint(4)]
 * @property int $owner_user_id [int(11)]
 * @property User $owner
 * @property Currency $fromCurrency
 * @property Currency $toCurrency
 * @property int $buyer_user_id [int(11)]
 */
class Offers extends ActiveRecord
{
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getDateClosed(): int
    {
        return $this->date_closed;
    }

    /**
     * @param int $date_closed
     * @return Offers
     */
    public function setDateClosed(int $date_closed): Offers
    {
        $this->date_closed = $date_closed;
        return $this;
    }

    /**
     * @return int
     */
    public function getBuyerUserId(): int
    {
        return $this->buyer_user_id;
    }

    /**
     * @param int $buyer_user_id
     * @return Offers
     */
    public function setBuyerUserId(int $buyer_user_id): Offers
    {
        $this->buyer_user_id = $buyer_user_id;
        return $this;
    }

    public const
        STATUS_NEW = 1,
        STATUS_SUCCESS = 2,
        STATUS_REMOVED = 3;

    public static function find()
    {
        return new OffersQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_created',
                'updatedAtAttribute' => 'date_updated'
            ]
        ];
    }

    /**
     * @return int
     */
    public function getOwnerUserId(): int
    {
        return $this->owner_user_id;
    }

    /**
     * @param int $owner_user_id
     * @return Offers
     */
    public function setOwnerUserId(int $owner_user_id): Offers
    {
        $this->owner_user_id = $owner_user_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getFromCurrencyId(): int
    {
        return $this->from_currency_id;
    }

    /**
     * @param int $from_currency_id
     * @return Offers
     */
    public function setFromCurrencyId(int $from_currency_id): Offers
    {
        $this->from_currency_id = $from_currency_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getToCurrencyId(): int
    {
        return $this->to_currency_id;
    }

    /**
     * @param int $to_currency_id
     * @return Offers
     */
    public function setToCurrencyId(int $to_currency_id): Offers
    {
        $this->to_currency_id = $to_currency_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFromValue(): string
    {
        return $this->from_value;
    }

    /**
     * @param string $from_value
     * @return Offers
     */
    public function setFromValue(string $from_value): Offers
    {
        $this->from_value = $from_value;
        return $this;
    }

    /**
     * @return string
     */
    public function getToValue(): string
    {
        return $this->to_value;
    }

    /**
     * @param string $to_value
     * @return Offers
     */
    public function setToValue(string $to_value): Offers
    {
        $this->to_value = $to_value;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Offers
     */
    public function setStatus(int $status): Offers
    {
        $this->status = $status;
        return $this;
    }

    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_user_id']);
    }

    public function getFromCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'from_currency_id']);
    }

    public function getToCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'to_currency_id']);
    }

}