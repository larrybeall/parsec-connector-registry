<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Servers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_key', 128);
            $table->string('server_identity', 128);
            $table->timestamp('heartbeat');
            $table->string('locator_packet');
            $table->timestamp('locator_packet_updated');
            $table->timestamp('created_at');

            $table->unique(['client_key', 'server_identity']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
