<?php

namespace Database\Seeders;

use App\Models\ContactType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactTypeSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactType::query()->create([
            'icon' => 'email_icon'
            , 'name' => 'Email'
            , 'sort' => 1
        ]);
        ContactType::query()->create([
            'icon' => 'phone_icon'
            , 'name' => 'Phone'
            , 'sort' => 2
        ]);
        ContactType::query()->create([
            'icon' => 'messenger_icon'
            , 'name' => 'Messenger'
            , 'sort' => 3
        ]);
    }
}
