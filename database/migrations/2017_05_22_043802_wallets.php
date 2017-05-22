<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Wallets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            // 主键
            $table
                ->bigIncrements('id')
                ->comment('钱包ID');

            // 关联用户
            $table
                ->integer('user_id')
                ->unsigned()
                ->comment('钱包所属用户');

            // 余额，单位 分 (避免小数计算偏移)
            $table
                ->integer('balance')
                ->unsigned()
                ->comment('钱包余额');
            $table->timestamps(); // 自动维护时间
            $table->softDeletes(); // 软删除

            // 外健约束
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // 唯一健设置
            $table->unique('user_id');

            // 加入余额到普通索引，加速查找和sql命中.
            $table->index('balance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
