<?php

namespace common\models\offers;

use common\models\offers\Exception\EqualsPaymentAccount;
use common\models\user\billing\Exception\LowBalanceException;
use common\models\offers\Exception\OfferSaveException;
use common\models\transaction\Transaction;
use common\models\transaction\TransactionDictionary;
use common\models\user\billing\Currency;
use common\models\user\billing\PaymentAccountBalanceManager;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class OffersManager
{
    private
        $offerId,
        $offer;

    public static function create(array $form, int $owner_id): Offers
    {
        $model = new Offers();

        $model->load($form);

        $model->setStatus(Offers::STATUS_NEW);
        $model->setOwnerUserId($owner_id);

        $transaction = \Yii::$app->db->beginTransaction();

        if ($model->save()) {
            try {
                (new PaymentAccountBalanceManager($owner_id))
                    ->changeBalance(
                        $model->getFromCurrencyId(),
                        $model->getFromValue(),
                        false,
                        TransactionDictionary::REASON_OFFER_CREATED,
                        $model->getId()
                    );

                $transaction->commit();
                $model->setToValue(0);
                $model->setFromValue(0);

            } catch (\Exception $exception) {
                $transaction->rollBack();
            }
        }

        return $model;
    }

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
                    ->scalar() ?? 0;
        }, 10);
    }

    /**
     * @param int $paymentAccountId
     * @throws LowBalanceException
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
                TransactionDictionary::REASON_OFFER, $this->offerId);

            $buyer->changeBalance($this->loadModel()->getToCurrencyId(), $this->loadModel()->getToValue(), false, TransactionDictionary::REASON_OFFER, $this->offerId);
            $buyer->changeBalance($this->loadModel()->getFromCurrencyId(), $this->loadModel()->getFromValue(), true, TransactionDictionary::REASON_OFFER, $this->offerId);

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
     * @throws LowBalanceException
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
            throw new LowBalanceException();
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