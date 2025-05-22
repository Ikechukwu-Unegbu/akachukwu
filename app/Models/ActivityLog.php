<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use donatj\UserAgent\UserAgentParser;



class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_id',
        'resource_owner_id',
        'activity',
        'resource',
        'description',
        'type',
        'raw_request',
        'raw_response',
        'ip_address',
        'user_agent',
        'location',
        'balance_before',
        'balance_after',
        'tags',
        'ref_id',
    ];

    protected $casts = [
        'raw_request' => 'array',
        'raw_response' => 'array',
        'tags' => 'array',
        'location'=>'array',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    // Relationships

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function resourceOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resource_owner_id');
    }

    public function getUserAgentSummaryAttribute(): string
    {
        $parser = new UserAgentParser();
        $result = $parser->parse($this->user_agent);
    
        $browser = $result->browser(); // without version
        $platform = $result->platform() ?: 'Unknown Platform';
    
        return "{$browser} on {$platform}";
    }

    public function getTagsListAttribute(): string
    {
        $tags = is_array($this->tags) ? $this->tags : json_decode($this->tags, true);

        if (empty($tags)) {
            return 'N/A';
        }

        return implode(', ', $tags);
    }


    public function getModelDifferences(): array
    {
        // If both columns are empty
        if (empty($this->resource) && empty($this->new_resource)) {
            return ['status' => 'No data to compare'];
        }
    
        // Try to unserialize both (safely)
        $old = $this->safeUnserialize($this->resource);
        $new = $this->safeUnserialize($this->new_resource);
    
        // If either fails to unserialize to a model
        if (($old && !$old instanceof \Illuminate\Database\Eloquent\Model) ||
            ($new && !$new instanceof \Illuminate\Database\Eloquent\Model)) {
            return ['status' => 'Invalid serialized model(s)'];
        }
    
        // Only new model present (resource created)
        if (!$old && $new) {
            return collect($new->getAttributes())
                ->except(['created_at', 'updated_at'])
                ->mapWithKeys(fn($val, $key) => [$key => ['old' => null, 'new' => $val]])
                ->toArray();
        }
    
        // Only old model present (resource deleted)
        if ($old && !$new) {
            return collect($old->getAttributes())
                ->except(['created_at', 'updated_at'])
                ->mapWithKeys(fn($val, $key) => [$key => ['old' => $val, 'new' => null]])
                ->toArray();
        }
    
        // Both models exist — perform comparison
        if (get_class($old) !== get_class($new)) {
            return ['status' => 'Models must be of same type'];
        }
    
        $ignored = ['created_at', 'updated_at'];
        $differences = [];
    
        foreach ($new->getAttributes() as $key => $newVal) {
            if (in_array($key, $ignored)) continue;
    
            $oldVal = $old->getAttribute($key);
            if ($newVal != $oldVal) {
                $differences[$key] = [
                    'old' => $oldVal,
                    'new' => $newVal,
                ];
            }
        }
    
        return $differences ?: ['status' => 'No changes detected'];
    }
    
    protected function safeUnserialize(?string $data): mixed
    {
        try {
            return $data ? unserialize($data) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
    




    public function getFormattedLocationAttribute(): string
    {
        $location = is_string($this->location)
            ? json_decode($this->location, true)
            : $this->location;

        if (!$location || !is_array($location)) {
            return 'N/A';
        }

        $parts = array_filter([
            $location['city'] ?? null,
            $location['region'] ?? null,
            $location['country'] ?? null,
        ]);

        return count($parts) > 0 ? implode(', ', $parts) : 'N/A';
    }

}


