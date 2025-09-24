<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminUserController extends Controller
{
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:blacklist,block,unblacklist,unblock',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $ids = $validated['user_ids'];
        $action = $validated['action'];

        switch ($action) {
            case 'blacklist':
                User::whereIn('id', $ids)->update(['is_blacklisted' => true]);
                break;
            case 'unblacklist':
                User::whereIn('id', $ids)->update(['is_blacklisted' => false]);
                break;
            case 'block':
                User::whereIn('id', $ids)->update(['post_no_debit' => true]);
                break;
            case 'unblock':
                User::whereIn('id', $ids)->update(['post_no_debit' => false]);
                break;
        }

        return response()->json(['message' => 'Bulk action performed successfully.']);
    }


    public function download(): StreamedResponse
    {
        $fileName = 'users_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $handle = fopen('php://output', 'w');

            // Add header row
            fputcsv($handle, ['Name', 'Email']);

            // Fetch users in chunks for memory efficiency
            User::whereRole('user')
            ->where('blocked_by_admin', false)
            ->where('is_blacklisted', false)
            ->where('post_no_debit', false)
            ->chunk(500, function($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, [$user->name, $user->email]);
                }
            });


            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

}
