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
        $member = factory(\App\Member::class)->create();
        $phones = factory(\App\Phone::class, 3)->make();
        foreach($phones as $phone) {
            $member->phones()->save($phone);
        }
        $addresses = factory(\Lecturize\Addresses\Models\Address::class, 3)->make();
        $member->saveAddresses($addresses->toArray());
    }
}
