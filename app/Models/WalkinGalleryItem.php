<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkinGalleryItem extends Model
{
    use HasFactory;

    protected $table = 'walkin_gallery_items';

    protected $fillable = [
        'type',
        'company_name',
        'title',
        'caption',
        'media_path',
        'thumbnail_path',
        'embed_url',
        'embed_thumbnail_url',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function comments()
    {
        return $this->hasMany(WalkinGalleryComment::class, 'walkin_gallery_item_id');
    }
}



