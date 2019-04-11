<?php

namespace common\models\user\billing\Query;

use yii\db\ActiveQuery;

class PaymentAccountBalanceQuery extends ActiveQuery
{

    public function filterByPaymentAccountId(int $paymentAccountId)
    {
        return $this->andWhere([
            'payment_account_id' => $paymentAccountId
        ]);
    }

    public function filterByCurrencyId(int $currencyId)
    {
        return $this->andWhere([
            'currency_id' => $currencyId
        ]);
    }
}