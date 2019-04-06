<?php


namespace frontend\controllers\user\auth\vk;


use common\components\user\auth\VkService;
use yii\filters\AccessControl;
use yii\web\Controller;

class OAuthController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['auth', 'code-callback'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ]
        ];
    }

    public function actionAuth()
    {
        return $this->redirect((new VkService())->getAuthUrl());
    }

    public function actionCodeCallback($code)
    {
        (new VkService())->authUser($code);

        return $this->goHome();
    }

    public function actionAuthCallback()
    {
//        dump($_SERVER);
//        $url = explode('#', \Yii::$app->request->getAbsoluteUrl());
//
//        dump($url);exit();
    }
}