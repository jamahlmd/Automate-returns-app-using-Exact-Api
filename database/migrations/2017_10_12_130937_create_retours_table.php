<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Fluent;

class CreateRetoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee');
            $table->date('arrival_date')->nullable();
            $table->integer('date_difference')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('customer_name')->nullable();
            $table->integer('invoice_quantity')->nullable();
            $table->string('invoice_name')->nullable();
            $table->float('invoice_price')->nullable();
            $table->float('invoice_total')->nullable();
            $table->integer('total_orderamount')->nullable();
            $table->integer('product_quantity')->nullable();
            $table->integer('open_products')->nullable();
            $table->integer('credit_amount')->nullable();
            $table->text('reason')->nullable();
            $table->text('comment')->nullable();
            $table->text('if_credited')->nullable();
            $table->text('contact')->nullable();
            $table->string('country_code')->nullable();
            $table->string('emailadress')->nullable();
            $table->string('carrier')->nullable();
            $table->integer('nlcall_id')->nullable();
            $table->string('agent_name')->nullable();
            $table->integer('agent_id')->nullable();
            $table->boolean('claim')->default(false);
            $table->boolean('geretourd')->default(false);
            $table->boolean('exported')->default(false);
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
        Schema::dropIfExists('retours');
    }
}
