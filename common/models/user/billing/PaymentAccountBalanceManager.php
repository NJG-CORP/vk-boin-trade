<?php


namespace common\models\user\billing;


use common\models\user\billing\Exception\CouldNotSavePaymentAccountBalanceException;

class PaymentAccountBalanceManager
{
    private const
        DEFAULT_VALUE = 0;

    private $paymentAccountId;

    public function __construct(int $paymentAccountId)
    {
        $this->paymentAccountId = $paymentAccountId;
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