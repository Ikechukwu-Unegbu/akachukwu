<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'stie_title',
        'site_logo',
        'email',
        'phone1',
        'phone2',
        'total_users',
        'name',
        'twitter',
        'facebook',
        'instagram',
        'linkedin',
    ];
}
