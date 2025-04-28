<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Announcement;

class AnnouncementService
{
    public static function getActiveAnnouncements()
    {
        $now = Carbon::now();

        return Announcement::query()
            ->where('is_active', true)
            ->where('start_at', '<=', $now)
            ->where('end_at', '>=', $now)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
