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
];