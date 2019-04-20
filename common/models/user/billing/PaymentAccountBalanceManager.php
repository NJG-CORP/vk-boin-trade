<?php


namespace common\models\user\billing;


use common\models\transaction\Transaction;
use common\models\transaction\TransactionDictionary;
use common\models\user\billing\Exception\CouldNotSavePaymentAccountBalanceException;
use common\models\user\billing\Exception\CurrencyNotFound;

class PaymentAccountBalanceManager
{
    private const
        DEFAULT_VALUE = 0;

    private $paymentAccountId;

    public function __construct(int $paymentAccountId)
    {
        $this->paymentAccountId = $paymentAccountId;
    }

    public function getBalance($currencyId)
    {
        return PaymentAccountBalance::find()
            ->select('value')
            ->filterByPaymentAccountId($this->paymentAccountId)
            ->filterByCurrencyId($currencyId)
            ->scalar();
    }

    public function checkAmount(int $currencyId, string $value): bool
    {
        $amount = PaymentAccountBalance::find()
            ->select('value')
            ->filterByCurrencyId($currencyId)
            ->filterByPaymentAccountId($this->paymentAccountId)
            ->scalar();

        return $amount >= $value;
    }

    public function changeBalance(int $currencyId, string $value, bool $isPositive = true, int $reason, int $objectId)
    {
        $balance = PaymentAccountBalance::find()
            ->filterByPaymentAccountId($this->paymentAccountId)
            ->filterByCurrencyId($currencyId)
            ->one();

        if (!$balance) {
            throw new CurrencyNotFound();
        }

        $balance->setValue($balance->getValue() + ($isPositive ? $value : -$value));

        if (!$balance->save()) {
            throw new CouldNotSavePaymentAccountBalanceException($balance->getFirstError());
        }

        $transaction = new Transaction();

        $transaction
            ->setValue($value)
            ->setCurrencyId($currencyId)
            ->setPaymentAccountId($this->paymentAccountId)
            ->setReason($reason)
            ->setObjectId($objectId)
            ->setType($isPositive ? TransactionDictionary::TYPE_INCREASE : TransactionDictionary::TYPE_DECREASE)
            ->save();
    }

    /**
     * @throws \common\components\base\BaseModelException
     */
    public function createCurrencies()
    {
        /** @var Currency[] $currencies */
        $currencies = Currency::find()->all();

        foreach ($currencies as $currency) {
            $balance = new PaymentAccountBalance();
            $balance->setValue(self::DEFAULT_VALUE);
            $balance->setCurrencyId($currency->getId());
            $balance->setPaymentAccountId($this->paymentAccountId);
            if (!$balance->save()) {
                throw CouldNotSavePaymentAccountBalanceException::errors($balance->getErrors());
            }
        }
    }

}