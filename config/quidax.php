<?php

return [
    // Default FX spread in basis points (bps). 50 = 0.5%
    'fx_spread_bps' => env('QUIDAX_FX_SPREAD_BPS', 0),

    // Supported currencies, default networks, and mapping to Quidax currency codes
    'currencies' => [
        'BTC' => [
            'default_network' => 'bitcoin',
            'networks' => ['bitcoin'],
            'codes' => [
                'bitcoin' => 'btc',
            ],
        ],
        'ETH' => [
            'default_network' => 'erc20',
            'networks' => ['erc20'],
            'codes' => [
                'erc20' => 'eth',
            ],
        ],
        'BNB' => [
            'default_network' => 'bsc',
            'networks' => ['bsc'],
            'codes' => [
                'bsc' => 'bnb',
            ],
        ],
        'TRX' => [
            'default_network' => 'tron',
            'networks' => ['tron'],
            'codes' => [
                'tron' => 'trx',
            ],
        ],
        'USDT' => [
            'default_network' => 'trc20',
            'networks' => ['trc20', 'erc20', 'bep20'],
            'codes' => [
                'trc20' => 'usdt-trc20',
                'erc20' => 'usdt-erc20',
                'bep20' => 'usdt-bep20',
            ],
        ],
    ],
];
