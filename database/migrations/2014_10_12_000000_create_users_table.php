<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->tinyInteger('gender')->nullable('1: Name, 2: Nữ');
            $table->date('date_of_birth')->nullable();
            $table->string('password')->nullable();
            $table->integer('type')->default(2)->comment('1: ADMIN, 2: MEMBER');
            $table->tinyInteger('status')->comment('1: ACTIVE, 0: INACTIVE')->default(1);
            $table->string('token_password')->nullable();
            $table->text('access_token')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
