<?php
namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            ['name' => 'Low', 'color' => 'success'],
            ['name' => 'Medium', 'color' => 'warning'],
            ['name' => 'High', 'color' => 'danger'],
        ];

        foreach ($priorities as $p) {
            Priority::create($p);
        }
    }
}