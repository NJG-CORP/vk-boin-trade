<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'timeZone' => 'UTC',
            'dateFormat' => 'dd.MM.Y',
            'timeFormat' => 'HH:mm:ss',
            'datetimeFormat' => 'dd.MM.Y HH:mm',
            'decimalSeparator' => '.',
            'thousandSeparator' => ' ',
            'nullDisplay' => '-',
            'currencyCode' => '₴'   // <-- ---- ----
        ],
        'vkCoinClient' => [
            'class' => \common\components\vk\VkCoinClient::class,
            'apikey' => '6Y8_KF,wSQ*[Sr;&F;.rBj-z*8mz[NxYgwNt,yBZ!.&;O5Ym[y',
            'merchant_id' => 185036613
        ]
    ],
];
