<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('role_id');
            $table->primary(['user_id','role_id']);
        });

        // Insert some stuff
        DB::table('roles')->insert(
            array(
                'id' => 1,
                'name' => 'Admin'
            )
        );
        DB::table('roles')->insert(
            array(
                'id' => 2,
                'name' => 'Manager'
            )
        );
        DB::table('role_user')->insert(
            array(
                'user_id' => 1,
                'role_id' => 1
            )
        );
        DB::table('role_user')->insert(
            array(
                'user_id' => 2,
                'role_id' => 1
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
        Schema::dropIfExists('roles');
    }
}
