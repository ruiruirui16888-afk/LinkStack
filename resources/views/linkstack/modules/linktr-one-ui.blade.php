@php
    use App\Models\LinktrUserStyle;
    use Illuminate\Support\Facades\Schema;

    $defaultStyle = [
        'background_color_1' => '#df9fac',
        'background_color_2' => '#ffc8f4',
        'background_color_3' => null,
        'background_direction' => '180deg',
        'name_font' => 'default',
        'bio_font' => 'default',
        'category_font' => 'default',
        'button_font' => 'default',
        'footer_font' => 'default',
        'name_color' => '#513246',
        'bio_color' => '#513246',
        'category_color' => '#513246',
        'button_text_color' => '#513246',
        'footer_color' => 'rgba(81, 50, 70, 0.72)',
        'button_background_color' => '#ffffff',
        'button_shadow_color' => 'rgba(69, 33, 52, 0.16)',
        'button_radius' => 32,
        'avatar_radius' => 999,
    ];

    $linktrStyle = null;
    if (isset($userinfo) && Schema::hasTable('linktr_user_styles')) {
        $linktrStyle = LinktrUserStyle::where('user_id', $userinfo->id)->first();
    }

    $fonts = config('linktr_fonts', []);
    $value = function ($key) use ($linktrStyle, $defaultStyle) {
        return $linktrStyle->{$key} ?? $defaultStyle[$key] ?? '';
    };
    $font = function ($field) use ($linktrStyle, $fonts) {
        $key = $linktrStyle->{$field} ?? 'default';
        return $fonts[$key]['stack'] ?? ($fonts['default']['stack'] ?? '-apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif');
    };

    $gradientStops = $value('background_color_1') . ' 0%, ' . $value('background_color_2') . ' 100%';
    if (!empty($value('background_color_3'))) {
        $gradientStops = $value('background_color_1') . ' 0%, ' . $value('background_color_2') . ' 58%, ' . $value('background_color_3') . ' 100%';
    }
@endphp

<style>
    :root {
        --linktr-name-font: {!! $font('name_font') !!};
        --linktr-bio-font: {!! $font('bio_font') !!};
        --linktr-category-font: {!! $font('category_font') !!};
        --linktr-button-font: {!! $font('button_font') !!};
        --linktr-footer-font: {!! $font('footer_font') !!};
        --linktr-name-color: {{ $value('name_color') }};
        --linktr-bio-color: {{ $value('bio_color') }};
        --linktr-category-color: {{ $value('category_color') }};
        --linktr-button-text-color: {{ $value('button_text_color') }};
        --linktr-button-bg: {{ $value('button_background_color') }};
        --linktr-button-shadow: {{ $value('button_shadow_color') }};
        --linktr-button-radius: {{ (int) $value('button_radius') }}px;
        --linktr-avatar-radius: {{ (int) $value('avatar_radius') }}px;
        --linktr-footer-color: {{ $value('footer_color') }};
    }

    html, body {
        min-height: 100%;
    }

    body {
        background: linear-gradient({{ $value('background_direction') }}, {!! $gradientStops !!}) !important;
        background-attachment: fixed !important;
        color: var(--linktr-bio-color);
    }

    body:before {
        content: "";
        position: fixed;
        inset: 0;
        pointer-events: none;
        background:
            radial-gradient(circle at 18% 12%, rgba(255,255,255,.34), transparent 28%),
            radial-gradient(circle at 82% 18%, rgba(255,255,255,.18), transparent 24%);
        z-index: -1;
    }

    .container {
        max-width: 620px !important;
        width: min(100% - 28px, 620px) !important;
        word-break: break-word;
    }

    .column {
        margin-top: 36px !important;
    }

    #avatar {
        width: 104px !important;
        height: 104px !important;
        min-width: 104px !important;
        border-radius: var(--linktr-avatar-radius) !important;
        object-fit: cover !important;
        border: 4px solid rgba(255,255,255,.72);
        box-shadow: 0 14px 40px rgba(81, 50, 70, .20);
        display: block;
        margin: 0 auto 18px;
    }

    h1 {
        color: var(--linktr-name-color) !important;
        font-family: var(--linktr-name-font) !important;
        font-size: 22px !important;
        font-weight: 800 !important;
        line-height: 1.28 !important;
        text-align: center !important;
        margin: 0 0 10px !important;
        letter-spacing: .01em;
    }

    .description-parent {
        padding-bottom: 18px !important;
    }

    .description-parent, .description-parent p, .description-parent * {
        color: var(--linktr-bio-color) !important;
        font-family: var(--linktr-bio-font) !important;
        font-size: 15px !important;
        line-height: 1.65 !important;
        margin-bottom: 0 !important;
        text-align: center;
    }

    .social-icon-div {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 14px !important;
        margin: 6px 0 26px !important;
    }

    .social-link {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        display: inline-flex !important;
        justify-content: center;
        align-items: center;
        background: rgba(255,255,255,.48);
        color: var(--linktr-name-color) !important;
        text-decoration: none !important;
        box-shadow: 0 8px 22px rgba(81, 50, 70, .12);
        backdrop-filter: blur(8px);
    }

    .social-icon {
        font-size: 20px !important;
        color: var(--linktr-name-color) !important;
    }

    .linktr-category-title {
        color: var(--linktr-category-color);
        font-family: var(--linktr-category-font);
        font-size: 14px;
        font-weight: 800;
        letter-spacing: .02em;
        text-align: center;
        margin: 25px 0 12px;
    }

    .linktr-button-wrap {
        margin: 12px 0;
    }

    .linktr-button {
        min-height: 64px;
        border-radius: var(--linktr-button-radius) !important;
        background: var(--linktr-button-bg) !important;
        color: var(--linktr-button-text-color) !important;
        font-family: var(--linktr-button-font) !important;
        font-size: 15px !important;
        font-weight: 800 !important;
        line-height: 1.25 !important;
        text-decoration: none !important;
        display: grid !important;
        grid-template-columns: 48px 1fr 32px;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 8px 15px 8px 10px;
        box-shadow: 0 12px 28px var(--linktr-button-shadow);
        border: 1px solid rgba(255,255,255,.72);
        transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .linktr-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 34px var(--linktr-button-shadow);
    }

    .linktr-button-icon {
        width: 48px;
        height: 48px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: rgba(239, 166, 190, .26);
        color: var(--linktr-button-text-color);
        font-size: 21px;
    }

    .linktr-button-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .linktr-button-title {
        text-align: center;
        overflow-wrap: anywhere;
    }

    .linktr-button-dots {
        display: inline-flex;
        justify-content: flex-end;
        color: rgba(81, 50, 70, .42);
        font-size: 22px;
        letter-spacing: -1px;
    }

    .linktr-footer-text {
        font-family: var(--linktr-footer-font);
        color: var(--linktr-footer-color);
        font-size: 12px;
        line-height: 1.6;
        text-align: center;
        margin: 34px auto 28px;
        max-width: 520px;
    }

    .linktr-card-preview {
        width: min(100%, 557px);
        min-height: 146px;
        margin: 20px auto;
        border: 1px solid #cfdce5;
        border-radius: 14px;
        background: #f8fbfc;
        display: grid;
        grid-template-columns: 140px 1fr;
        overflow: hidden;
        text-align: left;
    }

    .linktr-card-preview img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        margin: 3px;
        border-radius: 10px;
    }

    .linktr-card-preview-body {
        padding: 12px 14px;
        color: #1f2933;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
    }

    .linktr-card-domain { color: #6b7c8f; font-size: 13px; margin-bottom: 5px; }
    .linktr-card-title { font-weight: 700; font-size: 15px; line-height: 1.35; margin-bottom: 6px; }
    .linktr-card-desc { color: #536170; font-size: 13px; line-height: 1.35; }

    @media (max-width: 520px) {
        .container { width: min(100% - 22px, 620px) !important; }
        .column { margin-top: 30px !important; }
        #avatar { width: 96px !important; height: 96px !important; min-width: 96px !important; }
        .linktr-button { grid-template-columns: 44px 1fr 28px; min-height: 60px; padding-left: 8px; }
        .linktr-button-icon { width: 44px; height: 44px; }
    }
</style>
