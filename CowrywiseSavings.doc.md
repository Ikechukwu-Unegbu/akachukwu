# Cowrywise Investment Account Saving API

## Get Saving Rates Endpoint
`GET {{base_url}}/investments/savings/rate`
## Body
```json
{
    "days": "30"
}
```
## Response Structure
```json
{
    "status": true,
    "response": {
        "data": {
            "interest_rate": "11.0%",
            "tenure": "per-annum"
        },
        "errors": null,
        "message": "Request successful",
        "status": "success"
    },
    "message": "Saving rates fetched successfully"
}
```

## Create Saving Endpoint
`POST {{base_url}}/investments/savings/create`
```json
{
    "days": "30",
    "interest_enabled": true
}
```
## Response Structure
```json
{
    "status": true,
    "response": {
        "data": {
            "savings_id": "cbabe098c2fd4750a59ecb94eb8f48bb",
            "account_id": "81ae101e14194b9785cc0740093e95e7",
            "name": "Naira Savings - 90 days",
            "product_code": "PRCDE1789633030",
            "currency": "NGN",
            "principal": "0.00000",
            "returns": "0.00000",
            "balance": "0.00000",
            "interest_enabled": true,
            "interest_rate": "0.11",
            "maturity_date": "2025-09-21",
            "is_matured": false,
            "created_on": "2025-06-23T04:37:09.603949+00:00",
            "start_date": "2025-06-23"
        },
        "errors": null,
        "message": "Request successful",
        "status": "success"
    },
    "message": "Savings created successfully"
}
```

## Fetch All Savings Endpoint
`GET {{base_url}}/investments/savings/{saving_id}/fetch-savings`
## Response Structure
```json
{
    "status": true,
    "response": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 9,
                "cowry_wise_account_id": 19,
                "savings_id": "cbabe098c2fd4750a59ecb94eb8f48bb",
                "name": "Naira Savings - 90 days",
                "product_code": "PRCDE1789633030",
                "principal": "0.00",
                "returns": "0.00",
                "balance": "0.00",
                "interest_enabled": 0,
                "interest_rate": "0.11",
                "maturity_date": "2025-09-21",
                "created_at": "2025-06-23T04:37:09.000000Z",
                "updated_at": "2025-06-23T04:37:09.000000Z",
                "user": {...},
                "account": {...}
            }
        ],
        "first_page_url": "https://vastel.dev/api/investments/savings?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "https://vastel.dev/api/investments/savings?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "https://vastel.dev/api/investments/savings?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "https://vastel.dev/api/investments/savings",
        "per_page": 20,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    },
    "message": "Savings Fetched Successfully"
}
```

## Fetch Single Savings Endpoint
`GET {{base_url}}/investments/savings/fetch-savings`
## Response Structure
```json
{
    "status": true,
    "response": {
        "id": 1,
        "user_id": 9,
        "cowry_wise_account_id": 19,
        "savings_id": "cbabe098c2fd4750a59ecb94eb8f48bb",
        "name": "Naira Savings - 90 days",
        "product_code": "PRCDE1789633030",
        "principal": "0.00",
        "returns": "0.00",
        "balance": "0.00",
        "interest_enabled": 0,
        "interest_rate": "0.11",
        "maturity_date": "2025-09-21",
        "created_at": "2025-06-23T04:37:09.000000Z",
        "updated_at": "2025-06-23T04:37:09.000000Z",
        "user": {...},
        "account": {...}
    },
    "message": "Savings retrieved Successfully"
}
```

## Get Single Savings Performance Endpoint
`GET {{base_url}}/investments/savings/{saving_id}/performance`
## Body
```json
{
    "start_date": "2025-06-23",
    "end_date": "2025-07-23"
}
```
## Response Structure
```json
{
    "status": true,
    "response": {
        "data": [],
        "errors": null,
        "message": "Request successful",
        "status": "success"
    },
    "message": "Savings Performance retrieved successfully"
}
```

## Fund Savings Endpoint
`GET {{base_url}}/investments/savings/{saving_id}/fund`
## Body
```json
{
    "amount": "1000"
}
```
## Response Structure
```json
{
    "status": true,
    "response": {
        "data": {
            "id": "850fc9b33aac419387baf47afcfb34d6",
            "amount": {
                "value": "150000.00000",
                "currency_code": "NGN"
            },
            "fee": {
                "value": "0.00000",
                "currency_code": "NGN"
            },
            "reference": "TRF5C00A48D2B0A7BC8",
            "description": "",
            "source_asset": {
                "id": "bc03729b-aa38-4214-8acf-2cef6aa62182",
                "name": "Naira Wallet",
                "custom_name": "",
                "account_id": "81ae101e14194b9785cc0740093e95e7",
                "account_name": "Jane Doe2",
                "account_email": "chelsey.frami@hotmail.com",
                "product_code": "PRCDE6608556312",
                "asset_type": "wallet",
                "current_value": "49250000.000",
                "old_balance": 49400000,
                "new_balance": 49250000
            },
            "destination_asset": {
                "id": "cbabe098-c2fd-4750-a59e-cb94eb8f48bb",
                "name": "Naira Savings - 90 days",
                "custom_name": "",
                "account_id": "81ae101e14194b9785cc0740093e95e7",
                "account_name": "Jane Doe2",
                "account_email": "chelsey.frami@hotmail.com",
                "product_code": "PRCDE1789633030",
                "asset_type": "savings",
                "current_value": "597000.00000",
                "old_balance": 0,
                "new_balance": 0
            },
            "transfer_type": "asset-funding",
            "deposit_holding": null,
            "status": "Pending",
            "transaction_date": "2025-06-23T08:20:38.920298+00:00",
            "updated_on": "2025-06-23T09:20:38.934003+01:00",
            "meta": []
        },
        "errors": null,
        "message": "Request successful",
        "status": "success"
    },
    "message": "Fund Savings created successfully"
}
```
## Error Response
```json
{
    "status": false,
    "errors": [
        "Fund Savings Creation Failed!"
    ],
    "message": {
        "status": 400,
        "errorBody": {
            "data": null,
            "errors": [
                {
                    "error_class": "ValidationError",
                    "message": "Amount is less than the minimum amount for this investment"
                }
            ],
            "message": "Request failed",
            "status": "failed",
            "trace_id": "f16e1aa1f139e2227c8f40c2d7cc359d"
        }
    }
}
```

## Fund Savings Endpoint
`GET {{base_url}}/investments/savings/{saving_id}/withdraw`
## Body
```json
{
    "amount": "1000"
}
```
## Response Structure
```json
{
    "status": true,
    "response": {
        "data": {
            "id": "6e98828ee5684cc8bee131246d824533",
            "amount": {
                "value": "150000.00000",
                "currency_code": "NGN"
            },
            "fee": {
                "value": "3000.00000",
                "currency_code": "NGN"
            },
            "reference": "TRFC2794E747690A84A",
            "description": "",
            "source_asset": {
                "id": "cbabe098-c2fd-4750-a59e-cb94eb8f48bb",
                "name": "Naira Savings - 90 days",
                "custom_name": "",
                "account_id": "81ae101e14194b9785cc0740093e95e7",
                "account_name": "Jane Doe2",
                "account_email": "chelsey.frami@hotmail.com",
                "product_code": "PRCDE1789633030",
                "asset_type": "savings",
                "current_value": "594000.00000",
                "old_balance": 747000,
                "new_balance": 594000
            },
            "destination_asset": {
                "id": "bc03729b-aa38-4214-8acf-2cef6aa62182",
                "name": "Naira Wallet",
                "custom_name": "",
                "account_id": "81ae101e14194b9785cc0740093e95e7",
                "account_name": "Jane Doe2",
                "account_email": "chelsey.frami@hotmail.com",
                "product_code": "PRCDE6608556312",
                "asset_type": "wallet",
                "current_value": "49250000.0000",
                "old_balance": 0,
                "new_balance": 0
            },
            "transfer_type": "asset-liquidation",
            "deposit_holding": null,
            "status": "Pending",
            "transaction_date": "2025-06-23T08:21:11.760508+00:00",
            "updated_on": "2025-06-23T09:21:11.773410+01:00",
            "meta": []
        },
        "errors": null,
        "message": "Request successful",
        "status": "success"
    }
}
```
