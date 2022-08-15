<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigInteger('id', true, true);
            $table->char('uuid', 32);
            $table->string('type', 150);
            $table->string('name', 300);
            $table->string('manufacturer', 300);
            $table->string('system', 150);
            $table->string('system_version', 50);
            $table->string('language', 50);
            $table->string('browser', 50);
            $table->string('browser_version', 50);
            $table->text('user_agent');
            $table->timestamp('updated_at')->nullable(false);
            $table->timestamp('created_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
