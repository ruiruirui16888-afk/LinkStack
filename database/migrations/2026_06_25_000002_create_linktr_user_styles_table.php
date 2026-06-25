<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinktrUserStylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linktr_user_styles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();

            $table->string('background_type')->default('linear-gradient');
            $table->string('background_color_1')->default('#df9fac');
            $table->string('background_color_2')->default('#ffc8f4');
            $table->string('background_color_3')->nullable();
            $table->string('background_direction')->default('180deg');

            $table->string('name_font')->default('default');
            $table->string('bio_font')->default('default');
            $table->string('category_font')->default('default');
            $table->string('button_font')->default('default');
            $table->string('footer_font')->default('default');

            $table->string('name_color')->default('#513246');
            $table->string('bio_color')->default('#513246');
            $table->string('category_color')->default('#513246');
            $table->string('button_text_color')->default('#513246');
            $table->string('footer_color')->default('rgba(81, 50, 70, 0.72)');

            $table->string('button_background_color')->default('#ffffff');
            $table->string('button_shadow_color')->default('rgba(69, 33, 52, 0.16)');
            $table->unsignedInteger('button_radius')->default(32);
            $table->unsignedInteger('avatar_radius')->default(999);
            $table->text('footer_text')->nullable();
            $table->boolean('show_footer')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linktr_user_styles');
    }
}
