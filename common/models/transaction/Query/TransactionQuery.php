<?php

namespace common\models\transaction\Query;

use common\models\transaction\TransactionDictionary;
use yii\db\ActiveQuery;

class TransactionQuery extends ActiveQuery
{
    public function filterByExternal()
    {
        return $this->andWhere([
            'reason' => TransactionDictionary::REASON_IN_OUT
        ]);
    }

    public function filterByPaymentAccountId(int $paymentAccountId)
    {
        return $this->andWhere([
            'payment_account_id' => $paymentAccountId
        ]);
    }

}