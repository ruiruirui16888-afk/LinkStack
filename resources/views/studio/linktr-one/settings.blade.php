@extends('layouts.sidebar')

@section('content')
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
            <form method="post" action="{{ route('linktr.one.category.store') }}" class="mb-4">
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
        </div>
    </div>
</div>
@endsection
