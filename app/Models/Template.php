<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @method void prepareToAttachMedia(Media $media, FileAdder $fileAdder)
 */
class Template extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'subject',
        'body',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
