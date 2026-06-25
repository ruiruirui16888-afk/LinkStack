<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinktrShareCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linktr_share_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();

            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image_path')->nullable();

            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image_path')->nullable();
            $table->string('twitter_card_type')->default('summary_large_image');

            $table->string('telegram_domain_text')->nullable();
            $table->string('telegram_title')->nullable();
            $table->text('telegram_description')->nullable();
            $table->string('telegram_image_path')->nullable();

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
        Schema::dropIfExists('linktr_share_cards');
    }
}
