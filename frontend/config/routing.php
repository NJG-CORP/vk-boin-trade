<?php
return [
    [
        'pattern' => 'coin-to-pay',
        'route' => 'offers/main/index',
        'defaults' => ['from' => 1, 'to' => 2],
    ],
    [
        'pattern' => 'pay-to-coin',
        'route' => 'offers/main/index',
        'defaults' => ['from' => 2, 'to' => 1],
    ],
    [
        'pattern' => 'create-offer',
        'route' => 'offers/manage/create',
    ],
    [
        'pattern' => 'transactions',
        'route' => 'transaction/main/index',
    ],
    [
        'pattern' => 'payment-in',
        'route' => 'payment/in/index',
    ],
];