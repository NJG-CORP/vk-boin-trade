<?php


namespace common\models\transaction;


class TransactionDictionary
{

    private const
        REASON_LABEL =
        [
            self::REASON_IN_OUT => 'Ввод\\Вывод',
            self::REASON_OFFER => 'Исполнение оффера',
            self::REASON_OFFER_CREATED => 'Создание оффера'
        ];

    public const REASON_IN_OUT = 0;
    public const TYPE_INCREASE = 0;
    public const REASON_OFFER = 1;
    public const TYPE_DECREASE = 1;
    public const REASON_OFFER_CREATED = 2;

    public static function getLabelForReason(int $reason)
    {
        return self::REASON_LABEL[$reason];
    }
}