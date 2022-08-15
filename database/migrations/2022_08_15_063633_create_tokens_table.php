<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->bigInteger('id', true, true);
            $table->char('uuid', 32);
            $table->char('device_id', 32);
            $table->text('data');
            $table->tinyInteger('revoked')->default(false);
            $table->char('parent_id', 32);
            $table->timestamp('updated_at')->nullable(false);
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('expired_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tokens');
    }
}
