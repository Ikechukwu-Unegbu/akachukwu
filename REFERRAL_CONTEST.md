# Referral Contest API Endpoint

### API Usage
```bash
# Get leaderboard for contest ID 1
GET /api/referral-contest/leaderboard/1

# Get active contest
GET /api/referral-contest/active
```

## Data Structure

### Leaderboard Response Format
```json
{
    "status": true,
    "response": [
        {
            "username": "john_doe",
            "total_referred": 3,
            "users": ["alice123", "bob456", "charlie789"]
        },
        {
            "username": "mary_smith", 
            "total_referred": 2,
            "users": ["david_001", "emma_king"],
            "qualified_count": 2
        }
    ],
    "message": "Referrals retrieved successfully"
}
```

### Error Response Format
```json
{
    "status": false,
    "errors": [],
    "message": "No active referral contest found"
}
```
