<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinktrFieldsToLinksTable extends Migration
{
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->unsignedBigInteger('linktr_category_id')->nullable()->after('button_id');
            $table->string('linktr_icon_mode')->default('preset')->after('custom_icon');
            $table->string('linktr_icon_path')->nullable()->after('linktr_icon_mode');
            $table->string('linktr_icon_preset')->nullable()->after('linktr_icon_path');
            $table->string('linktr_icon_bg_color')->nullable()->after('linktr_icon_preset');
            $table->string('linktr_button_bg_color')->nullable()->after('linktr_icon_bg_color');
            $table->string('linktr_button_text_color')->nullable()->after('linktr_button_bg_color');
            $table->boolean('linktr_is_visible')->default(true)->after('linktr_button_text_color');
            $table->boolean('linktr_open_new_tab')->default(true)->after('linktr_is_visible');
            $table->boolean('linktr_nofollow')->default(true)->after('linktr_open_new_tab');
            $table->foreign('linktr_category_id')->references('id')->on('linktr_categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropForeign(['linktr_category_id']);
            $table->dropColumn([
                'linktr_category_id',
                'linktr_icon_mode',
                'linktr_icon_path',
                'linktr_icon_preset',
                'linktr_icon_bg_color',
                'linktr_button_bg_color',
                'linktr_button_text_color',
                'linktr_is_visible',
                'linktr_open_new_tab',
                'linktr_nofollow',
            ]);
        });
    }
}
