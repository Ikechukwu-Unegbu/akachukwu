<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoWallet extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'crypto_wallets';

    
    
    /**
     * Supported cryptocurrencies (all lowercase)
     */
    public const USDT = 'usdt';
    public const USDC = 'usdc';
    public const BTC  = 'btc';
        public const NGN  = 'ngn';
    public const ETH  = 'eth';
    public const BNB  = 'bnb';
    public const SOL  = 'sol';
    public const DOGE = 'doge';
    public const TRX  = 'trx';
    public const XRP  = 'xrp';

    /**
     * Array of all supported currencies
     */
    public const ALLOWED_CRYPTO = [
        self::USDT,
        self::USDC,
        self::BTC,
        self::ETH,
        self::NGN,
        self::BNB,
        self::SOL,
        self::DOGE,
        self::TRX,
        self::XRP,
    ];
}
