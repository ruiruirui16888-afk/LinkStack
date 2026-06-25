<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinktrUserStyle extends Model
{
    protected $table = 'linktr_user_styles';

    protected $fillable = [
        'user_id',
        'background_type',
        'background_color_1',
        'background_color_2',
        'background_color_3',
        'background_direction',
        'name_font',
        'bio_font',
        'category_font',
        'button_font',
        'footer_font',
        'name_color',
        'bio_color',
        'category_color',
        'button_text_color',
        'footer_color',
        'button_background_color',
        'button_shadow_color',
        'button_radius',
        'avatar_radius',
        'footer_text',
        'show_footer',
    ];

    protected $casts = [
        'button_radius' => 'integer',
        'avatar_radius' => 'integer',
        'show_footer' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fontStack($field)
    {
        $fonts = config('linktr_fonts', []);
        $key = $this->{$field} ?: 'default';

        return $fonts[$key]['stack'] ?? $fonts['default']['stack'] ?? 'Arial, sans-serif';
    }
}
