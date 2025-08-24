# Quidax Crypto Wallet API Documentation

## Overview
This documentation provides comprehensive information for mobile developers to integrate with the Quidax crypto wallet functionality in the Vastel application.

## Base URL
```
https://your-domain.com/api/v1
```

## Authentication
All API endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your_access_token}
```

## API Endpoints

### 1. Account Management

#### 1.1 Create Quidax User Account
Creates a new Quidax sub-account for the authenticated user.

**Endpoint:** `POST /quidax/users/create`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:** None (uses authenticated user data)

**Response:**
```json
{
    "status": true,
    "message": "Request successful",
    "data": {
        "id": "user_quidax_id",
        "sn": "user_serial_number",
        "display_name": "User Display Name",
        "reference": "user_reference",
        "created_at": "2024-01-01T00:00:00Z",
        "updated_at": "2024-01-01T00:00:00Z"
    }
}
```

**Error Response:**
```json
{
    "status": false,
    "message": "API request failed",
    "errors": ["Error details"]
}
```

#### 1.2 Get User Account Information
Retrieves the authenticated user's Quidax account information.

**Endpoint:** `GET /quidax/account`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": true,
    "message": "Request successful",
    "data": {
        "id": "user_quidax_id",
        "sn": "user_serial_number",
        "display_name": "User Display Name",
        "reference": "user_reference",
        "email": "user@example.com",
        "first_name": "John",
        "last_name": "Doe",
        "phone_number": "+2341234567890",
        "created_at": "2024-01-01T00:00:00Z",
        "updated_at": "2024-01-01T00:00:00Z"
    }
}
```

**Error Response (User not found):**
```json
{
    "status": false,
    "message": "User not found",
    "errors": ["User not found"]
}
```

#### 1.3 Get All Users (Admin Only)
Retrieves all Quidax sub-accounts (typically for admin use).

**Endpoint:** `GET /quidax/users`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": true,
    "message": "Request successful",
    "data": [
        {
            "id": "user_1_id",
            "sn": "user_1_sn",
            "display_name": "User 1",
            "reference": "user_1_reference",
            "created_at": "2024-01-01T00:00:00Z"
        },
        {
            "id": "user_2_id",
            "sn": "user_2_sn",
            "display_name": "User 2",
            "reference": "user_2_reference",
            "created_at": "2024-01-01T00:00:00Z"
        }
    ]
}
```

### 2. Wallet Management

#### 2.1 Get User Wallets
Retrieves all wallets for the authenticated user.

**Endpoint:** `GET /quidax/wallets`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": true,
    "message": "Request successful",
    "data": [
        {
            "currency": "BTC",
            "balance": "0.00123456",
            "locked": "0.00000000",
            "address": "bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh"
        },
        {
            "currency": "ETH",
            "balance": "0.12345678",
            "locked": "0.00000000",
            "address": "0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6"
        },
        {
            "currency": "USDT",
            "balance": "100.00000000",
            "locked": "0.00000000",
            "address": "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t"
        }
    ]
}
```

### Common HTTP Status Codes
- `200` - Success
- `400` - Bad Request
- `401` - Unauthorized (Invalid or missing token)
- `403` - Forbidden (Insufficient permissions)
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

### Error Response Format
```json
{
    "status": false,
    "message": "Error description",
    "errors": [
        "Detailed error message 1",
        "Detailed error message 2"
    ]
}
```

## Implementation Guidelines

### 1. User Onboarding Flow
1. User registers/logs in to your app
2. Call `POST /quidax/users/create` to create Quidax account
3. Store the returned `quidax_id` in your user profile
4. Use `GET /quidax/account` to verify account creation

### 2. Wallet Management Flow
1. Use `GET /quidax/wallets` to display all user wallets
2. Use `GET /quidax/balance-summary` for quick balance overview
3. Use `GET /quidax/wallets/{currency}` for specific currency details

### 3. Deposit Flow
1. User selects cryptocurrency to deposit
2. Display the wallet address from `GET /quidax/wallets/{currency}`
3. Monitor for webhook notifications at `POST /quidax/webhook/handler`
4. Update user balance when deposit is confirmed

### 4. Error Handling Best Practices
- Always check the `status` field in responses
- Implement retry logic for network failures
- Handle rate limiting gracefully
- Log errors for debugging purposes

## Security Considerations

1. **Token Security**: Never expose access tokens in client-side code
2. **Webhook Verification**: Verify webhook signatures to prevent spoofing
3. **Rate Limiting**: Implement appropriate rate limiting on your end
4. **HTTPS**: Always use HTTPS for all API communications
5. **Input Validation**: Validate all user inputs before sending to API

## Testing

### Test Environment
- Use test API keys for development
- Test with small amounts first
- Verify webhook handling in test environment

### Common Test Scenarios
1. Create new user account
2. Check wallet balances
3. Simulate deposit webhook
4. Test error conditions (invalid currency, unauthorized access)

## Support

For technical support or questions about the API:
- Check the application logs for detailed error information
- Contact the backend development team
- Provide request/response examples when reporting issues

## Changelog

### Version 1.0
- Initial API documentation
- Account management endpoints
- Wallet management endpoints
- Currency information endpoints
- Webhook handling
