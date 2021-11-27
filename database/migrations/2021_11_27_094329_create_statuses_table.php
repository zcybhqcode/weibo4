<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
           $table->increments('id');
           $table->text('content');//储存微博的内容
           $table->integer('user_id')->index();//查找指定用户发布过的所有微博
           //创建时间添加索引
           $table->index(['created_at']);
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
        Schema::dropIfExists('statuses');
    }
}
