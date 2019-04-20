<?php


namespace frontend\controllers\payment;


use common\components\vk\Exception\VkCoinSendException;
use common\models\user\billing\Exception\CouldNotSavePaymentAccountBalanceException;
use common\models\user\billing\Exception\CurrencyNotFound;
use common\models\user\billing\Exception\LowBalanceException;
use common\models\user\billing\PaymentAccountManager;
use Yii;
use yii\db\Exception;
use yii\web\Controller;

class OutController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionVkCoinOut()
    {
        ['amount' => $amount] = Yii::$app->request->getBodyParams();
        try {
            (new PaymentAccountManager(Yii::$app->user->getId()))->vkCoinOut($amount);
            Yii::$app->session->setFlash('success', 'Вывод успешно совершен, Вам было перечислено: ' . $amount . ' VK Coin');
        } catch (VkCoinSendException $e) {
            Yii::$app->session->setFlash('error', 'Ошибка на сервере VK');
        } catch (CouldNotSavePaymentAccountBalanceException $e) {
        } catch (CurrencyNotFound $e) {
        } catch (LowBalanceException $e) {
            Yii::$app->session->setFlash('error', 'Недостаточно средств на балансе');
        } catch (Exception $e) {
        }

        return $this->redirect(Yii::$app->request->getReferrer());
    }
}