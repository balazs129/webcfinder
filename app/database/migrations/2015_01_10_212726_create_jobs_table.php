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
			$table->string('upper_weight_threshold');
			$table->string('lower_weight_threshold');
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
