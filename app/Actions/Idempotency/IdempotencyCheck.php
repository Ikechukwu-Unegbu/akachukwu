<?php

namespace App\Actions\Idempotency;

use Carbon\Carbon;

class IdempotencyCheck
{
/**
     * Check for a recent duplicate transaction across various transaction types.
     *
     * @param string $modelClass Fully qualified model class name (e.g., App\Models\AirtimeTransaction)
     * @param array $conditions Array of conditions to match (e.g., ['mobile_number' => '1234'])
     * @param string $statusColumn The column name for status checks (default: 'status')
     * @param array $statusValues Status values to consider as duplicates (e.g., ['pending', 2])
     * @param int $timeWindow Time in minutes to look back for duplicates
     * @return mixed|null Returns the found transaction or null if no duplicate is found
     */
    public static function checkDuplicateTransaction(
        string $modelClass,
        array $conditions,
        string $statusColumn = 'vendor_status',
        array $statusValues = ['pending', 'refunded', 'successful'],
        int $timeWindow = 1
    ) {
        // Ensure the model class exists
        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model class {$modelClass} does not exist.");
        }

        // Define the time range for duplicate check
        $timeLimit = Carbon::now()->subMinutes($timeWindow);

        // Query the model dynamically
        $query = $modelClass::query();

        // Apply conditions dynamically
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }

        // Apply status and time window filters
        $query->whereIn($statusColumn, $statusValues)
              ->where('created_at', '>=', $timeLimit);
        
        return $query->first();
    }
}