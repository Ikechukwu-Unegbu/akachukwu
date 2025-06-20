# Cowrywise Investment Account Onboarding API

## Create Account Endpoint
`POST {{base_url}}/investments/investments/onboarding/create`

## Description
This endpoint creates a new Investment Account which serves as the foundation for various investment products. The account holds core user information and serves as the primary container for all investment activities.

## Body
```json
{
    "first_name": "Test",
    "last_name": "User"
}
```
## Response Structure
```json
{
    "status": true,
    "response": {
        "data": {
            "account_id": "string",
            "account_number": "integer",
            "first_name": "string",
            "last_name": "string",
            "name": "string",
            "email": "string",
            "risk_appetite": "integer",
            "is_proprietary": "boolean",
            "account_status": "string",
            "verification_status": "string",
            "is_verified": "boolean",
            "account_type": "string",
            "phone_number": "string|null",
            "date_of_birth": "date|null",
            "gender": "string|null",
            "identifications": "array",
            "banks": "array",
            "address": {
                "street": "string",
                "lga": "string",
                "area_code": "string",
                "city": "string",
                "state": "string"
            },
            "next_of_kin": {
                "first_name": "string",
                "last_name": "string",
                "email": "string",
                "phone_number": "string",
                "relationship": "string",
                "gender": "string|null"
            },
            "date_joined": "datetime",
            "terms_of_use_accepted": "boolean"
        },
        "errors": "null|array",
        "message": "string",
        "status": "string"
    },
    "message": "Account created successfully"
}
```

## Update Account Endpoint
`POST {{base_url}}/investments/onboarding/profile/update`
## Body
```json
{
    "first_name": "Test",
    "last_name": "User",
    "phone_number": "09099009900",
    "gender": "M" OR "F",
    "date_of_birth": "1996-12-03",
}
```
## Response Structure
```json
{
    "status": true,
    "response": {
        "data": {
            "account_id": "string",
            "account_number": "integer",
            "first_name": "string",
            "last_name": "string",
            "name": "string",
            "email": "string",
            "risk_appetite": "integer",
            "is_proprietary": "boolean",
            "account_status": "string",
            "verification_status": "string",
            "is_verified": "boolean",
            "account_type": "string",
            "phone_number": "string|null",
            "date_of_birth": "date|null",
            "gender": "string|null",
            "identifications": "array",
            "banks": "array",
            "address": {
                "street": "string",
                "lga": "string",
                "area_code": "string",
                "city": "string",
                "state": "string"
            },
            "next_of_kin": {
                "first_name": "string",
                "last_name": "string",
                "email": "string",
                "phone_number": "string",
                "relationship": "string",
                "gender": "string|null"
            },
            "date_joined": "datetime",
            "terms_of_use_accepted": "boolean"
        },
        "errors": "null|array",
        "message": "string",
        "status": "string"
    },
    "message": "Account updated successfully"
}
```


## Get Account Endpoint
`GET {{base_url}}/investments/onboarding/account`
## Response Structure
```json
{
    "status": true,
    "response": {
        "id": 1,
        "user_id": 9,
        "account_id": "9588915e1f934d11a9eb1ee60f8149f0",
        "account_type": "individual",
        "account_status": "ACTIVE",
        "is_verified": false,
        "risk_appetite": 0,
        "terms_of_use_accepted": false,
        "account_number": "4XXXXXXXXXXX",
        "is_proprietary": false,
        "verification_status": "UNVERIFIED",
        "date_joined": "2025-06-19T16:36:03.000000Z",
        "street": "",
        "lga": "",
        "area_code": "",
        "city": "",
        "state": "",
        "date_of_birth": "1996-12-02",
        "gender": "M",
        "created_at": "2025-06-19T17:36:03.000000Z",
        "updated_at": "2025-06-19T22:58:53.000000Z",
        "banks": [],
        "next_of_kin": null,
        "identities": []
    },
    "message": "Account retrieved successfully"
}
```


## GET Account Portfolio Endpoint
`GET {{base_url}}/investments/onboarding/portfolio`
## Response Structure
```json
{
    "status": true,
    "response": {
        "data": {
            "account_id": "9588915e1f934d11a9eb1ee60f8149f0",
            "balance": [
                {
                    "currency": "USD",
                    "value": "50000000.000",
                    "rate_of_returns": "0.0",
                    "portfolio_returns": "0.00000"
                },
                {
                    "currency": "NGN",
                    "value": "50000000.000",
                    "rate_of_returns": "0.0",
                    "portfolio_returns": "0.00000"
                }
            ],
            "assets": {
                "wallets": [
                    {
                        "wallet_id": "9c7e2b2e44ab469aaf1e1a1f0cca66e6",
                        "account_id": "9588915e1f934d11a9eb1ee60f8149f0",
                        "name": "NGN-Wallet",
                        "product_code": "PRCDE8281077064",
                        "currency": "NGN",
                        "balance": "50000000.0000",
                        "account_number": "",
                        "account_name": "",
                        "bank_name": "",
                        "virtual_accounts": [],
                        "created_on": "2025-06-19T17:36:03.926257+00:00"
                    },
                    {
                        "wallet_id": "f35d3ae3be7246dbb3c425db1f0fec49",
                        "account_id": "9588915e1f934d11a9eb1ee60f8149f0",
                        "name": "USD-Wallet",
                        "product_code": "PRCDE8867540374",
                        "currency": "USD",
                        "balance": "50000000.0000",
                        "account_number": "",
                        "account_name": "",
                        "bank_name": "",
                        "virtual_accounts": [],
                        "created_on": "2025-06-19T17:36:03.897537+00:00"
                    }
                ],
                "savings": [],
                "flexible_savings": [],
                "indexes": [],
                "mutual_funds": [],
                "treasury_bills": [],
                "stocks": [],
                "fixed_notes": []
            }
        },
        "errors": null,
        "message": "Request successful",
        "status": "success"
    },
    "message": "Portfolio fetched successfully"
}
```


## Update Account Identity Endpoint
`POST {{base_url}}/investments/onboarding/identity/update`
## Body
```json
{
    "identity_type": "BVN" OR "NIN",
    "identity_value": "22146433184"
}
```
## Response Structure
```json
{
    "status": true,
    "response": {
        "id": "9ccf4297a7f54af9a251c22479080ab2",
        "type": "BVN",
        "value": "22146433184",
        "verification_status": "PENDING",
        "failure_verification_reason": null
    },
    "message": "Account Identity added successfully"
}
```

## Update Account Address Endpoint
`POST {{base_url}}/investments/onboarding/address/update`
```json
{
    "street": "10, Edidi Lane",
    "lga": "Idumota",
    "area_code": "100034",
    "city": "Lagos",
    "state": "Lagos"
}
```
## Response Structure
```json
{
    "status": true,
    "response": {
        "account_id": "9588915e1f934d11a9eb1ee60f8149f0",
        "account_number": "4XXXXXXXX",
        "first_name": "Test",
        "last_name": "User",
        "name": "Test User",
        "email": "koby39@gmail.com",
        "risk_appetite": 0,
        "is_proprietary": false,
        "account_status": "ACTIVE",
        "verification_status": "FAILED",
        "is_verified": false,
        "account_type": "individual",
        "phone_number": "+2349099009900",
        "date_of_birth": "1996-12-03",
        "gender": "M",
        "identifications": [
            {
                "id": "9ccf4297a7f54af9a251c22479080ab2",
                "type": "BVN",
                "value": "22146433184",
                "verification_status": "FAILED",
                "failure_verification_reason": "invalid BVN value"
            }
        ],
        "banks": [],
        "address": {
            "address_id": "aa341e50f5ec4aabb8ad60ccce6abf39",
            "street": "10, Edidi Lane",
            "lga": "Idumota",
            "area_code": "100034",
            "city": "Lagos",
            "state": "Lagos",
            "country": "Nigeria"
        },
        "next_of_kin": {
            "first_name": "",
            "last_name": "",
            "email": "",
            "phone_number": "",
            "relationship": "",
            "gender": null
        },
        "date_joined": "2025-06-19T17:36:03.878157+00:00",
        "terms_of_use_accepted": true
    },
    "message": "Account Identity added successfully"
}
```

## Update Account Next Of Kin Endpoint
`POST {{base_url}}/investments/onboarding/nok/update`
## Body
```json
{
    "relationship": "sister",
    "first_name": "Test",
    "last_name": "User2",
    "email": "nok.email@email.com",
    "phone_number": "09068514310",
    "gender": "F"
}
```
## Response Structure
```json
{
    "status": true,
    "response": {
        "account_id": "9588915e1f934d11a9eb1ee60f8149f0",
        "account_number": "4XXXXXXXXX",
        "first_name": "Test",
        "last_name": "User",
        "name": "Test User",
        "email": "koby39@gmail.com",
        "risk_appetite": 0,
        "is_proprietary": false,
        "account_status": "ACTIVE",
        "verification_status": "FAILED",
        "is_verified": false,
        "account_type": "individual",
        "phone_number": "+2349099009900",
        "date_of_birth": "1996-12-03",
        "gender": "M",
        "identifications": [
            {
                "id": "9ccf4297a7f54af9a251c22479080ab2",
                "type": "BVN",
                "value": "22146433184",
                "verification_status": "FAILED",
                "failure_verification_reason": "invalid BVN value"
            }
        ],
        "banks": [],
        "address": {
            "address_id": "aa341e50f5ec4aabb8ad60ccce6abf39",
            "street": "10, Edidi Lane",
            "lga": "Idumota",
            "area_code": "100034",
            "city": "Lagos",
            "state": "Lagos",
            "country": "Nigeria"
        },
        "next_of_kin": {
            "kin_id": "a8e1fd754f3a41098b100fb8cf5c97b5",
            "first_name": "Test",
            "last_name": "User2",
            "email": "nok.email@email.com",
            "phone_number": "+2349068514310",
            "relationship": "sister",
            "gender": "F"
        },
        "date_joined": "2025-06-19T17:36:03.878157+00:00",
        "terms_of_use_accepted": true
    },
    "message": "Account updated successfully"
}
```


## Add Bank Endpoint
## Description
Add a bank account to an investment account.
`POST {{base_url}}/investments/onboarding/bank/create`
## Body
```json
{
    "bank_code": "035",
    "account_number": "0244940582"
}
```
## Response Structure


## Create Wallet Endpoint
## Description
Create a new wallet
`POST {{base_url}}/investments/wallet/create`
## Response Structure
```json
{
    {
        "wallet_id": "368b59e6f35d1de6669ad44e09a5b976",
        "account_id": "b9c82f9fde159f6cdf73342c96dd5382",
        "name": "NGN-Wallet",
        "bank_name": "",
        "product_code": "PRCD265E3469989",
        "created_on": "2022-08-17T06:42:59.780880+00:00",
        "currency": "NGN",
        "balance": "0.00000",
        "account_number": "",
        "account_name": ""
    },
    "errors":null,
    "message":"Request successful",
    "status":"success"
}
```

## Get Wallet Endpoint
## Description
`GET {{base_url}}/investments/wallet`
## Response Structure
```json

```
