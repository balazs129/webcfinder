<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdgeListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('edge_lists', function($table) {
			$table -> increments('id');
			$table -> string('file_name');
			$table -> string('name');
			$table -> integer('size');
			$table -> integer('nodes');
			$table -> integer('edges');
			$table -> text('description');
			$table -> integer('user_id');
			$table -> timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('edge_lists');
	}

}
