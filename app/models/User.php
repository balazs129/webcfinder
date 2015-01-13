<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $fillable = ['name' ,'email', 'password', 'organization'];

	private $rules = array(
		'email' => 'required|email|unique:users',
		'password' => 'required|min:3|confirmed',
		'name' => 'required',
		'organization' => 'required'
	);

	public function validate($input) {
		return Validator::make($input, $this->rules);
	}

	public function files()
	{
		return $this -> hasMany('EdgeList');
	}

	public function jobs()
	{
		return $this->hasMany('Job');
	}
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

}
