<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        setting(['merchants_range' => 50])->save();
        setting(['default_speed' => 100])->save();
        setting(['name' => [
            'ar' => 'Ping App ar',
            'en' => 'Ping App en',
        ]])->save();
        setting(['address' => [
            'ar' => 'address ar',
            'en' => 'address en',
        ]])->save();
        setting(['email' => 'email@email.com'])->save();
        setting(['mobile' => '+966547896541'])->save();
        setting(['logo' => ''])->save();
        setting(['logo_min' => ''])->save();
        setting(['whatsApp' => '+966547896541'])->save();
        setting(['facebook' => ''])->save();
        setting(['twitter' => ''])->save();
        setting(['instagram' => ''])->save();
        setting(['snapchat' => ''])->save();
        setting(['youtube' => ''])->save();
        setting(['android_url' => ''])->save();
        setting(['ios_url' => ''])->save();
        setting(['about_us' => [
            'ar' => 'about_us_ar',
            'en' => 'about_us_en',
        ]])->save();

        setting(['privacy_policy' => [
            'ar' => 'privacy_policy_ar',
            'en' => 'privacy_policy_en',
        ]])->save();
    }
}
