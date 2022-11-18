<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Session;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $rootRoute = '/api/v1';

    protected function withCSRFToken($attributes = [])
    {
        Session::start();
        return array_merge($attributes, ['_token' => csrf_token()]);
    }

    protected function getFullRoute($attributes = '')
    {

        return '/api/v1' . $attributes;
    }
}
