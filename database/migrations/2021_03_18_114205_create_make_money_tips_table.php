<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMakeMoneyTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('make_money_tips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cover_image', 255)->default(null)->comment('封面图');
            $table->string('author', 50)->default(null)->comment('作者');
            $table->string('brief_intro', 255)->default(null)->comment('简介');
            $table->text('content')->default(null)->comment('图文详情');
            $table->tinyInteger('sort')->default(0)->comment('排序, 默认倒序');
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
        Schema::dropIfExists('make_money_tips');
    }
}
