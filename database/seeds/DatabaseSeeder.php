<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountriesSeeder::class);
        $this->command->info('Seeded the countries!'); 
        $this->call(RolesAndPermissionsSeeder::class);
        $this->command->info('Seeded the permissions and roles!'); 
        $this->call(UserAdminSeeder::class);
        $this->command->info('Seeded the users!'); 
    }
}
