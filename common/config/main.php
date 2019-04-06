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
        ]
    ],
];
