# Quidax API Integration

This document explains how to use the Quidax API integration for crypto wallet operations in the Vastel application.

## Configuration

Add the following environment variables to your `.env` file:

```env
QUIDAX_API_KEY=your_quidax_api_key
QUIDAX_SECRET_KEY=your_quidax_secret_key
QUIDAX_TEST_MODE=true  # Set to false for production
```

## API Endpoints

All endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header.

### Account and Wallet Management

#### Get Account Information
```http
GET /api/quidax/account
```

#### Get User Wallets
```http
GET /api/quidax/wallets
```

#### Get Specific Wallet Balance
```http
GET /api/quidax/wallets/{currency}
```

#### Get Account Balance Summary
```http
GET /api/quidax/balance-summary
```

#### Get Wallet Statistics
```http
GET /api/quidax/wallet-stats
```

### Deposit and Withdrawal

#### Get Deposit Address
```http
GET /api/quidax/deposit-address/{currency}
```

#### Get Deposit History
```http
GET /api/quidax/deposits?currency=BTC&limit=100&page=1
```

#### Get Withdrawal History
```http
GET /api/quidax/withdrawals?currency=BTC&limit=100&page=1
```

#### Create Withdrawal
```http
POST /api/quidax/withdraw
Content-Type: application/json

{
    "currency": "BTC",
    "amount": "0.001",
    "address": "bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh",
    "memo": "optional_memo"
}
```

#### Get Withdrawal Fee
```http
GET /api/quidax/withdrawal-fee/{currency}
```

### Currencies and Markets

#### Get Supported Currencies
```http
GET /api/quidax/currencies
```

#### Get Currency Information
```http
GET /api/quidax/currencies/{currency}
```

#### Get Markets
```http
GET /api/quidax/markets
```

#### Get Market Ticker
```http
GET /api/quidax/markets/{market}/ticker
```

#### Get Market Price
```http
GET /api/quidax/markets/{market}/price
```

#### Get Order Book
```http
GET /api/quidax/markets/{market}/order-book?asks=20&bids=20
```

#### Get Market Trades
```http
GET /api/quidax/markets/{market}/trades?limit=50
```

### Trading

#### Create Buy Order
```http
POST /api/quidax/orders/buy
Content-Type: application/json

{
    "market": "btcngn",
    "quantity": "0.001",
    "price": "50000000",
    "type": "limit"
}
```

#### Create Sell Order
```http
POST /api/quidax/orders/sell
Content-Type: application/json

{
    "market": "btcngn",
    "quantity": "0.001",
    "price": "50000000",
    "type": "limit"
}
```

#### Get User Orders
```http
GET /api/quidax/orders?market=btcngn&state=wait&limit=100&page=1
```

#### Get Specific Order
```http
GET /api/quidax/orders/{orderId}
```

#### Cancel Order
```http
POST /api/quidax/orders/{orderId}/cancel
```

#### Get User Trades
```http
GET /api/quidax/trades?market=btcngn&limit=100&page=1
```

## Response Format

All API responses follow this format:

### Success Response
```json
{
    "status": true,
    "response": {
        // Response data
    },
    "message": "Success message"
}
```

### Error Response
```json
{
    "status": false,
    "errors": [
        // Error details
    ],
    "message": "Error message"
}
```

## Usage Examples

### Using the WalletService directly

```php
use App\Services\Payment\Crypto\WalletService;

$walletService = new WalletService();

// Get user wallets
$wallets = $walletService->getUserWallets();

// Get specific wallet balance
$balance = $walletService->getWalletBalance('BTC');

// Create withdrawal
$withdrawal = $walletService->createWithdrawal('BTC', '0.001', 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh');
```

### Using the QuidaxService directly

```php
use App\Services\Payment\Crypto\QuidaxxService;

$quidaxService = new QuidaxxService();

// Get account info
$accountInfo = $quidaxService->getAccountInfo();

// Get market price
$marketPrice = $quidaxService->getMarketPrice('btcngn');
```

## Error Handling

The integration includes comprehensive error handling:

- Network timeouts (30 seconds)
- API error responses
- Validation errors
- Logging of all errors for debugging

## Testing

For testing, set `QUIDAX_TEST_MODE=true` in your environment. This will use the staging API endpoint.

## Security Notes

- API keys are stored securely in environment variables
- All requests are authenticated using Laravel Sanctum
- Sensitive data is logged appropriately
- Input validation is performed on all endpoints

## Support

For issues with the Quidax API integration, check the Laravel logs for detailed error information.
