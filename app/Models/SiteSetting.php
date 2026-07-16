<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'logo_url',
        'hero_image_url',
        'whatsapp_digits',
        'instagram_url',
        'phone_display',
        'address_line',
        'contact_email',
    ];
}
