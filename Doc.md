# VasTel API Documentation for Mobile APP

### Account Creation API 
    Method: POST
    Endpoint: domain/api/register
    Body:
        {
            "name":"Ikechukwu Vin API",
            "username":"Ikeapi2",
            "phone":"08064133376",
            "email":"apiike2@gmail.com", 
            "password":"password", 
            "password_confirmation":"password"
        }

#### Success Response 
    Response:  
        {
            "message": "Account Created.",
            "status": "success",
            "user": {
                "name": "Ikechukwu Vin API",
                "username": "Ikeapi2",
                "email": "apiike2@gmail.com",
                "role": "user",
                "updated_at": "2024-04-13T18:48:54.000000Z",
                "created_at": "2024-04-13T18:48:54.000000Z",
                "id": 5
            }
        }

#### Failure Response
    {
        "message": "The username field is required. (and 1 more error)",
        "errors": {
            "username": [
                "The username field is required."
            ],
            "email": [
                "The email field is required."
            ]
        }
    }


## Login Endpoint
    Method: POST
    Endpoint: domain.com/api/login
    Body:
    {
        "email":"apiike2@gmail.com",
        "password":"password"
    }

#### Success Response
    Response:  
        {
            "\r\n                token": "1|qlNlkUGLaUcKGARvrNcOJb8HNcKvInY6ynNQbsIJb634a4f6",
            "status": "success",
            "user": {
                "id": 5,
                "name": "Ikechukwu Vin API",
                "username": "Ikeapi2",
                "email": "apiike2@gmail.com",
                "role": "user",
                "email_verified_at": null,
                "image": null,
                "address": null,
                "mobile": null,
                "referer_username": null,
                "gender": null,
                "account_balance": "0.00",
                "wallet_balance": "0.00",
                "bonus_balance": "0.00",
                "created_at": "2024-04-13T18:48:54.000000Z",
                "updated_at": "2024-04-13T18:48:54.000000Z",
                "phone": null
            }
        }

#### Failure Response 
        Response: {
            "message": "The password field is required.",
            "errors": {
                "password": [
                    "The password field is required."
                ]
            }
    }

## Forgot Password

## View Profile Settings 


## Update Profile Info 
