<!-- Profile Avatar -->
@if(file_exists(base_path(findAvatar($userinfo->id))))
    <img alt="avatar" id="avatar" class="rounded-avatar fadein" src="{{ url(findAvatar($userinfo->id)) }}" height="128" width="128" style="object-fit: cover;">
@elseif(file_exists(base_path('assets/linkstack/images/').findFile('avatar')))
    <img alt="avatar" id="avatar" class="fadein" src="{{ url('assets/linkstack/images/').'/'.findFile('avatar') }}" height="128" width="128" style="object-fit: cover;">
@else
    <img alt="avatar" id="avatar" class="fadein" src="{{ asset('assets/linkstack/images/logo.svg') }}" height="128" width="128" style="object-fit: cover;">
@endif
