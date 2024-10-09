<?php

namespace Database\Seeders;

use App\Models\BandleSortType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BandleSortTypeSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BandleSortType::query()->create([
            'name' => 'Name (A to Z)'
            , 'value' => 'title|asc'
            , 'sort' => 1
        ]);
        BandleSortType::query()->create([
            'name' => 'Name (Z to A)'
            , 'value' => 'title|desc'
            , 'sort' => 2
        ]);
        BandleSortType::query()->create([
            'name' => 'Newest'
            , 'value' => 'created_at|desc'
            , 'sort' => 3
        ]);
        BandleSortType::query()->create([
            'name' => 'Oldest'
            , 'value' => 'created_at|asc'
            , 'sort' => 4
        ]);
    }
}
