<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkinGalleryComment extends Model
{
    use HasFactory;

    protected $table = 'walkin_gallery_comments';

    protected $fillable = [
        'walkin_gallery_item_id',
        'company_name',
        'name',
        'comment',
        'status',
        'ip_address',
        'user_agent',
    ];

    public function item()
    {
        return $this->belongsTo(WalkinGalleryItem::class, 'walkin_gallery_item_id');
    }
}



