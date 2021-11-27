<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivationToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
   {
       Schema::table('users', function (Blueprint $table) {
           //创建激活与未激活状态
   		$table->string('activation_token')->nullable();
   		$table->boolean('activated')->default(false);
       });
   }
   
   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
       Schema::table('users', function (Blueprint $table) {
           //激活令牌进行清空
           $table->dropColumn('activation_token');
           $table->dropColumn('activated');
       });
    }
}
