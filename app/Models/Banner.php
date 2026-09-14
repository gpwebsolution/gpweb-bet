<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'type',
        'description',
        'link',
        'sort_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function imageUrl(): string
    {
        if (str_starts_with($this->image, 'assets/')) {
            return asset($this->image);
        }
        return asset('storage/'.$this->image);
    }
}
