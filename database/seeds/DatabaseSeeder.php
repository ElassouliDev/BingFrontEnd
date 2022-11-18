<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(ManagerTableSeeder::class);

        $this->call(MerchantTypesSeeder::class);
        $this->call(BankTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(MerchantSeeder::class);
        $this->call(ClientSeeder::class);

        $this->call(SettingsSeeder::class);
        $this->call(PermissionTableSeeder::class);

        $this->call(ISeedOauthPersonalAccessClientsTableSeeder::class);
        $this->call(ISeedOauthClientsTableSeeder::class);

    }
}
