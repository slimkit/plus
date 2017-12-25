<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMemberLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_member_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->comment('圈子ID');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->integer('member_id')->unsigned()->comment('圈子成员ID');
            $table->tinyInteger('status')->default(0)->comment('审核状态：0 - 待审核、1 - 通过、2 - 拒绝');
            $table->integer('auditer')->nullable()->unsigned()->comment('审核人');
            $table->timestamp('audit_at')->nullable()->comment('处理时间');
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
        Schema::dropIfExists('group_member_logs');
    }
}
