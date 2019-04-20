<?php

namespace common\models\user\billing;

use common\components\base\BaseModelException;
use common\components\vk\Exception\CouldNotSaveException;
use common\components\vk\Exception\TransactionAlreadyExistsException;
use common\components\vk\Exception\VkCoinSendException;
use common\components\vk\VkCoinClient;
use common\components\vk\VkCoinTransaction;
use common\models\transaction\TransactionDictionary;
use common\models\user\billing\Exception\CouldNotSavePaymentAccountException;
use common\models\user\billing\Exception\LowBalanceException;
use common\models\user\billing\Exception\WrongMerchantException;
use common\models\user\Exception\UserNotFoundException;
use common\models\user\User;
use Throwable;
use Yii;

class PaymentAccountManager
{

    private $userId;
    private $user;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @throws BaseModelException
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

    /**
     * @param VkCoinTransaction $transaction
     * @return PaymentAccountManager
     * @throws UserNotFoundException
     */
    public static function createFromVkTransaction(VkCoinTransaction $transaction)
    {
        $user = User::find()->filterByVkId($transaction->getFromId())->one();

        if(!$user) {
            throw new UserNotFoundException();
        }

        return new self($user->getId());
    }

    /**
     * @param string $tx
     * @return VkCoinTransaction
     * @throws CouldNotSaveException
     * @throws Exception\CouldNotSavePaymentAccountBalanceException
     * @throws Exception\CurrencyNotFound
     * @throws TransactionAlreadyExistsException
     * @throws UserNotFoundException
     * @throws WrongMerchantException
     * @throws \yii\db\Exception
     */
    public static function upBalanceFromTransaction(string $tx): ?VkCoinTransaction
    {
        ['status' => $status, 'response' => $response] = Yii::$app->vkCoinClient->getTransactions($tx);

        if ($status && !array_key_exists('error', $response)) {
            if (is_array($response) && array_key_exists(0, $response)) {
                $dbTransaction = Yii::$app->db->beginTransaction();
                try {
                    $vkTransaction = new VkCoinTransaction();
                    $vkTransaction->load($response[0], '');

                    if (VkCoinTransaction::find()->andWhere(['id' => $vkTransaction->getId()])->exists()) {
                        throw new TransactionAlreadyExistsException($vkTransaction->getId());
                    }
                    if ($vkTransaction->getToId() !== Yii::$app->params['vk_coin']['merchant_id']) {
                        throw new WrongMerchantException($vkTransaction->getToId());
                    }
                    if(!$vkTransaction->save()) {
                        throw new CouldNotSaveException($vkTransaction->getLastError());
                    }

                    $manager = self::createFromVkTransaction($vkTransaction);

                    (new PaymentAccountBalanceManager($manager->loadUser()->paymentAccount->getId()))
                        ->changeBalance(
                            Currency::CURRENCY_VK_COIN,
                            $vkTransaction->getAmount(),
                            true,
                            TransactionDictionary::REASON_IN_OUT,
                            $vkTransaction->getId()
                        );

                    $dbTransaction->commit();

                    return $vkTransaction;
                } catch (Throwable $e) {
                    Yii::error($e->getMessage());

                    $dbTransaction->rollBack();
                    throw $e;
                }
            }
        }

        return null;
    }

    /**
     * @param string $amount
     * @throws Exception\CouldNotSavePaymentAccountBalanceException
     * @throws Exception\CurrencyNotFound
     * @throws LowBalanceException
     * @throws VkCoinSendException
     * @throws \yii\db\Exception
     */
    public function vkCoinOut(string $amount)
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $paymentAccountBalanceManager = new PaymentAccountBalanceManager($this->loadUser()->paymentAccount->getId());

            if(!$paymentAccountBalanceManager->checkAmount(Currency::CURRENCY_VK_COIN, $amount)) {
                throw new LowBalanceException();
            }

            $paymentAccountBalanceManager
                ->changeBalance(
                    Currency::CURRENCY_VK_COIN,
                    $amount,
                    false,
                    TransactionDictionary::REASON_IN_OUT,
                    0
                );
            ['status' => $status, 'response' => $response] = Yii::$app->vkCoinClient->sendTransfer($this->loadUser()->getVkId(), $amount * 1000);

            if (!$status || array_key_exists('error', $response)) {
                throw new VkCoinSendException();
            }
            $dbTransaction->commit();
        } catch (Throwable $e) {
            Yii::error($e->getMessage());

            $dbTransaction->rollBack();
            throw $e;
        }
    }
    private function loadUser(): ?User
    {
        if ($this->user) {
            return $this->user;
        }
        return $this->user = User::findOne($this->userId);
    }

}