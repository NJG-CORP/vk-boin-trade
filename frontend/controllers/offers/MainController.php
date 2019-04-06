<?php


namespace frontend\controllers\offers;


use common\models\offers\OffersManager;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MainController extends Controller
{
    public function actionIndex(int $from, int $to)
    {
        $provider = (new OffersManager())->getProvider($from, $to);

        if (!$provider) {
            throw new NotFoundHttpException();
        }

        return $this->render('index', [
            'provider' => $provider
        ]);
    }
}