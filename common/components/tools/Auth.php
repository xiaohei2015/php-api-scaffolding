<?php

namespace common\components\tools;

use Yii;
use yii\base\Component;


/**
 * Auth
 */
class Auth extends AuthMethod
{
    /**
     * @var Component the owner of this behavior
     */
    public $owner;

    //auth fields
    public $_token;

    public function init()
    {
        parent::init();
        $this->_token = \common\components\tools\TokenFilter::getAccessToken();
        //$this->_auth();
    }

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = $this->_token;
        if ($accessToken&&is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
            return null;
        }
        /*if ($accessToken !== null) {
            $this->handleFailure($response);
        }*/

        return null;
    }
}
