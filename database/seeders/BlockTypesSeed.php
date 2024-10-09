<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BlockType;

class BlockTypesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlockType::query()->create([
            'name' => 'Name card'
            , 'description' => 'Personal information'
            , 'icon' => 'name_card_icon'
            , 'limit' => 1
            , 'sort' => 3
        ]);
        BlockType::query()->create([
            'name' => 'Social network'
            , 'description' => 'Add dedicated link to a social profile '
            , 'icon' => 'block_icon social_block_icon'
            , 'limit' => 0
            , 'sort' => 1
        ]);
        BlockType::query()->create([
            'name' => 'Contacts'
            , 'description' => 'Leave contacts here'
            , 'icon' => 'block_icon contacts_icon'
            , 'limit' => 0
            , 'sort' => 2
        ]);
    }
}
