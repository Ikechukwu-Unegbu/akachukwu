<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function deletePostImage() : bool
    {        
        if (!is_null($this->featured_image) && !empty($this->featured_image)) {
            $path = env('DO_FOLDER') . '/' . basename($this->featured_image);
            if (Storage::disk('do')->exists($path)) {
                Storage::disk('do')->delete($path);
                return true;
            }
        }

        return false;
    }

}
