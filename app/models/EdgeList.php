<?php

class EdgeList extends Eloquent {

    protected $fillable = ['name', 'nodes', 'edges', 'description', 'user_id'];

    public function user()
    {
        $this -> belongsTo('User');
    }

    private $rules = array(
        'edgelist' => 'mimes:txt,dat|max:1000'
    );

    public function validate($input) {
        return Validator::make($input, $this->rules);
    }
}

