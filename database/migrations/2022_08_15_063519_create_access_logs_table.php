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
            $table->string('ip', 50);
            $table->char('token_id', 32);
            $table->char('request_id', 32);
            $table->string('method', 20);
            $table->string('namespace', 50);
            $table->string('controller', 100);
            $table->string('action', 50);
            $table->text('header');
            $table->string('url', 200);
            $table->string('path', 100);
            $table->text('parameter');
            $table->decimal('start_at', 16, 6);
            $table->decimal('end_at', 16, 6);
            $table->integer('status')->default(true);
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
