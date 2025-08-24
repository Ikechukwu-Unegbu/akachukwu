# Referral Contest API Endpoint

### API Usage
```bash
# Get leaderboard for active contest
GET /api/referral-contest/leaderboard
```

## Data Structure_

### Leaderboard Response Format
```json
{
    "status": true,
    "response": {
        "john_doe": {
            "total_referred": 3,
            "users": ["alice123", "bob456", "charlie789"],
            "qualified_count": 2
        },
        "mary_smith": {
            "total_referred": 2,
            "users": ["david_001", "emma_king"],
            "qualified_count": 2
        }
    },
    "message": "Leaderboard retrieved successfully"
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

## Notes
- Only one referral contest can be active at a time
- The API automatically detects the active contest
- No contest ID parameter needed
- Users are filtered based on qualification conditions (NIN/BVN, funded account, billable transactions)
- Leaderboard is sorted by qualified count
