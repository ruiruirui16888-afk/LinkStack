<?php
use App\Models\LinktrCategory;
use App\Models\UserData;
use Illuminate\Support\Facades\Schema;
?>

@php
    $initial = 1;
    $hasLinktrTables = Schema::hasTable('linktr_categories');
    $buttonLinks = collect($links)->filter(function ($link) {
        if (($link->name ?? '') === 'icon') {
            return false;
        }
        if (isset($link->linktr_is_visible) && !$link->linktr_is_visible) {
            return false;
        }
        return true;
    });

    $categories = collect();
    if ($hasLinktrTables) {
        $categories = LinktrCategory::where('user_id', $userinfo->id)
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();
    }

    $presetIconClass = function ($preset, $buttonName) {
        $key = $preset ?: $buttonName;
        $map = [
            'x' => 'fa-brands fa-x-twitter',
            'twitter' => 'fa-brands fa-x-twitter',
            'x-twitter' => 'fa-brands fa-x-twitter',
            'telegram' => 'fa-brands fa-telegram',
            'instagram' => 'fa-brands fa-instagram',
            'youtube' => 'fa-brands fa-youtube',
            'website' => 'fa-solid fa-link',
            'link' => 'fa-solid fa-link',
            'cloud' => 'fa-solid fa-cloud',
            'payment' => 'fa-solid fa-credit-card',
            'star' => 'fa-solid fa-star',
            'heart' => 'fa-solid fa-heart',
            'lock' => 'fa-solid fa-lock',
        ];
        return $map[$key] ?? ('fa-solid fa-link');
    };

    $renderLinktrButton = function ($link) use (&$initial, $presetIconClass, $userinfo) {
        $target = isset($link->linktr_open_new_tab) ? (bool) $link->linktr_open_new_tab : (UserData::getData($userinfo->id, 'links-new-tab') != false);
        $nofollow = isset($link->linktr_nofollow) ? (bool) $link->linktr_nofollow : true;
        $rel = $nofollow ? 'noopener noreferrer nofollow noindex' : 'noopener noreferrer';
        $buttonStyle = '';
        if (!empty($link->linktr_button_bg_color)) {
            $buttonStyle .= '--linktr-button-bg:' . e($link->linktr_button_bg_color) . ';';
        }
        if (!empty($link->linktr_button_text_color)) {
            $buttonStyle .= '--linktr-button-text-color:' . e($link->linktr_button_text_color) . ';';
        }
        $iconBg = !empty($link->linktr_icon_bg_color) ? 'background:' . e($link->linktr_icon_bg_color) . ';' : '';
        $iconMode = $link->linktr_icon_mode ?? 'preset';
        $iconHtml = '';
        if ($iconMode === 'upload' && !empty($link->linktr_icon_path)) {
            $iconHtml = '<img alt="" src="' . e(url(ltrim($link->linktr_icon_path, '/'))) . '">';
        } elseif ($iconMode !== 'none') {
            $iconHtml = '<i class="' . e($presetIconClass($link->linktr_icon_preset ?? null, $link->name ?? null)) . '"></i>';
        }
        $href = e($link->link);
        $title = e($link->title);
        $targetAttr = $target ? ' target="_blank"' : '';
        $html = '<div style="--delay: ' . $initial++ . 's" class="button-entrance linktr-button-wrap">';
        $html .= '<a id="' . e($link->id) . '" class="button-click linktr-button" style="' . $buttonStyle . '" rel="' . $rel . '" href="' . $href . '"' . $targetAttr . '>';
        $html .= '<span class="linktr-button-icon" style="' . $iconBg . '">' . $iconHtml . '</span>';
        $html .= '<span class="linktr-button-title">' . $title . '</span>';
        $html .= '<span class="linktr-button-dots">•••</span>';
        $html .= '</a></div>';
        return $html;
    };
@endphp

@include('linkstack.modules.block-libraries', ['links' => $links])

@if($categories->count() > 0)
    @foreach($categories as $category)
        @php
            $categoryLinks = $buttonLinks->where('linktr_category_id', $category->id);
        @endphp
        @if($categoryLinks->count() > 0)
            <div class="linktr-category-title fadein">{{ $category->resolved_title }}</div>
            @foreach($categoryLinks as $link)
                @if(isset($link->custom_html) && $link->custom_html)
                    @php setBlockAssetContext($link->type); @endphp
                    @include('blocks::' . $link->type . '.display', ['link' => $link, 'initial' => $initial++])
                @else
                    {!! $renderLinktrButton($link) !!}
                @endif
            @endforeach
        @endif
    @endforeach

    @php $uncategorized = $buttonLinks->filter(fn($link) => empty($link->linktr_category_id)); @endphp
    @if($uncategorized->count() > 0)
        <div class="linktr-category-title fadein">Links | 链接入口</div>
        @foreach($uncategorized as $link)
            @if(isset($link->custom_html) && $link->custom_html)
                @php setBlockAssetContext($link->type); @endphp
                @include('blocks::' . $link->type . '.display', ['link' => $link, 'initial' => $initial++])
            @else
                {!! $renderLinktrButton($link) !!}
            @endif
        @endforeach
    @endif
@else
    @foreach($buttonLinks as $link)
        @if(isset($link->custom_html) && $link->custom_html)
            @php setBlockAssetContext($link->type); @endphp
            @include('blocks::' . $link->type . '.display', ['link' => $link, 'initial' => $initial++])
        @else
            {!! $renderLinktrButton($link) !!}
        @endif
    @endforeach
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function handleClickOrTouch(event) {
            var target = event.target.closest('.button-click');
            if (!target) return;
            var id = target.id;
            if (!sessionStorage.getItem('clicked-' + id)) {
                var url = '{{ route("clickNumber") }}/' + id;
                fetch(url, { method: 'GET', headers: { 'Content-Type': 'application/json' } });
                sessionStorage.setItem('clicked-' + id, 'true');
            }
        }

        document.addEventListener('mousedown', function (event) {
            if (event.button === 0 || event.button === 1) handleClickOrTouch(event);
        });
        document.addEventListener('touchstart', handleClickOrTouch);
    });
</script>
