<?php 
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GeneralHelpers{
        /**
     * Generate a unique UUID for the given table.
     *
     * @param string $table
     * @return string
     */
    public static function generateUniqueUuid(string $table): string
    {
        $uuid = Str::uuid();

        while (DB::table($table)->where('uuid', $uuid)->exists()) {
            $uuid = Str::uuid();
        }

        return $uuid;
    }
    public static function generateUniqueRef(string $table): string
    {
        $uuid = Str::uuid();

        while (DB::table($table)->where('reference_id', $uuid)->exists()) {
            $uuid = Str::uuid();
        }

        return $uuid;
    }

}