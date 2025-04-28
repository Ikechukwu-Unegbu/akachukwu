<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AnnouncementService;

class AnnouncementApiController extends Controller
{
    protected $announcementService;

    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    public function show()
    {
        $announcements = $this->announcementService->getActiveAnnouncements();

        return ApiHelper::sendResponse($announcements, 'Announcements Fetched Successfully');
    }
}
