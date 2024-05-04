<?php 
namespace App\Services;

use Spatie\Activitylog\Models\Activity;

class ActivityService{

    public function basic_log($text):void
    {
        activity()->log($text);
    }

    public function full_log($causeModel, $targetModel, $text, $event = null):void
    {
        activity()
        ->causedBy($causeModel)
        ->performedOn($targetModel)
        ->event($event)
        ->log($text);   
    }

    
    function compare_changes(Activity $activity):array {
        $old = $activity->properties['old'] ?? [];
        $new = $activity->properties['attributes'] ?? [];

        $changes = [];

        foreach ($new as $attribute => $value) {
            if (array_key_exists($attribute, $old)) {
                // If the attribute exists in both old and new, check if it has changed
                if ($old[$attribute] != $value) {
                    $changes[$attribute] = [
                        'old' => $old[$attribute],
                        'new' => $value
                    ];
                }
            } else {
                // If the attribute is new in the new data, record it as a change
                $changes[$attribute] = [
                    'old' => null,
                    'new' => $value
                ];
            }
        }

        // Check for attributes that were removed in the new data
        foreach ($old as $attribute => $value) {
            if (!array_key_exists($attribute, $new)) {
                $changes[$attribute] = [
                    'old' => $value,
                    'new' => null
                ];
            }
        }

        return $changes;
    }
}