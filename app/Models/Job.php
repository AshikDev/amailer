<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'template_id',
        'send_as'
    ];

    protected $casts = [
        'is_sent' => 'boolean'
    ];

    public static function getSendAsOptions(): array
    {
        return [
            'Separately' => 'Separately',
            'Bulk' => 'Bulk',
            'CC' => 'CC',
            'BCC' => 'BCC'
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
