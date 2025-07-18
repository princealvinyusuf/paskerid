<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicData extends Model
{
    use HasFactory;

    protected $table = 'topic_data';

    protected $fillable = [
        'title',
        'description',
        'date',
        'file_url',
        'image_url',
    ];
} 