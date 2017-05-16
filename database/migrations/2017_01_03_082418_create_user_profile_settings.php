<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile_settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('create_user_id')->comment('创建者uid');
            $table->string('profile', 10)->comment('资料标识');
            $table->string('profile_name', 10)->comment('资料名称');
            $table->string('type', 20)->nullable()->default('input')->comment('资料类型');
            $table->tinyInteger('required')->nullable()->default(0)->comment('是否必填');
            $table->tinyInteger('is_delable')->nullable()->default(0)->comment('是否可被删除0:不可被删除, 1:可被删除');
            $table->tinyInteger('state')->nullable()->default(1)->comment('状态,1:在用，0:停用');
            $table->tinyInteger('is_show')->nullable()->default(1)->comment('是否在前台显示,1:显示，0:不显示');
            $table->mediumText('default_options')->nullable()->comment('预设选项：select,checkbox,radio类型的资料必填');
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
        Schema::dropIfExists('user_profile_settings');
    }
}
