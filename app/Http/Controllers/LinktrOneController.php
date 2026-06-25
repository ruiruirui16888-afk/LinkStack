<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinktrCategory;
use App\Models\LinktrShareCard;
use App\Models\LinktrUserStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LinktrOneController extends Controller
{
    public function settings()
    {
        $user = Auth::user();
        $style = LinktrUserStyle::firstOrCreate(['user_id' => $user->id]);
        $shareCard = LinktrShareCard::firstOrCreate(['user_id' => $user->id]);
        $categories = LinktrCategory::where('user_id', $user->id)->orderBy('sort_order')->get();
        $links = Link::where('user_id', $user->id)->orderBy('up_link', 'asc')->orderBy('order', 'asc')->get();
        $fonts = config('linktr_fonts', []);
        $iconPresets = config('linktr_icons', []);

        return view('studio.linktr-one.settings', compact('user', 'style', 'shareCard', 'categories', 'links', 'fonts', 'iconPresets'));
    }

    public function updateStyle(Request $request)
    {
        $fonts = array_keys(config('linktr_fonts', []));
        $fontRule = ['required', Rule::in($fonts)];

        $data = $request->validate([
            'background_color_1' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'background_color_2' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'background_color_3' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'background_direction' => ['required', 'max:32'],
            'name_font' => $fontRule,
            'bio_font' => $fontRule,
            'category_font' => $fontRule,
            'button_font' => $fontRule,
            'footer_font' => $fontRule,
            'name_color' => ['required', 'max:40'],
            'bio_color' => ['required', 'max:40'],
            'category_color' => ['required', 'max:40'],
            'button_text_color' => ['required', 'max:40'],
            'footer_color' => ['required', 'max:60'],
            'button_background_color' => ['required', 'max:40'],
            'button_shadow_color' => ['nullable', 'max:80'],
            'button_radius' => ['required', 'integer', 'min:0', 'max:64'],
            'avatar_radius' => ['required', 'integer', 'min:0', 'max:999'],
            'footer_text' => ['nullable', 'max:255'],
            'show_footer' => ['nullable', 'boolean'],
        ]);

        $data['background_type'] = 'linear-gradient';
        $data['show_footer'] = $request->boolean('show_footer');

        LinktrUserStyle::updateOrCreate(['user_id' => Auth::id()], $data);

        return back()->with('success', 'Linktr style saved.');
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'title' => ['required', 'max:255'],
            'subtitle' => ['nullable', 'max:255'],
            'display_title' => ['nullable', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $data['user_id'] = Auth::id();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_visible'] = $request->boolean('is_visible', true);

        $categoryId = $data['id'] ?? null;
        unset($data['id']);

        if ($categoryId) {
            LinktrCategory::where('user_id', Auth::id())->where('id', $categoryId)->update($data);
        } else {
            LinktrCategory::create($data);
        }

        return back()->with('success', 'Category saved.');
    }

    public function deleteCategory($id)
    {
        $category = LinktrCategory::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        Link::where('user_id', Auth::id())->where('linktr_category_id', $category->id)->update(['linktr_category_id' => null]);
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    public function updateShareCard(Request $request)
    {
        $data = $request->validate([
            'og_title' => ['nullable', 'max:255'],
            'og_description' => ['nullable', 'max:500'],
            'twitter_title' => ['nullable', 'max:255'],
            'twitter_description' => ['nullable', 'max:500'],
            'twitter_card_type' => ['required', Rule::in(['summary', 'summary_large_image'])],
            'telegram_domain_text' => ['nullable', 'max:255'],
            'telegram_title' => ['nullable', 'max:255'],
            'telegram_description' => ['nullable', 'max:500'],
            'og_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'twitter_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'telegram_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        $shareCard = LinktrShareCard::firstOrCreate(['user_id' => Auth::id()]);

        foreach (['og_image', 'twitter_image', 'telegram_image'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $column = $fileField . '_path';
                $path = $request->file($fileField)->store('public/linktr-cards');
                $data[$column] = Storage::url($path);
            }
        }

        unset($data['og_image'], $data['twitter_image'], $data['telegram_image']);
        $shareCard->update($data);

        return back()->with('success', 'Share card saved.');
    }

    public function updateLinkEnhancements(Request $request, $id)
    {
        $link = Link::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $data = $request->validate([
            'linktr_category_id' => ['nullable', 'integer', Rule::exists('linktr_categories', 'id')->where('user_id', Auth::id())],
            'linktr_icon_mode' => ['required', Rule::in(['preset', 'upload', 'none'])],
            'linktr_icon_preset' => ['nullable', Rule::in(array_keys(config('linktr_icons', [])))],
            'linktr_icon_bg_color' => ['nullable', 'max:40'],
            'linktr_button_bg_color' => ['nullable', 'max:40'],
            'linktr_button_text_color' => ['nullable', 'max:40'],
            'linktr_is_visible' => ['nullable', 'boolean'],
            'linktr_open_new_tab' => ['nullable', 'boolean'],
            'linktr_nofollow' => ['nullable', 'boolean'],
            'linktr_icon' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('linktr_icon')) {
            $path = $request->file('linktr_icon')->store('public/linktr-icons');
            $data['linktr_icon_path'] = Storage::url($path);
            $data['linktr_icon_mode'] = 'upload';
        }

        $data['linktr_is_visible'] = $request->boolean('linktr_is_visible', true);
        $data['linktr_open_new_tab'] = $request->boolean('linktr_open_new_tab', true);
        $data['linktr_nofollow'] = $request->boolean('linktr_nofollow', true);
        unset($data['linktr_icon']);

        $link->update($data);

        return back()->with('success', 'Link style saved.');
    }
}
