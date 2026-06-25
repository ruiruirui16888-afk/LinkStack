<meta charset="utf-8">

@php
    use App\Models\LinktrShareCard;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Str;

    $relMe = "mastodon, firefish, streams";
    $relMeList = explode(', ', $relMe);

    $linktrShareCard = null;
    if (isset($userinfo) && Schema::hasTable('linktr_share_cards')) {
        $linktrShareCard = LinktrShareCard::where('user_id', $userinfo->id)->first();
    }

    $cleanDescription = strip_tags($userinfo->littlelink_description);
    $pageUrl = url($littlelink_name);

    $imageUrl = null;
    $resolveImage = function ($path) {
        if (empty($path)) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        return url(ltrim($path, '/'));
    };

    if ($linktrShareCard && !empty($linktrShareCard->og_image_path)) {
        $imageUrl = $resolveImage($linktrShareCard->og_image_path);
    } elseif (file_exists(base_path(findAvatar($userinfo->id)))) {
        $imageUrl = url(findAvatar($userinfo->id));
    } elseif (file_exists(base_path('assets/linkstack/images/').findFile('avatar'))) {
        $imageUrl = url('assets/linkstack/images/').'/'.findFile('avatar');
    } else {
        $imageUrl = asset('assets/linkstack/images/logo.svg');
    }

    $twitterImageUrl = $imageUrl;
    if ($linktrShareCard && !empty($linktrShareCard->twitter_image_path)) {
        $twitterImageUrl = $resolveImage($linktrShareCard->twitter_image_path);
    }

    $ogTitle = $linktrShareCard->og_title ?? $userinfo->name;
    $ogDescription = $linktrShareCard->og_description ?? $cleanDescription;
    $twitterTitle = $linktrShareCard->twitter_title ?? $ogTitle;
    $twitterDescription = $linktrShareCard->twitter_description ?? $ogDescription;
    $twitterCardType = $linktrShareCard->twitter_card_type ?? 'summary_large_image';
@endphp

{{-- Fediverse rel="me" links --}}
@foreach($links as $link)
  @if(in_array($link->name, $relMeList))
    <link href="{{$link->link}}" rel="me">
  @endif
@endforeach

@if(env('CUSTOM_META_TAGS') == 'true')
  @include('layouts.meta')
@else
  <meta name="description" content="{{ $ogDescription }}">
  <meta name="author" content="{{ $userinfo->name }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
@endif

<!-- Linktr One social preview meta -->
<meta property="og:url" content="{{ $pageUrl }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:image" content="{{ $imageUrl }}">
<meta property="og:image:alt" content="{{ $ogTitle }}">

<meta name="twitter:card" content="{{ $twitterCardType }}">
<meta property="twitter:domain" content="{{ parse_url(url(''), PHP_URL_HOST) }}">
<meta property="twitter:url" content="{{ $pageUrl }}">
<meta name="twitter:title" content="{{ $twitterTitle }}">
<meta name="twitter:description" content="{{ $twitterDescription }}">
<meta name="twitter:image" content="{{ $twitterImageUrl }}">

@if(config('advanced-config.linkstack_title') != '' and env('HOME_URL') === '')
<title>{{ $userinfo->name }} {{ config('advanced-config.linkstack_title') }}</title>
@elseif(env('CUSTOM_META_TAGS') == 'true' and config('advanced-config.title') != '')
<title>{{ config('advanced-config.title') }}</title>
@elseif(env('HOME_URL') != '')
<title>{{ $userinfo->name }}</title>
@else
<title>{{ $userinfo->name }} 🔗 {{ config('app.name') }} </title>
@endif

@include('components.favicon')
@include('components.favicon-extension')

@if(file_exists(base_path('assets/linkstack/images/').findFile('favicon')))
<link rel="icon" type="image/png" href="{{ asset('assets/linkstack/images/'.findFile('favicon')) }}">
@else
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/linkstack/images/logo.svg') }}">
@endif

@include('layouts.analytics')
