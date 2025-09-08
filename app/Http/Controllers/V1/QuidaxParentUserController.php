<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Payment\Crypto\QuidaxParentUserService;

class QuidaxParentUserController extends Controller
{

    public $parentService; 

    public function __construct(QuidaxParentUserService $parentService)
    {
        $this->parentService = $parentService;
    }


    public function getAllParentWallets()
    {
        return $this->parentService->getAllParentUserWalles();
    }

    public function createAllParentWallets()
    {
        return $this->parentService->createAllParentWallets();
        
    }

}
