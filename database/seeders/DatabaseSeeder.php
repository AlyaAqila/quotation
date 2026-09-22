<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Category;
use App\Models\Service;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a dummy user for audit logging
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // 2. Create Coastal Module, Category, Service, & Item
        $coastalModule = Module::create(['module_name' => 'Coastal']);
        
        $coastalCat = Category::create([
            'module_id' => $coastalModule->module_id,
            'category_name' => 'Coastal Hydrography'
        ]);

        $coastalService = Service::create([
            'category_id' => $coastalCat->category_id,
            'service_name' => 'Singlebeam / Multibeam Bathymetry'
        ]);

        Item::create([
            'module_id' => $coastalModule->module_id,
            'category_id' => $coastalCat->category_id,
            'service_id' => $coastalService->service_id,
            'item_name' => 'Hydrographic Survey',
            'internal_rate' => 1500.00,
            'description' => 'Detailed coastal bathymetric profiling.',
            'created_by' => $user->id,
        ]);
    }
}