<?php

use Illuminate\Database\Seeder;

class ISeedOauthClientsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('oauth_clients')->delete();

        \DB::table('oauth_clients')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'user_id' => NULL,
                    'name' => 'Laravel Personal Access Client',
                    'secret' => 'x0lHbXlFvymFOUa6MKxQRD35JhKmt83hMnNe4PfV',
                    'provider' => NULL,
                    'redirect' => 'http://localhost',
                    'personal_access_client' => 1,
                    'password_client' => 0,
                    'revoked' => 0,
                    'created_at' => '2020-11-15 05:14:12',
                    'updated_at' => '2020-11-15 05:14:12',
                ),
            1 =>
                array (
                    'id' => 2,
                    'user_id' => NULL,
                    'name' => 'Laravel Password Grant Client',
                    'secret' => '7D33jFfye6PNXbrfUHj2Vew4mxh8fY3A8jw5Snw0',
                    'provider' => 'users',
                    'redirect' => 'http://localhost',
                    'personal_access_client' => 0,
                    'password_client' => 1,
                    'revoked' => 0,
                    'created_at' => '2020-11-15 05:14:12',
                    'updated_at' => '2020-11-15 05:14:12',
                ),
        ));


    }
}
