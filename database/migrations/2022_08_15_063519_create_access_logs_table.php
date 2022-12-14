<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_logs', function (Blueprint $table) {
            $table->bigInteger('id', true, true);
            $table->string('ip', 50)->index();
            $table->char('token_id', 32)->default("0");
            $table->char('request_id', 32)->default("0");
            $table->string('method', 20);
            $table->string('namespace', 100);
            $table->string('controller', 100);
            $table->string('action', 50);
            $table->json('header');
            $table->string('url', 200)->index();
            $table->string('path', 500)->index();
            $table->json('response');
            $table->json('parameter');
            $table->decimal('start_at', 16, 6)->index();
            $table->decimal('end_at', 16, 6)->index();
            $table->integer('status')->default(200)->index();
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
        Schema::dropIfExists('access_logs');
    }
}
