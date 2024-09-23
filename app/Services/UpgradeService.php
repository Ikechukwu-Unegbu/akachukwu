<?php 
namespace App\Services;

use App\Helpers\GeneralHelpers;
use App\Models\Utility\UpgradeRequest;
use Illuminate\Support\Facades\Auth;

class UpgradeService{

    public function __construct()
    {
        
    }

    public function requestUpgrade():void
    {
        $requestModel = new UpgradeRequest();
        $requestModel->uuid = GeneralHelpers::generateUniqueUuid('upgrade_requests');
        $requestModel->user_id = Auth::user()->id;
        $requestModel->save();
    }
}