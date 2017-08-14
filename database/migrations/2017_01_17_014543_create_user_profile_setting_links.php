<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileSettingLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile_setting_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('资料关联用户id');
            $table->integer('user_profile_setting_id')->comment('资料项id');
            $table->mediumText('user_profile_setting_data')->comment('资料项内容');
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
        Schema::dropIfExists('user_profile_setting_links');
    }
}
