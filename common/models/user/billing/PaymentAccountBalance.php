<?php


namespace common\models\user\billing;


use yii\db\ActiveRecord;

/**
 * @property int $payment_account_id [int(11)]
 * @property int $currency_id [int(11)]
 * @property string $value [decimal(10,2)]
 * @property Currency $currency
 */
class PaymentAccountBalance extends ActiveRecord
{
    /**
     * @return int
     */
    public function getPaymentAccountId(): int
    {
        return $this->payment_account_id;
    }

    /**
     * @param int $payment_account_id
     * @return PaymentAccountBalance
     */
    public function setPaymentAccountId(int $payment_account_id): PaymentAccountBalance
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
     * @return PaymentAccountBalance
     */
    public function setCurrencyId(int $currency_id): PaymentAccountBalance
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
     * @return PaymentAccountBalance
     */
    public function setValue(string $value): PaymentAccountBalance
    {
        $this->value = $value;
        return $this;
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id']);
    }

}