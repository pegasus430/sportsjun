<?php

use App\Model\OrganizationRole;
use Illuminate\Database\Seeder;

class OrganizationRolesSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $roles = [
      ['name' => 'Admin'],
      ['name' => 'Coach'],
      ['name' => 'Manager'],
      ['name' => 'physio'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks = 0");

        OrganizationRole::truncate();

        OrganizationRole::insert($this->roles);

        DB::statement("SET foreign_key_checks = 1");

    }
}
