<?php


namespace common\models\offers;


use common\models\user\billing\Currency;
use yii\data\ActiveDataProvider;

class OffersManager
{

    public function getProvider(int $from, int $to): ?ActiveDataProvider
    {
        if (!$this->checkCurrency($from) || !$this->checkCurrency($to)) {
            return null;
        }

        return OffersProviderFactory::create($from, $to);
    }

    private function checkCurrency(int $id)
    {
        return Currency::find()
            ->filterById($id)
            ->exists();
    }
}