<?php


namespace frontend\controllers\user\auth;


use common\models\user\User;
use yii\web\Controller;

class LoginController extends Controller
{

    public function actionAs($user_id)
    {
        \Yii::$app->user->login(User::findOne($user_id));
    }

}