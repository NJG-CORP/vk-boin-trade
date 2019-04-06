<?php

namespace common\components\base;

use yii\helpers\Json;

class BaseModelException extends \Exception
{
    public static function errors(array $errors, int $code = 0, ?\Throwable $previous = null): self
    {
        return new self(Json::encode($errors), $code, $previous);
    }

}