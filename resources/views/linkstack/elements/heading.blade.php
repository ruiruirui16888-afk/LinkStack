<?php use App\Models\UserData; ?>
<!-- Profile Name -->
<h1 class="fadein linktr-profile-name">
    {{ $info->name }}
    @if(($userinfo->role == 'vip' or $userinfo->role == 'admin') and theme('disable_verification_badge') != "true" and env('HIDE_VERIFICATION_CHECKMARK') != true and UserData::getData($userinfo->id, 'checkmark') != false)
        <span title="{{__('messages.Verified user')}}">@include('components.verify-svg')</span>
    @endif
</h1>
