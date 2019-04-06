<?php

namespace common\models\user;

use common\models\user\billing\PaymentAccountManager;
use common\models\user\Exception\CouldNotSaveUserException;
use Yii;

class UserManager
{

    /**
     * @param string $email
     * @param int $vkId
     * @param string $username
     * @return User
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public static function createFromVk(string $email, int $vkId, string $username): User
    {
        $user = User::find()
            ->filterByVkId($vkId)
            ->one();

        if ($user) {
            return $user;
        }
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = new User();

            $model->setEmail($email);
            $model->setUsername($username);
            $model->setVkId($vkId);
            $model->generateAuthKey();
            $model->setStatus(User::STATUS_ACTIVE);

            if (!$model->save()) {
                throw CouldNotSaveUserException::errors($model->getErrors());
            }

            (new PaymentAccountManager($model->getId()))->create();
        } catch (\Exception | \Throwable $exception) {
            $transaction->rollBack();

            throw $exception;
        }

        $transaction->commit();

        return $model;
    }
}