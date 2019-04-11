<?php

namespace common\models\offers;

use common\models\offers\Exception\EqualsPaymentAccount;
use common\models\offers\Exception\LowBalanceOfferException;
use common\models\offers\Exception\OfferSaveException;
use common\models\transaction\Transaction;
use common\models\user\billing\Currency;
use common\models\user\billing\PaymentAccountBalanceManager;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class OffersManager
{
    private
        $offerId,
        $offer;

    public function __construct(int $offerId)
    {
        $this->offerId = $offerId;
    }

    public static function getProvider(int $from, int $to): ?ActiveDataProvider
    {
        if (!self::checkCurrency($from) || !self::checkCurrency($to)) {
            return null;
        }

        return OffersProviderFactory::create($from, $to);
    }

    public static function getMediumPrice(int $from, int $to): string
    {
        return \Yii::$app->cache->getOrSet('medium_price_' . $from . '_' . $to, function () use ($from, $to) {
            return Offers::find()
                ->filterOnlyActive()
                ->select(new Expression('SUM(to_value/from_value)/COUNT(*) as count'))
                ->filterByCurrencyIds($from, $to)
                ->scalar();
        }, 10);
    }

    /**
     * @param int $paymentAccountId
     * @throws LowBalanceOfferException
     * @throws EqualsPaymentAccount
     */
    public function closeOffer(int $paymentAccountId, int $userId)
    {
        $this->checkBuyer($paymentAccountId);

        $transaction = \Yii::$app->db->beginTransaction();

        $owner = (new PaymentAccountBalanceManager($this->loadModel()->owner->paymentAccount->getId()));
        $buyer = (new PaymentAccountBalanceManager($paymentAccountId));

        try {
            $owner->changeBalance($this->loadModel()->getToCurrencyId(),
                $this->loadModel()->getToValue(), true,
                Transaction::REASON_OFFER, $this->offerId);

            $buyer->changeBalance($this->loadModel()->getToCurrencyId(), $this->loadModel()->getToValue(), false, Transaction::REASON_OFFER, $this->offerId);
            $buyer->changeBalance($this->loadModel()->getFromCurrencyId(), $this->loadModel()->getFromValue(), true, Transaction::REASON_OFFER, $this->offerId);

            if (!$this->loadModel()
                ->setStatus(Offers::STATUS_SUCCESS)
                ->setDateClosed(time())
                ->setBuyerUserId($userId)
                ->save()
            ) {
                throw new OfferSaveException();
            }

            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw  $e;
        }
    }

    /**
     * @param int $paymentAccountId
     * @throws LowBalanceOfferException
     * @throws EqualsPaymentAccount
     */
    private function checkBuyer(int $paymentAccountId)
    {
        if ($this->loadModel()->owner->paymentAccount->getId() === $paymentAccountId) {
            throw new EqualsPaymentAccount();
        }
        if (!(new PaymentAccountBalanceManager($paymentAccountId))
            ->checkAmount(
                $this->loadModel()->getToCurrencyId(),
                $this->loadModel()->getToValue()
            )) {
            throw new LowBalanceOfferException();
        }
    }

    private function loadModel()
    {
        if ($this->offer) {
            return $this->offer;
        }

        return $this->offer = Offers::find()
            ->filterById($this->offerId)
            ->one();
    }


    private static function checkCurrency(int $id)
    {
        return Currency::find()
            ->filterById($id)
            ->exists();
    }
}