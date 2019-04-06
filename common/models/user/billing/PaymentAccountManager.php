<?php

namespace common\models\user\billing;

use common\models\user\billing\Exception\CouldNotSavePaymentAccountException;

class PaymentAccountManager
{

    private $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @throws \common\components\base\BaseModelException
     */
    public function create()
    {
        $paymentAccount = new PaymentAccount();

        $paymentAccount->setUserId($this->userId);
        if (!$paymentAccount->save()) {
            throw CouldNotSavePaymentAccountException::errors($paymentAccount->getErrors());
        }

        (new PaymentAccountBalanceManager($paymentAccount->getId()))->createCurrencies();
    }

}