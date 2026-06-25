<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LinktrShareCard extends Model
{
    protected $table = 'linktr_share_cards';

    protected $fillable = [
        'user_id',
        'og_title',
        'og_description',
        'og_image_path',
        'twitter_title',
        'twitter_description',
        'twitter_image_path',
        'twitter_card_type',
        'telegram_domain_text',
        'telegram_title',
        'telegram_description',
        'telegram_image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ogImageUrl()
    {
        return $this->absoluteUrl($this->og_image_path);
    }

    public function twitterImageUrl()
    {
        return $this->absoluteUrl($this->twitter_image_path ?: $this->og_image_path);
    }

    public function telegramImageUrl()
    {
        return $this->absoluteUrl($this->telegram_image_path ?: $this->og_image_path);
    }

    protected function absoluteUrl($path)
    {
        if (empty($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return url(ltrim($path, '/'));
    }
}
