<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->comment('圈子ID');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->tinyInteger('audit')->nullable()->default(0)->comment('审核状态：0 - 待审核、1 - 通过');
            $table->string('role', 100)->nullable()->default('member')->comment('角色，member - administrator - 管理者、founder - 创建者');
            $table->tinyInteger('disabled')->nullable()->default(0)->comment('是否禁用');
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
        Schema::dropIfExists('group_members');
    }
}
