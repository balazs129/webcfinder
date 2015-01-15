<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobs', function($table){
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('edge_list_id');
			$table->integer('result_id');
			$table->integer('slurm_id');
			$table->integer('upper_weight')->nullable();
			$table->integer('lower_weight')->nullable();
			$table->integer('digits')->nullable();
			$table->integer('max_time')->nullable();
			$table->boolean('directed')->nullable();
			$table->integer('lower_link')->nullable();
			$table->integer('k_size')->nullable();
			$table->string('status');
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
		Schema::Drop('jobs');
	}

}
