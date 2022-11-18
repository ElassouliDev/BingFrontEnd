<?php

namespace App\Models\helperModels;
use Carbon\Carbon;

class HelperTest
{
    public function __construct($key, $key_name, $value,$details)
    {
        $this->key = $key;
        $this->key_name = $key_name;
        $this->details = $details;
        $this->value = isset($value) ? $value : Carbon::now()->format(DATE_FORMAT_FULL);
    }
}
