<?php

class Job extends Eloquent {

    protected $fillable = [];

    public function user()
    {
        $this->belongsTo('User');
    }

    private $rules = array();

    public function validate($input)
    {
        return Validator::make($input, $this->rules);
    }

    public function generateOptions($input)
    {
        $options = " -i {$input['edge_list']}";

        if (!empty($input['upper_weight'])) {
            $options .= " -W {$input['upper_weight']}";
        }

        if (!empty($input['lower_weight'])) {
            $options .= " -w {$input['lower_weight']}";
        }

        if (!empty($input['threshold_digits'])) {
            $options .= " -d {$input['threshold_digits']}";
        }

        if (!empty($input['max_time'])) {
            $options .= " -t {$input['max_time']}";
        }

        if (!empty($input['weight_intensity'])) {
            $options .= " -I {$input['weight_intensity']}";
        }


        if (!empty($input['k_size'])) {
            $options .= " -k {$input['k_size']}";
        }

        return $options;
    }
}

