<?php

class EdgeList extends Eloquent {

    protected $fillable = ['file_name', 'name', 'size', 'nodes', 'edges', 'description', 'user_id'];

    public function user()
    {
        $this->belongsTo('User');
    }

    private $rules = array(
        'uploaded-file' => 'mimes:txt,dat|max:1000',
    );

    private $set_rules = array(
        'name' => 'required|min:3'
    );

    public function validate($input) {
        return Validator::make($input, $this->rules);
    }

    public function validate_set($input) {
        return Validator::make($input, $this->set_rules);
    }
}

