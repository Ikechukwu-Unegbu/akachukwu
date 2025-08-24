<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory;

       public const USAGES = [
        'MOBILE_APP_BANNER' => 'mobile-app-banner',
        'HERO_BANNER'       => 'hero-banner',
        'PROFILE_PICTURE'   => 'profile-picture',
        'DOCUMENT_UPLOAD'   => 'document-upload',
        'REPORT'            => 'report',
        'ADVERT'            => 'advert',
        'BLOG'              => 'blog',
    ];
}
