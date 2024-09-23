<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Electricity extends Model
{
    protected $table = "electric";

    protected $guarded = [];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('electricity')->exists($this->image)) {
            return Storage::disk('electricity')->url($this->image);
        }
        return "https://placehold.co/400";
    }

    public function deleteImage()
    {
        if ($this->image && Storage::disk('electricity')->exists($this->image)) {
            return Storage::disk('electricity')->delete($this->image);
        }
    }
}
