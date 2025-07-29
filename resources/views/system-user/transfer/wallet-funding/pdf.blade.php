<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Funding Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0018A8;
            padding-bottom: 20px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .title {
            color: #0018A8;
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }

        .filter-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #0018A8;
        }

        .filter-summary h3 {
            color: #0018A8;
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .filter-summary p {
            margin: 5px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        th {
            background-color: #0018A8;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-successful {
            background-color: #d4edda;
            color: #155724;
        }

        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-refunded {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
            font-size: 12px;
            width: 100%;
        }

        .stat-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #ddd;
            min-height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #0018A8;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 11px;
            color: #666;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/vastel-logo.png') }}" alt="Vastel Logo" class="logo">
        <div class="title">Wallet Funding Report</div>
        <div class="subtitle">Generated on {{ date('F d, Y \a\t h:i A') }}</div>
        <div class="subtitle">Total Records: {{ $transactions->count() }}</div>
    </div>

    @if (!empty($filterSummary))
        <div class="filter-summary">
            <h3>Applied Filters:</h3>
            @foreach ($filterSummary as $filter)
                <p>{{ $filter }}</p>
            @endforeach
        </div>
    @endif

    <div class="summary-stats">
        <div class="stat-box">
            <div class="stat-number">{{ $transactions->count() }}</div>
            <div class="stat-label">Total Transactions</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">N{{ number_format($transactions->sum('amount'), 2) }}</div>
            <div class="stat-label">Total Amount</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $transactions->where('vendor_status', 'successful')->count() }}</div>
            <div class="stat-label">Successful</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $transactions->where('vendor_status', 'failed')->count() }}</div>
            <div class="stat-label">Failed</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Transaction ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Gateway</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->transaction_id }}</td>
                    <td>{{ $transaction->username ?? 'N/A' }}</td>
                    <td>{{ $transaction->email ?? 'N/A' }}</td>
                    <td>N{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ ucfirst($transaction->vendor) }}</td>
                    <td>
                        <span class="status-badge status-{{ $transaction->vendor_status }}">
                            {{ ucfirst($transaction->vendor_status) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated by Vastel Admin Panel</p>
        <p>© {{ date('Y') }} Vastel. All rights reserved.</p>
    </div>
</body>

</html>
