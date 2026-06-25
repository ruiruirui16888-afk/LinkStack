@extends('layouts.sidebar')

@section('content')
@php
    $telegramImage = $shareCard->telegram_image_path ?: $shareCard->og_image_path;
    $telegramImageUrl = $telegramImage ? url(ltrim($telegramImage, '/')) : asset('assets/linkstack/images/logo.svg');
    $telegramDomain = $shareCard->telegram_domain_text ?: parse_url(url(''), PHP_URL_HOST);
    $telegramTitle = $shareCard->telegram_title ?: ($shareCard->og_title ?: $user->name);
    $telegramDescription = $shareCard->telegram_description ?: ($shareCard->og_description ?: strip_tags($user->littlelink_description));
@endphp

<div class="container-fluid">
    <h1 class="mb-4">Linktr One 设置</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header"><strong>页面地址</strong></div>
        <div class="card-body">
            <p class="mb-1">用户名：<strong>{{ $user->littlelink_name }}</strong></p>
            <p class="mb-0">前台地址：<code>{{ url($user->littlelink_name) }}</code></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>渐变背景 / 字体 / 颜色</strong></div>
        <div class="card-body">
            <form method="post" action="{{ route('linktr.one.style.update') }}">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>渐变颜色 1</label>
                        <input type="color" class="form-control" name="background_color_1" value="{{ $style->background_color_1 }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>渐变颜色 2</label>
                        <input type="color" class="form-control" name="background_color_2" value="{{ $style->background_color_2 }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>渐变颜色 3（可空）</label>
                        <input type="text" class="form-control" name="background_color_3" value="{{ $style->background_color_3 }}" placeholder="#ffffff">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>渐变方向</label>
                        <input type="text" class="form-control" name="background_direction" value="{{ $style->background_direction }}" placeholder="180deg">
                    </div>
                </div>

                <div class="row">
                    @foreach(['name_font' => '昵称字体', 'bio_font' => '简介字体', 'category_font' => '分类字体', 'button_font' => '按钮字体', 'footer_font' => '页脚字体'] as $field => $label)
                        <div class="col-md-4 mb-3">
                            <label>{{ $label }}</label>
                            <select class="form-control" name="{{ $field }}">
                                @foreach($fonts as $key => $font)
                                    <option value="{{ $key }}" @selected($style->{$field} === $key)>{{ $font['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    @foreach(['name_color' => '昵称颜色', 'bio_color' => '简介颜色', 'category_color' => '分类颜色', 'button_text_color' => '按钮文字颜色'] as $field => $label)
                        <div class="col-md-3 mb-3">
                            <label>{{ $label }}</label>
                            <input type="color" class="form-control" name="{{ $field }}" value="{{ $style->{$field} }}">
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>按钮背景色</label>
                        <input type="color" class="form-control" name="button_background_color" value="{{ $style->button_background_color }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>按钮阴影色</label>
                        <input type="text" class="form-control" name="button_shadow_color" value="{{ $style->button_shadow_color }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>按钮圆角</label>
                        <input type="number" class="form-control" name="button_radius" value="{{ $style->button_radius }}" min="0" max="64">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>头像圆角</label>
                        <input type="number" class="form-control" name="avatar_radius" value="{{ $style->avatar_radius }}" min="0" max="999">
                    </div>
                </div>

                <div class="mb-3">
                    <label>页脚文字（不可点击）</label>
                    <input type="text" class="form-control" name="footer_text" value="{{ $style->footer_text }}" placeholder="Cookie Preferences · Report · Privacy · Explore · More">
                </div>
                <div class="mb-3">
                    <label>页脚颜色</label>
                    <input type="text" class="form-control" name="footer_color" value="{{ $style->footer_color }}">
                </div>
                <input type="hidden" name="show_footer" value="0">
                <label class="mb-3 d-block"><input type="checkbox" name="show_footer" value="1" @checked($style->show_footer)> 显示页脚</label>
                <button class="btn btn-primary">保存外观设置</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>分类管理</strong></div>
        <div class="card-body">
            <form method="post" action="{{ route('linktr.one.category.store') }}" class="mb-3">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-3"><input class="form-control" name="title" placeholder="Social Media"></div>
                    <div class="col-md-3 mb-3"><input class="form-control" name="subtitle" placeholder="社交媒体"></div>
                    <div class="col-md-3 mb-3"><input class="form-control" name="display_title" placeholder="Social Media | 社交媒体"></div>
                    <div class="col-md-2 mb-3"><input class="form-control" type="number" name="sort_order" value="0"></div>
                    <div class="col-md-1 mb-3"><button class="btn btn-success w-100">新增</button></div>
                </div>
                <input type="hidden" name="is_visible" value="1">
            </form>

            <form method="post" action="{{ route('linktr.one.category.defaults') }}" class="mb-4" onsubmit="return confirm('创建默认分类模板？已存在的默认分类不会重复创建。')">
                @csrf
                <button class="btn btn-outline-primary btn-sm">一键创建默认分类：Social Media / Video Preview / Payment</button>
            </form>

            <table class="table table-bordered">
                <thead><tr><th>ID</th><th>显示标题</th><th>排序</th><th>状态</th><th>操作</th></tr></thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->resolved_title }}</td>
                        <td>{{ $category->sort_order }}</td>
                        <td>{{ $category->is_visible ? '显示' : '隐藏' }}</td>
                        <td>
                            <form method="post" action="{{ route('linktr.one.category.remove', $category->id) }}" onsubmit="return confirm('确认删除这个分类？分类下链接不会删除，只会取消分类。')">
                                @csrf
                                <button class="btn btn-sm btn-danger">删除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>链接入口增强：分类 / 图标 / 按钮颜色</strong></div>
        <div class="card-body">
            @if($links->count() === 0)
                <p class="text-muted mb-0">暂无链接。请先到「Links」添加链接入口。</p>
            @endif

            @foreach($links as $link)
                @if(($link->type ?? '') !== 'icon')
                    <form class="border rounded p-3 mb-3" method="post" action="{{ route('linktr.one.link.update', $link->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong>{{ $link->title }}</strong>
                                <div class="text-muted small">{{ $link->link }}</div>
                            </div>
                            @if(!empty($link->linktr_icon_path))
                                <img src="{{ url(ltrim($link->linktr_icon_path, '/')) }}" alt="" style="width:44px;height:44px;border-radius:50%;object-fit:cover;">
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>所属分类</label>
                                <select class="form-control" name="linktr_category_id">
                                    <option value="">无分类</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected($link->linktr_category_id == $category->id)>{{ $category->resolved_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>图标模式</label>
                                <select class="form-control" name="linktr_icon_mode">
                                    <option value="preset" @selected(($link->linktr_icon_mode ?? 'preset') === 'preset')>预设图标</option>
                                    <option value="upload" @selected(($link->linktr_icon_mode ?? '') === 'upload')>上传图标</option>
                                    <option value="none" @selected(($link->linktr_icon_mode ?? '') === 'none')>不显示图标</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>预设图标</label>
                                <select class="form-control" name="linktr_icon_preset">
                                    <option value="">默认</option>
                                    @foreach($iconPresets as $key => $preset)
                                        <option value="{{ $key }}" @selected(($link->linktr_icon_preset ?? '') === $key)>{{ $preset['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>上传图标</label>
                                <input type="file" class="form-control" name="linktr_icon">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>图标背景色</label>
                                <input type="text" class="form-control" name="linktr_icon_bg_color" value="{{ $link->linktr_icon_bg_color }}" placeholder="rgba(239,166,190,.26)">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>按钮背景色</label>
                                <input type="text" class="form-control" name="linktr_button_bg_color" value="{{ $link->linktr_button_bg_color }}" placeholder="#ffffff">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>按钮文字颜色</label>
                                <input type="text" class="form-control" name="linktr_button_text_color" value="{{ $link->linktr_button_text_color }}" placeholder="#513246">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="d-block">状态</label>
                                <input type="hidden" name="linktr_is_visible" value="0">
                                <input type="hidden" name="linktr_open_new_tab" value="0">
                                <input type="hidden" name="linktr_nofollow" value="0">
                                <label class="mr-2"><input type="checkbox" name="linktr_is_visible" value="1" @checked($link->linktr_is_visible ?? true)> 显示</label>
                                <label class="mr-2"><input type="checkbox" name="linktr_open_new_tab" value="1" @checked($link->linktr_open_new_tab ?? true)> 新窗口</label>
                                <label><input type="checkbox" name="linktr_nofollow" value="1" @checked($link->linktr_nofollow ?? true)> nofollow</label>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm">保存这个链接入口</button>
                    </form>
                @endif
            @endforeach
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>X / Telegram 分享卡片</strong></div>
        <div class="card-body">
            <form method="post" action="{{ route('linktr.one.share-card.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3"><label>OG 标题</label><input class="form-control" name="og_title" value="{{ $shareCard->og_title }}"></div>
                    <div class="col-md-6 mb-3"><label>OG 图片</label><input type="file" class="form-control" name="og_image"></div>
                </div>
                <div class="mb-3"><label>OG 描述</label><textarea class="form-control" name="og_description">{{ $shareCard->og_description }}</textarea></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>X 标题</label><input class="form-control" name="twitter_title" value="{{ $shareCard->twitter_title }}"></div>
                    <div class="col-md-6 mb-3"><label>X 图片</label><input type="file" class="form-control" name="twitter_image"></div>
                </div>
                <div class="mb-3"><label>X 描述</label><textarea class="form-control" name="twitter_description">{{ $shareCard->twitter_description }}</textarea></div>
                <div class="mb-3">
                    <label>X Card 类型</label>
                    <select class="form-control" name="twitter_card_type">
                        <option value="summary_large_image" @selected($shareCard->twitter_card_type === 'summary_large_image')>summary_large_image</option>
                        <option value="summary" @selected($shareCard->twitter_card_type === 'summary')>summary</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3"><label>Telegram 域名文字</label><input class="form-control" name="telegram_domain_text" value="{{ $shareCard->telegram_domain_text }}"></div>
                    <div class="col-md-4 mb-3"><label>Telegram 标题</label><input class="form-control" name="telegram_title" value="{{ $shareCard->telegram_title }}"></div>
                    <div class="col-md-4 mb-3"><label>Telegram 图片</label><input type="file" class="form-control" name="telegram_image"></div>
                </div>
                <div class="mb-3"><label>Telegram 描述</label><textarea class="form-control" name="telegram_description">{{ $shareCard->telegram_description }}</textarea></div>
                <button class="btn btn-primary">保存分享卡片</button>
            </form>

            <div class="mt-4">
                <h5>Telegram 卡片仿真预览</h5>
                <div style="width:min(100%,557px);min-height:146px;border:1px solid #cfdce5;border-radius:14px;background:#f8fbfc;display:grid;grid-template-columns:140px 1fr;overflow:hidden;">
                    <img src="{{ $telegramImageUrl }}" alt="" style="width:140px;height:140px;object-fit:cover;margin:3px;border-radius:10px;">
                    <div style="padding:12px 14px;color:#1f2933;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;">
                        <div style="color:#6b7c8f;font-size:13px;margin-bottom:5px;">{{ $telegramDomain }}</div>
                        <div style="font-weight:700;font-size:15px;line-height:1.35;margin-bottom:6px;">{{ $telegramTitle }}</div>
                        <div style="color:#536170;font-size:13px;line-height:1.35;">{{ $telegramDescription }}</div>
                    </div>
                </div>
                <p class="text-muted small mt-2 mb-0">说明：真实 X / Telegram 客户端卡片布局由平台控制；这里用于模拟你截图中的卡片视觉。</p>
            </div>
        </div>
    </div>
</div>
@endsection
