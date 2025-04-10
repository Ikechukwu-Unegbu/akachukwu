<?php

namespace App\Traits;

trait HasStatusText
{
    public function initializeHasStatusText()
    {
        $this->appends = array_unique(array_merge(
            $this->appends ?? [],
            ['text_status']
        ));
    }

    public function getTextStatusAttribute()
    {
        return $this->vendor_status ?? $this->api_status ?? null;
    }
}