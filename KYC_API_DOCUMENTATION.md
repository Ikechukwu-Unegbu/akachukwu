# KYC Verification API Documentation

## Overview

The KYC (Know Your Customer) verification system is a tiered approach that allows users to progressively unlock more features and higher transaction limits. The system consists of three tiers:

- **Tier 1**: Basic personal information and identification (BVN/NIN)
- **Tier 2**: Address information, next of kin details, and utility bill upload
- **Tier 3**: Government ID upload and final verification for unlimited access

## Base URL

```
POST /api/v1/kyc
```

## Authentication

All endpoints require authentication using Laravel Sanctum. Include the bearer token in the Authorization header:

```
Authorization: Bearer {your_token}
```

---

## Endpoints

### 1. Submit Tier 1 Verification

**Endpoint:** `POST /api/v1/kyc/tier-1`

**Description:** Submit personal information and identification (BVN or NIN) for Tier 1 verification.

**Request Body:**
```json
{
    "phone_number": "+2348012345678",
    "gender": "female",
    "identification_type": "bvn",
    "identification_number": "12345678901",
    "date_of_birth": "1990-01-01"
}
```

**Field Descriptions:**
- `phone_number` (required): User's phone number
- `gender` (required): Gender selection (male, female, other)
- `identification_type` (required): Type of identification (bvn, nin)
- `identification_number` (required): BVN or NIN number
- `date_of_birth` (required if identification_type is "nin"): Date of birth in YYYY-MM-DD format

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Tier 1 verification completed successfully",
    "data": {
        "tier": 1,
        "completed": true,
        "next_tier": 2
    }
}
```

---

### 2. Submit Tier 2 Verification

**Endpoint:** `POST /api/v1/kyc/tier-2`

**Description:** Submit address information, next of kin details, and utility bill for Tier 2 verification.

**Request Body:**
```json
{
    "house_number": "123",
    "street_address": "123 Main Street, Victoria Island",
    "local_government": "Victoria Island",
    "area_code": "100001",
    "city": "Lagos",
    "state": "Lagos",
    "next_of_kin_relationship": "spouse",
    "next_of_kin_first_name": "John",
    "next_of_kin_last_name": "Johnson",
    "next_of_kin_email": "john@example.com",
    "next_of_kin_phone": "+2348012345679",
    "next_of_kin_gender": "male",
    "utility_bill_type": "electricity",
    "utility_bill_file": "[file upload]"
}
```

**Field Descriptions:**
- `house_number` (required): House/building number
- `street_address` (required): Complete street address
- `local_government` (required): Local government area
- `area_code` (required): Postal/area code
- `city` (required): City name
- `state` (required): State name
- `next_of_kin_relationship` (required): Relationship to next of kin
- `next_of_kin_first_name` (required): Next of kin's first name
- `next_of_kin_last_name` (required): Next of kin's last name
- `next_of_kin_email` (required): Next of kin's email address
- `next_of_kin_phone` (required): Next of kin's phone number
- `next_of_kin_gender` (required): Next of kin's gender
- `utility_bill_type` (required): Type of utility bill (electricity, waste_water, rent_tenancy, internet, other)
- `utility_bill_file` (required): Utility bill file (SVG, PNG, JPG, JPEG, GIF, max 5MB)

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Tier 2 verification completed successfully",
    "data": {
        "tier": 2,
        "completed": true,
        "next_tier": 3
    }
}
```

---

### 3. Submit Tier 3 Verification

**Endpoint:** `POST /api/v1/kyc/tier-3`

**Description:** Submit government ID and BVN for final verification and unlimited access.

**Request Body:**
```json
{
    "id_type": "national_id",
    "id_file": "[file upload]",
    "bvn_number": "12345678901"
}
```

**Field Descriptions:**
- `id_type` (required): Type of government ID (national_id, voters_card, drivers_license, international_passport)
- `id_file` (required): Government ID file (SVG, PNG, JPG, JPEG, GIF, max 5MB)
- `bvn_number` (required): Bank Verification Number

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Tier 3 verification completed successfully. Full access unlocked!",
    "data": {
        "tier": 3,
        "completed": true,
        "kyc_completed": true,
        "user_level": "verified"
    }
}
```

---

### 4. Get Verification Status

**Endpoint:** `GET /api/v1/kyc/status`

**Description:** Get the current verification status and progress for all tiers.

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Verification status retrieved successfully",
    "data": {
        "tier_1": {
            "completed": true,
            "completed_at": "2024-01-15T10:30:00Z",
            "requirements": {
                "personal_info": true,
                "identification": true
            }
        },
        "tier_2": {
            "completed": true,
            "completed_at": "2024-01-16T14:20:00Z",
            "requirements": {
                "address_info": true,
                "next_of_kin": true,
                "utility_bill": true
            }
        },
        "tier_3": {
            "completed": false,
            "completed_at": null,
            "requirements": {
                "government_id": false,
                "bvn_verification": true
            }
        },
        "overall_status": {
            "kyc_completed": false,
            "current_tier": 2,
            "user_level": "basic"
        }
    }
}
```

---

### 5. Upload Document

**Endpoint:** `POST /api/v1/kyc/upload-document`

**Description:** Upload a document file (utility bill or government ID) separately.

**Request Body:**
```
Content-Type: multipart/form-data

document_type: utility_bill
file: [file upload]
```

**Field Descriptions:**
- `document_type` (required): Type of document (utility_bill, government_id)
- `file` (required): Document file (SVG, PNG, JPG, JPEG, GIF, max 5MB)

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Document uploaded successfully",
    "data": {
        "file_path": "https://cdn.example.com/production/kyc/utility_bills/123/abc123.png",
        "file_name": "IKEDC Bill Screenshot.png",
        "file_size": 204800
    }
}
```

---

## Error Responses

### Validation Error (422)
```json
{
    "status": "failed",
    "message": "Validation failed",
    "errors": {
        "first_name": ["The first name field is required."],
        "identification_number": ["The identification number must be 11 digits."]
    }
}
```

### Server Error (500)
```json
{
    "status": "failed",
    "message": "An error occurred while processing Tier 1 verification",
    "error": "Database connection failed"
}
```

### Prerequisite Error
```json
{
    "status": "failed",
    "message": "Tier 1 verification must be completed first"
}
```

---

## File Upload Requirements

### Supported Formats
- SVG
- PNG
- JPG/JPEG
- GIF

### Size Limits
- Maximum file size: 5MB
- Recommended dimensions: 800x400px or smaller

### File Types
- **Utility Bills**: Electricity, Waste/Water, Rent/Tenancy, Internet bills
- **Government IDs**: National ID Card, Voter's Card, Driver's License, International Passport

---

## User Levels and Access

### Tier 0 (Basic)
- Basic account functionality
- Limited transaction amounts

### Tier 1 (Verified)
- Personal information verified
- Basic transaction limits increased

### Tier 2 (Enhanced)
- Address and next of kin verified
- Higher transaction limits
- Access to savings features

### Tier 3 (Unlimited)
- Full KYC completed
- Unlimited access to all features
- Maximum transaction limits
- User level upgraded to "verified"

---

## Implementation Notes

1. **Progressive Verification**: Users must complete tiers in order (1 → 2 → 3)
2. **File Storage**: Documents are stored on DigitalOcean Spaces with CDN
3. **Data Validation**: All inputs are validated server-side
4. **Transaction Safety**: Database operations use transactions for data integrity
5. **Rate Limiting**: Consider implementing rate limiting for file uploads
6. **Security**: All endpoints require authentication and validate user ownership

---
