<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


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

}
