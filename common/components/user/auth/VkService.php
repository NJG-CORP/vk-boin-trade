<?php

namespace common\components\user\auth;

class VkService
{
    private
        $code;

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function checkCode()
    {

    }
}
