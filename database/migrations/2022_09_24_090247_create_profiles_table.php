<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('profile_picture')->default('images/avatar.svg');
            $table->string('affiliate_link', 255)->nullable();
            $table->string('facebook', 255)->default('https://www.facebook.com/');
            $table->string('linkedin', 255)->default('https://www.linkedin.com/');
            $table->boolean('applied')->default(false);
            $table->string('designation')->nullable();
            $table->string('experties')->nullable();
            $table->string('cv')->nullable();
            $table->text('about_me')->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};
