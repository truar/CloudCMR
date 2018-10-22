<?php

use Illuminate\Database\Seeder;

class MembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 100; $i++) {
            $member = factory(\App\Member::class)->create();
            $nbPhones = rand (0, 3);
            $phones = factory(\App\Phone::class, $nbPhones)->make();
            foreach($phones as $phone) {
                $member->phones()->save($phone);
            }
            $nbAddresses = rand (0, 2);
            $addresses = factory(\Lecturize\Addresses\Models\Address::class, $nbAddresses)->make();
            $member->saveAddresses($addresses->toArray());
        }
    }
}
