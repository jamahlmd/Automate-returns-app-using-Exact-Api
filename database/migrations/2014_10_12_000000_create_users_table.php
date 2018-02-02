<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });


        // Insert some stuff
        DB::table('users')->insert(
            array(
                'id' => 1,
                'name' => 'Jams',
                'email' => 'jamahlmd@gmail.com',
                'password' => '$2y$10$VCrhwmvcbWkjeP7G0MF83O/GbPUDHgxcRHFnAYwb6qS0L1E.afiZW',
            )
        );
        DB::table('users')->insert(
            array(
                'id' => 2,
                'name' => 'Ahasan',
                'email' => 'r.ahasan@hotmail.com',
                'password' => '$2y$10$VCrhwmvcbWkjeP7G0MF83O/GbPUDHgxcRHFnAYwb6qS0L1E.afiZW',
            )
        );
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
