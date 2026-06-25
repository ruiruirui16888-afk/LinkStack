<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinktrCategory extends Model
{
    protected $table = 'linktr_categories';

    protected $fillable = [
        'user_id',
        'title',
        'subtitle',
        'display_title',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class, 'linktr_category_id')->orderBy('order', 'asc');
    }

    public function getResolvedTitleAttribute()
    {
        if (!empty($this->display_title)) {
            return $this->display_title;
        }

        if (!empty($this->subtitle)) {
            return trim($this->title . ' | ' . $this->subtitle);
        }

        return $this->title;
    }
}
