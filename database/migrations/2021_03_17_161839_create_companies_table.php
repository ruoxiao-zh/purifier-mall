<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->default(null)->comment('公司名称');
            $table->string('logo_path', 255)->default(null)->comment('Logo 路径');
            $table->string('contact', 50)->default(null)->comment('联系方式');
            $table->string('address', 255)->default(null)->comment('地址');
            $table->text('description')->default(null)->comment('公司描述');
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
        Schema::dropIfExists('companies');
    }
}
