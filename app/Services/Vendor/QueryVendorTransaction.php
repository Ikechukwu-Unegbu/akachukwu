<?php

namespace App\Services\Vendor;

use App\Helpers\ApiHelper;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class QueryVendorTransaction
{
    public static function initializeQuery($id, $type)
    {
        $transactionTables = [
            'airtime'        => 'airtime_transactions',
            'cable'          => 'cable_transactions',
            'data'           => 'data_transactions',
            'electricity'    => 'electricity_transactions',
            'result_checker' => 'result_checker_transactions'
        ];
    
        // Check if the provided type exists in the mapping
        if (!isset($transactionTables[$type])) {
            // If type is invalid, return an error message
            // throw new \InvalidArgumentException("Invalid transaction type: {$type}");
            return response()->json(['status' => false, 'result' => [], 'msg' => "Unable to query {$type} transaction"])->getData();
        }

        // Get the corresponding table name for the provided type
        $tableName = $transactionTables[$type];

        $table =  DB::table($tableName)
        ->where('id', $id)
        ->first();

        if(!$table->api_response) {
            return response()->json(['status' => false, 'result' => [], 'msg' => 'No vendor API provided for this transaction.'])->getData();
        }
        
        $vendor = Vendor::find($table->vendor_id);
        // $vendor = Vendor::find(2);
        $vendorService =  VendorServiceFactory::make($vendor);
        return $vendorService::queryTransactionFromVendor($table, $type);
    }
}