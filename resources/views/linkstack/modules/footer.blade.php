@php
    use App\Models\LinktrUserStyle;
    use Illuminate\Support\Facades\Schema;

    $linktrFooterStyle = null;
    if (isset($userinfo) && Schema::hasTable('linktr_user_styles')) {
        $linktrFooterStyle = LinktrUserStyle::where('user_id', $userinfo->id)->first();
    }

    $showFooter = $linktrFooterStyle->show_footer ?? true;
    $footerText = $linktrFooterStyle->footer_text ?? 'Cookie Preferences · Report · Privacy · Explore · More';
@endphp

@if($showFooter)
    <div class="linktr-footer-text fadein">{{ $footerText }}</div>
@endif
