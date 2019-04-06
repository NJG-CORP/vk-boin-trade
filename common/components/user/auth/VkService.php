<?php

namespace common\components\user\auth;

use common\components\user\auth\Exception\CouldNotGetVkUser;
use common\models\user\UserManager;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;
use yii\base\Component;

/**
 * @property VKOAuth $OAuth
 */
class VkService extends Component
{
    private
        $code,
        $OAuth;

    public function init()
    {
        parent::init();
        $this->OAuth = new VKOAuth();
    }

    public function getAuthUrl()
    {
        $oauth = new VKOAuth();
        $client_id = \Yii::$app->params['vk']['id'];
        $redirect_uri = \Yii::$app->params['vk']['return_url']['code'];
        $display = VKOAuthDisplay::PAGE;
        $scope = array(VKOAuthUserScope::EMAIL);
        $state = \Yii::$app->params['vk']['secret'];

        return $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state);
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string $code
     * @return mixed
     * @throws CouldNotGetVkUser
     * @throws \Throwable
     */
    public function authUser(string $code)
    {
        $client_id = \Yii::$app->params['vk']['id'];
        $redirect_uri = \Yii::$app->params['vk']['return_url']['code'];
        $state = \Yii::$app->params['vk']['secret'];

        try {
            $response = $this->OAuth->getAccessToken($client_id, $state, $redirect_uri, $code);

            $token = $response['access_token'];

            $api = new VKApiClient();
            $userInfo = $api->users()->get($token)[0];

            $user = UserManager::createFromVk($response['email'], $response['user_id'], ($userInfo['last_name'] . ' ' . $userInfo['first_name']));

            \Yii::$app->user->login($user);
        } catch (\Exception $exception) {
            throw new CouldNotGetVkUser($exception, $exception->getCode(), $exception);
        }

        return $user;
    }
}
