This is Intellectual property and copyritght of Halcyon Internet and VasTel Ng. 
On no account shall this work be used by any party without explicity approval of both entities especially VasTel.

## Network Endpoint
    POST /api/networks HTTP/1.1
    Content-Type: application/json
    Example Response : {
        "status": "success",
        "message": "Network Fetched Successfully",
        "response": [
            {
                "id": 1,
                "vendor_id": 1,
                "network_id": 1,
                "name": "MTN",
                "status": 1,
                "created_at": "2024-04-10T19:46:38.000000Z",
                "updated_at": "2024-04-10T19:46:38.000000Z"
            }
        ]
    }

## Data Type Endpoint
    POST /api/datatypes HTTP/1.1
    Content-Type: application/json
    Body : {
        "network_id": 1,
    }
    Example Response : {
        "status": "success",
        "message": "Data Type Fetched Successfully",
        "response": [
            {
                "id": 1,
                "vendor_id": 1,
                "network_id": 1,
                "name": "CORPORATE",
                "status": 1,
                "created_at": "2024-04-10T19:46:39.000000Z",
                "updated_at": "2024-04-10T19:46:39.000000Z"
            },
            {
                "id": 2,
                "vendor_id": 1,
                "network_id": 1,
                "name": "SME",
                "status": 1,
                "created_at": "2024-04-10T19:46:39.000000Z",
                "updated_at": "2024-04-10T19:46:39.000000Z"
            }
        ]
    }

## Data Plan Endpoint
    POST /api/dataplans HTTP/1.1
    Content-Type: application/json
    Body : {
        "network_id": 1,
        "data_type_id": 2,
    }
    Example Response : {
        "status": "success",
        "message": "Data Plan Fetched Successfully.",
        "response": [
            {
                "id": 11,
                "vendor_id": 1,
                "network_id": 1,
                "type_id": 2,
                "data_id": 179,
                "amount": "127.50",
                "size": "500.0MB",
                "validity": "30 days",
                "status": 1,
                "created_at": "2024-04-10T19:46:42.000000Z",
                "updated_at": "2024-04-10T19:46:42.000000Z",
                "type": {
                    "id": 2,
                    "vendor_id": 1,
                    "network_id": 1,
                    "name": "SME",
                    "status": 1,
                    "created_at": "2024-04-10T19:46:39.000000Z",
                    "updated_at": "2024-04-10T19:46:39.000000Z"
                }
            }
        ]
    }

## Buy Airtime Endpoint
    POST /api/airtime/create HTTP/1.1
    Content-Type: application/json
    Authorization: Bearer 2|0q2K7QUbnT3TcQUMsyyRh4UASupLJl9XuKjotUqqe5b1832c
    Body : {
        "network_id": 1,
        "amount": 200,
        "phone_number": 080XXXXXXXX
    }

    Validation Response: {
        "message": "The network field is required. (and 2 more errors)",
        "errors": {
            "network": [
                "The network field is required."
            ],
            "amount": [
                "The amount field is required."
            ],
            "phone_number": [
                "The phone number field is required."
            ]
        }
    }

    Example Response : {
        "status": true,
        "error": null,
        "message": "Airtime purchase successful: ₦200 MTN airtime added to 080XXXXXXXX.",
        "response": {
            "user_id": 1,
            "vendor_id": 1,
            "network_id": 1,
            "network_name": "MTN",
            "amount": "200",
            "mobile_number": "080XXXXXXXX",
            "balance_before": "XXXXX",
            "balance_after": "XXXXX",
            "transaction_id": "20240429043958000-1714428917-airtime-idvcaydhgl043960000-1714428917h2yi",
            "updated_at": "2024-04-29T22:15:17.000000Z",
            "created_at": "2024-04-29T22:15:17.000000Z",
            "id": 37
        }
    }



## Buy Data Endpoint
    POST /api/data/create HTTP/1.1
    Content-Type: application/json
    Authorization: Bearer 2|0q2K7QUbnT3TcQUMsyyRh4UASupLJl9XuKjotUqqe5b1832c
    Body : {
        "network_id": 1,
        "data_type_id": 2,
        "plan_id": 11,
        "phone_number": 080XXXXXXXX
    }

    Validation Response : {
        "message": "The network field is required. (and 3 more errors)",
        "errors": {
            "network": [
                "The network field is required."
            ],
            "data_type_id": [
                "The data type field is required."
            ],
            "plan_id": [
                "The plan field is required."
            ],
            "phone_number": [
                "The phone number field is required."
            ]
        }
    }

    Example Response : {
        "status": true,
        "error": null,
        "message": "Data purchase successful: MTN 500.0MB for ₦127.50 on 080XXXXXXXX.",
        "response": {
            "user_id": 1,
            "vendor_id": 1,
            "network_id": 1,
            "type_id": 2,
            "data_id": 179,
            "amount": "127.50",
            "size": "500.0MB",
            "validity": "30 days",
            "mobile_number": "080XXXXXXXX",
            "balance_before": "XXXXX",
            "balance_after": "XXXXX",
            "plan_network": "MTN",
            "plan_name": "500.0MB",
            "plan_amount": "127.50",
            "transaction_id": "20240430086139600-1714452517-data-gtfw352zldbgly",
            "updated_at": "2024-04-30T04:48:37.000000Z",
            "created_at": "2024-04-30T04:48:37.000000Z",
            "id": 6
        }
    }


## Cable List
    POST /api/cables HTTP/1.1
    Content-Type: application/json
    Example Response : {
        "status": true,
        "message": "Cable Fetched Successfully.",
        "response": [
            {
                "id": 1,
                "vendor_id": 1,
                "cable_id": 1,
                "cable_name": "GOTV",
                "status": 1,
                "created_at": "2024-04-10T19:47:00.000000Z",
                "updated_at": "2024-04-10T19:47:00.000000Z"
            }
        ]
    }

## Cable Plan
    POST /api/cableplans HTTP/1.1
    Content-Type: application/json
    Body : {
        "cable_id" : 1
    }
    Example Response : {
        "status": "success",
        "message": "Cable Plan Fetched Successfully.",
        "response": [
            {
                "id": 1,
                "vendor_id": 1,
                "cable_id": 2,
                "cable_name": "DStv Padi 2500",
                "cable_plan_id": 33,
                "package": "GOtv Smallie-monthly",
                "amount": "1300.00",
                "status": 1,
                "created_at": "2024-04-10T19:47:00.000000Z",
                "updated_at": "2024-04-10T19:47:00.000000Z"
            }
        ]
    }

## Buy Cable Subscription Endpoint

    -- Validate IUC/Smartcard Endpoint

    POST /api/cable/validate HTTP/1.1
    Content-Type: application/json
    Authorization: Bearer 2|0q2K7QUbnT3TcQUMsyyRh4UASupLJl9XuKjotUqqe5b1832c
    Body : {
        "iuc_number": XXXXXXXXXX,
        "cable_id": 2
    }
    Example Response : {
        "status": true,
        "error": null,
        "message": "IUC validated. Proceed to make payment.",
        "response": {
            "name": "CANL16Ikechukwu Unegbu"
        }
    }

    -- Purchase Cable Subscription Endpoint

    POST /api/cable/create HTTP/1.1
    Content-Type: application/json
    Authorization: Bearer 2|0q2K7QUbnT3TcQUMsyyRh4UASupLJl9XuKjotUqqe5b1832c
    Body : {
        "iuc_number": XXXXXXXXXX,
        "cable_id": 2,
        "cable_plan_id": 33,
        "card_owner": "CANL16Ikechukwu Unegbu"
    }
    Example Response : {
         "status": true,
        "error": null,
        "message": "Cable subscription successful: DStv Padi 2500 for ₦2950.00 on CANL16Ikechukwu Unegbu (7527694615).",
        "response": {
            "user_id": 1,
            "vendor_id": 1,
            "cable_name": "DSTV",
            "cable_id": 2,
            "cable_plan_name": "DStv Padi 2500.",
            "cable_plan_id": 33,
            "smart_card_number": "XXXXXXXXXX",
            "customer_name": "CANL16Ikechukwu Unegbu",
            "amount": "2950.00",
            "balance_before": "XXXXX",
            "balance_after": "XXXXX",
            "transaction_id": "20240430025003700-1714461114-cable-p2voblpsdz025005200-1714461114tgs7",
            "updated_at": "2024-04-30T07:11:54.000000Z",
            "created_at": "2024-04-30T07:11:54.000000Z",
            "id": 12
        }
    }

## Electricity Discos
    POST /api/electricity/discos HTTP/1.1
    Content-Type: application/json
    Example Response : {
        "status": true,
        "message": "Electricity Disco Fetched Successfully.",
        "response": [
            {
                "id": 1,
                "vendor_id": 1,
                "disco_id": 18,
                "disco_name": "Ikeja Electric",
                "status": 1,
                "created_at": "2024-04-10T19:46:58.000000Z",
                "updated_at": "2024-04-10T19:46:58.000000Z"
            }
        ]
    }

## Pay Electricity Bill Endpoint

    -- Validate Meter Number Endpoint

    POST /api/electricity/validate HTTP/1.1
    Content-Type: application/json
    Authorization: Bearer 2|0q2K7QUbnT3TcQUMsyyRh4UASupLJl9XuKjotUqqe5b1832c
    Body : {
        "meter_number": XXXXXXXXXX,
        "disco_id": 18,
        "meter_type": 1 or 2
    }

meter_type: *Prepaid* = 1 & *Postpaid* = 2

    Example Response : {
        "status": true,
        "error": null,
        "message": "Meter Number validated. Proceed to make payment.",
        "response": {
            "name": "Ikechukwu Unegbu",
            "address": "1 GANIYA LAWAL STR OLORUNISHOLA AYOBO LAGOS"
        }
    }
   
    -- Purchase Electricity Bill Endpoint

    POST /api/electricity/create HTTP/1.1
    Content-Type: application/json
    Authorization: Bearer 2|0q2K7QUbnT3TcQUMsyyRh4UASupLJl9XuKjotUqqe5b1832c
    Body : {
        "amount": 500,
        "meter_number": XXXXXXXXXX,
        "disco_id": 18,
        "meter_type": 1 or 2,
        "owner_name": "Ikechukwu Unegbu",
        "owner_address": "1 GANIYA LAWAL STR OLORUNISHOLA AYOBO LAGOS",
        "phone_number": 080XXXXXXXX
    }
    Example Response : {
        "status": true,
        "error": null,
        "message": "Bill payment successful: ₦500 Prepaid purchase for (04290592098).",
        "response": {
            "user_id": 1,
            "vendor_id": 1,
            "disco_id": 18,
            "disco_name": "Ikeja Electric",
            "meter_number": "XXXXXXXXXX",
            "meter_type_id": "1",
            "meter_type_name": "Prepaid",
            "amount": "500",
            "customer_mobile_number": "080XXXXXXXX",
            "customer_name": "Ikechukwu Unegbu",
            "customer_address": "1 GANIYA LAWAL STR OLORUNISHOLA AYOBO LAGOS",
            "balance_before": "XXXXX",
            "balance_after": "XXXXX",
            "transaction_id": "20240430032263900-1714462912-electricity-mmv8mqesxz032265900-1714462912jjgn",
            "token": "34860165086658327630",
            "updated_at": "2024-04-30T07:41:52.000000Z",
            "created_at": "2024-04-30T07:41:52.000000Z",
            "id": 12
        }
    }

## User PIN Endpoint

    -- Creating New PIN

    POST /api/pin/create HTTP/1.1
    Content-Type: application/json
    Authorization: Bearer 2|0q2K7QUbnT3TcQUMsyyRh4UASupLJl9XuKjotUqqe5b1832c
    Body : {
        "pin": 1234,
        "pin_confirmation": 1234
    }

    Validation Response : {
        "errors": {
            "pin": [
                "The pin field is required.",
                "The pin field must be 4 digits."
            ],
            "pin_confirmation": [
                "The pin confirmation field is required.",
                "The pin confirmation field must match pin."
            ]
        }
    }

    Example Response : {
        "status": true,
        "error": null,
        "message": "PIN created successfully."
    }

    -- Updating Pin

    POST /api/pin/update HTTP/1.1
    Content-Type: application/json
    Authorization: Bearer 2|0q2K7QUbnT3TcQUMsyyRh4UASupLJl9XuKjotUqqe5b1832c
    Body : {
        "current_pin": 1234,
        "pin": 1234,
        "pin_confirmation": 1234
    }

    Validation Response : {
        "message": "The current pin field is required. (and 2 more errors)",
        "errors": {
            "current_pin": [
                "The current pin field is required.",
                "The current pin field must be 4 digits.",
                "The current PIN is incorrect."
            ],
            "pin": [
                "The pin field is required.",
                "The pin field must be 4 digits."
            ],
            "pin_confirmation": [
                "The pin confirmation field is required.",
                "The pin confirmation field must match pin."
            ]
        }
    }

    Example Response : {
        "status": true,
        "error": null,
        "message": "PIN updated successfully."
    }


    -- Validating Pin on every transaction
    POST /api/pin/validate HTTP/1.1
    Content-Type: application/json
    Authorization: Bearer 2|0q2K7QUbnT3TcQUMsyyRh4UASupLJl9XuKjotUqqe5b1832c
    Body : {
        "pin": 1234
    }

    Validation Response : {
        "message": "The pin field is required",
        "errors": {
            "pin": [
                "The pin field is required.",
                "The pin field must be 4 digits.",
                "The PIN provided is incorrect. Provide a valid PIN."
            ]
        }
    }

    Example Response : {
        "status": true,
        "message": "PIN validated successfully."
    }


    POST /api/epins/create HTTP/1.1
    Content-Type: application/json
    Authorization: Bearer 2|0q2K7QUbnT3TcQUMsyyRh4UASupLJl9XuKjotUqqe5b1832c
    Body : {
        "exam_name": "WAEC",
        "quantity": 1
    }
    Example Response : {
        "status": true,
        "response": {
            "transaction_id": "20240716037023800-1721152807-result-checker-iijfcqeqqobls7",
            "exam_name": "WAEC",
            "quantity": "1",
            "amount": "3900.00",
            "balance_before": "14000000.00",
            "balance_after": "13996100",
            "purchase_codes": [
                {
                    "id": 23,
                    "result_checker_id": 16,
                    "serial": "WRN182135587",
                    "pin": "373820665258",
                    "created_at": "2024-07-16T18:00:08.000000Z",
                    "updated_at": "2024-07-16T18:00:08.000000Z"
                }
            ]
        },
        "message": "Result Checker PIN purchase successful: WAEC (1 QTY) ₦3900."
    }