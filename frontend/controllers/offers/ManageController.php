<?php


namespace frontend\controllers\offers;


use common\models\offers\Exception\EqualsPaymentAccount;
use common\models\user\billing\Exception\LowBalanceException;
use common\models\offers\OffersManager;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class ManageController extends Controller
{
    public function actionBuy()
    {
        ['offerId' => $offerId] = \Yii::$app->request->getQueryParams();

        if (!$offerId) {
            throw new BadRequestHttpException();
        }

        try {
            (new OffersManager($offerId))->closeOffer(\Yii::$app->user->identity->paymentAccount->getId(), \Yii::$app->user->getId());
        } catch (LowBalanceException | EqualsPaymentAccount $e) {
            throw new ForbiddenHttpException();
        }

        return 1;
    }

    public function actionCreate()
    {
        $model = OffersManager::create(
            \Yii::$app->request->getBodyParams(),
            \Yii::$app->user->getId()
        );

        return $this->render('../main/_form', [
            'fromCurrencyId' => $model->getFromCurrencyId(),
            'toCurrencyId' => $model->getToCurrencyId(),
            'model' => $model
        ]);
    }
}