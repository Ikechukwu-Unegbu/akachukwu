@extends('layouts.admin.app')

@section('title', 'Activity Breakdown Over Time')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">📈 Activity Breakdown by Type</h1>

    <div class="bg-white shadow rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">🔍 Activity Volume Comparison</h2>
            <div>
                <label for="timePeriod" class="mr-2 font-medium">Select Period:</label>
                <select id="timePeriod" class="border rounded px-3 py-1">
                    <option value="week" selected>Weekly</option>
                    <option value="month">Monthly</option>
                    <option value="year">Yearly</option>
                </select>
            </div>
        </div>
        <canvas id="activityBreakdownChart" height="140"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('activityBreakdownChart');
    const timePeriodSelect = document.getElementById('timePeriod');

    const chartDataSets = {
        week: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [
                { label: 'Airtime Purchase', data: [50, 65, 40, 70, 55, 30, 20], backgroundColor: '#3b82f6' },
                { label: 'Internet Data', data: [30, 45, 25, 60, 35, 20, 15], backgroundColor: '#10b981' },
                { label: 'Cable TV', data: [20, 15, 10, 25, 18, 10, 5], backgroundColor: '#f59e0b' },
                { label: 'Electricity Bills', data: [40, 50, 35, 45, 50, 25, 30], backgroundColor: '#8b5cf6' },
                { label: 'Money Transfers', data: [60, 80, 55, 90, 70, 35, 40], backgroundColor: '#ef4444' },
                { label: 'Savings', data: [15, 25, 20, 30, 25, 18, 12], backgroundColor: '#14b8a6' },
            ]
        },
        month: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [
                { label: 'Airtime Purchase', data: [220, 180, 240, 260], backgroundColor: '#3b82f6' },
                { label: 'Internet Data', data: [150, 170, 140, 160], backgroundColor: '#10b981' },
                { label: 'Cable TV', data: [60, 50, 55, 70], backgroundColor: '#f59e0b' },
                { label: 'Electricity Bills', data: [190, 210, 180, 200], backgroundColor: '#8b5cf6' },
                { label: 'Money Transfers', data: [280, 300, 320, 350], backgroundColor: '#ef4444' },
                { label: 'Savings', data: [70, 65, 80, 90], backgroundColor: '#14b8a6' },
            ]
        },
        year: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                { label: 'Airtime Purchase', data: [800, 750, 820, 870, 900, 880, 910, 920, 870, 860, 830, 790], backgroundColor: '#3b82f6' },
                { label: 'Internet Data', data: [600, 580, 610, 630, 640, 620, 650, 660, 640, 630, 610, 590], backgroundColor: '#10b981' },
                { label: 'Cable TV', data: [240, 230, 250, 260, 270, 255, 265, 270, 260, 250, 240, 230], backgroundColor: '#f59e0b' },
                { label: 'Electricity Bills', data: [700, 710, 730, 750, 760, 740, 770, 780, 760, 750, 740, 720], backgroundColor: '#8b5cf6' },
                { label: 'Money Transfers', data: [1000, 1050, 1080, 1100, 1150, 1130, 1170, 1200, 1180, 1160, 1140, 1110], backgroundColor: '#ef4444' },
                { label: 'Savings', data: [300, 320, 330, 340, 350, 360, 370, 380, 370, 360, 350, 340], backgroundColor: '#14b8a6' },
            ]
        }
    };

    let chart = new Chart(ctx, {
        type: 'bar',
        data: chartDataSets['week'],
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                title: {
                    display: true,
                    text: 'User Activity Volume by Type',
                    font: { size: 18 }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
            },
            scales: {
                x: { stacked: true },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Transactions'
                    }
                }
            }
        }
    });

    timePeriodSelect.addEventListener('change', function () {
        const selected = this.value;
        chart.data = chartDataSets[selected];
        chart.update();
    });
});
</script>
@endpush
