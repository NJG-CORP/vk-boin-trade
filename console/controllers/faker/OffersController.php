<?php


namespace console\controllers\faker;


use common\models\offers\Offers;
use Faker\Factory;
use yii\console\Controller;

class OffersController extends Controller
{

    public function actionFake()
    {
        $faker = Factory::create();

        for ($i = 0; $i < 1000000; $i++) {
            $offer = new Offers();
            $from = $faker->numberBetween(1, 2);
            $to = $from === 1 ? 2 : 1;

            $offer->setStatus(Offers::STATUS_NEW);
            $offer->setFromCurrencyId($from);
            $offer->setToCurrencyId($to);
            $offer->setOwnerUserId(1);
            $offer->setFromValue($faker->randomFloat(2, 1, 10000000));
            $offer->setToValue($faker->randomFloat(2, 1, 10000000));
            $offer->detachBehavior('timestamp');
            $offer->date_created = $faker->dateTimeBetween('-10 days');
            $offer->date_updated = $offer->date_created;
            $offer->save();
        }
    }

}