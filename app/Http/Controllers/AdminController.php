<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Module;
use App\Models\Category;
use App\Models\Service;
use App\Models\Unit; // <-- 1. ADDED UNIT MODEL IMPORT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard with grouped modules and items.
     */
    public function index()
    {
        $units = Unit::all();
        // Eager load nested relationships: Module -> Categories -> Services -> Items
        $modules = Module::with([
            'categories.services.items', 
            'items.category', 
            'items.service',
            'items.unit'
        ])->get();

        // 1. Structured nested tree for frontend JavaScript dropdowns
        $adminModulesTree = $modules->map(function ($module) {
            return [
                'module_id'   => $module->getKey(),
                'module_name' => $module->module_name ?? $module->name ?? 'UNKNOWN MODULE',
                'categories'  => $module->categories->map(function ($cat) {
                    return [
                        'category_id'   => $cat->getKey(),
                        'category_name' => $cat->category_name ?? $cat->name ?? 'N/A',
                        'services'      => $cat->services->map(function ($serv) {
                            return [
                                'service_id'   => $serv->getKey(),
                                'service_name' => $serv->service_name ?? $serv->name ?? 'N/A',
                                'items'        => $serv->items->map(function ($item) {
                                    return [
                                        'item_id'   => $item->getKey(),
                                        'item_name' => $item->item_name ?? $item->name ?? 'N/A',
                                        'rate'      => $item->internal_rate ?? 0,
                                        'unit_id'   => $item->unit_id ?? null,
                                    ];
                                })->values()->toArray()
                            ];
                        })->values()->toArray()
                    ];
                })->values()->toArray()
            ];
        })->values()->toArray();

        // 2. Data groups for static Blade table rendering
        $dataGroups = $modules->mapWithKeys(function ($module) {
            $moduleName = $module->module_name ?? $module->name ?? 'UNKNOWN MODULE';
            return [
                strtoupper($moduleName) => [
                    'id'    => $module->getKey(),
                    'items' => $module->items->map(function ($item) {
                        return [
                            'id'            => $item->getKey(),
                            'name'          => $item->item_name ?? $item->name ?? 'N/A',
                            'category_name' => $item->category?->category_name ?? $item->category?->name ?? 'N/A',
                            'service'       => $item->service?->service_name ?? $item->service?->name ?? 'N/A',
                            'rate'          => number_format($item->internal_rate ?? 0, 2),
                            'unit_id'       => $item->unit_id ?? null,
                            'description'   => $item->description ?? '',
                        ];
                    })->toArray()
                ]
            ];
        });

        return view('dashboard.admin', compact('dataGroups', 'adminModulesTree', 'units'));
    }

    public function showAdmin()
    {
        return $this->index();
    }

    /**
     * Load the creation form.
     */
    public function create()
    {
        $modules = Module::all();
        $categories = Category::has('items')->get();
        $services = Service::has('items')->get();
        $units = Unit::all(); // <-- 2. FETCH ALL UNITS

        return view('dashboard.newItem', compact('modules', 'categories', 'services', 'units')); // <-- PASS $units
    }

    /**
     * Load the edit form for an existing item.
     */
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $modules = Module::all();
        $categories = Category::has('items')->get();
        $services = Service::has('items')->get();
        $units = Unit::all(); // <-- 3. FETCH ALL UNITS FOR EDIT VIEW

        return view('dashboard.newItem', compact('item', 'modules', 'categories', 'services', 'units')); // <-- PASS $units
    }

    /**
     * Handle Item creation with module-scoped relationships.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'module_id'         => 'nullable|required_without:new_module_name',
            'new_module_name'   => 'nullable|required_without:module_id|string|max:255',
            'category_id'       => 'nullable|required_without:new_category_name',
            'new_category_name' => 'nullable|required_without:category_id|string|max:255',
            'unit_id'           => 'nullable', // <-- 4. ADDED UNIT VALIDATION
            'new_unit_name'     => 'nullable|string|max:50', // <-- ADDED NEW UNIT VALIDATION
            'internal_rate'     => 'required|numeric|min:0',
            'description'       => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Resolve Module
            if ($request->filled('new_module_name')) {
                $module = Module::create(['module_name' => $request->new_module_name]);
                $moduleId = $module->getKey();
            } else {
                $moduleId = $request->module_id;
            }

            // 2. Resolve Category (Ensure Category belongs strictly to $moduleId)
            $categoryName = $request->new_category_name;
            if (!$categoryName && $request->filled('category_id')) {
                $selectedCategory = Category::find($request->category_id);
                $categoryName = $selectedCategory?->category_name ?? $selectedCategory?->name;
            }

            $category = Category::firstOrCreate([
                'module_id'     => $moduleId,
                'category_name' => $categoryName,
            ]);
            $categoryId = $category->getKey();

            // 3. Resolve Service (Ensure Service belongs strictly to $categoryId)
            $serviceName = $request->new_service_name;
            if (!$serviceName && $request->filled('service_id')) {
                $selectedService = Service::find($request->service_id);
                $serviceName = $selectedService?->service_name ?? $selectedService?->name;
            }

            $serviceId = null;
            if ($serviceName) {
                $service = Service::firstOrCreate([
                    'category_id'  => $categoryId,
                    'service_name' => $serviceName,
                ]);
                $serviceId = $service->getKey();
            }

            // 4. Resolve Unit (Handle creating new unit on the fly) <-- ADDED THIS BLOCK
            $unitId = $request->unit_id;
            if ($request->unit_id === 'NEW_UNIT' && $request->filled('new_unit_name')) {
                $newUnit = Unit::firstOrCreate(
                    ['unit_name' => strtoupper(trim($request->new_unit_name))],
                    ['created_by' => auth()->id()]
                );
                $unitId = $newUnit->getKey();
            }

            // 5. Save Main Item
            Item::create([
                'module_id'     => $moduleId,
                'category_id'   => $categoryId,
                'service_id'    => $serviceId,
                'unit_id'       => $unitId, // <-- ADDED UNIT_ID ASSIGNMENT
                'item_name'     => $request->name,
                'internal_rate' => $request->internal_rate,
                'description'   => $request->description,
                'created_by'    => auth()->id(),
            ]);
        });

        return redirect()->route('admin.index')->with('success', 'New item created successfully!');
    }

    /**
     * Handle updating an existing item.
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:255',
            'module_id'         => 'required_without:new_module_name|nullable',
            'new_module_name'   => 'required_without:module_id|nullable|string|max:255',
            'category_id'       => 'required_without:new_category_name|nullable',
            'new_category_name' => 'required_without:category_id|nullable|string|max:255',
            'service_id'        => 'nullable',
            'new_service_name'  => 'nullable|string|max:255',
            'unit_id'           => 'nullable', // <-- ADDED UNIT VALIDATION
            'new_unit_name'     => 'nullable|string|max:50', // <-- ADDED NEW UNIT VALIDATION
            'internal_rate'     => 'required|numeric|min:0',
            'description'       => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $item) {
            // 1. Resolve Module
            $moduleId = $request->module_id;
            if (!$moduleId && $request->filled('new_module_name')) {
                $module = Module::create([
                    'module_name' => $request->new_module_name,
                    'is_active'   => true,
                ]);
                $moduleId = $module->getKey();
            }

            // 2. Resolve Category for this Module
            $categoryName = $request->new_category_name;
            if (!$categoryName && $request->filled('category_id')) {
                $selectedCat = Category::find($request->category_id);
                $categoryName = $selectedCat?->category_name ?? $selectedCat?->name;
            }

            $category = Category::firstOrCreate([
                'module_id'     => $moduleId,
                'category_name' => $categoryName,
            ]);
            $categoryId = $category->getKey();

            // 3. Resolve Service for this Category
            $serviceName = $request->new_service_name;
            if (!$serviceName && $request->filled('service_id')) {
                $selectedService = Service::find($request->service_id);
                $serviceName = $selectedService?->service_name ?? $selectedService?->name;
            }

            $serviceId = null;
            if ($serviceName) {
                $service = Service::firstOrCreate([
                    'category_id'  => $categoryId,
                    'service_name' => $serviceName,
                ]);
                $serviceId = $service->getKey();
            }

            // 4. Resolve Unit (Handle creating new unit on the fly) <-- ADDED THIS BLOCK
            $unitId = $request->unit_id;
            if ($request->unit_id === 'NEW_UNIT' && $request->filled('new_unit_name')) {
                $newUnit = Unit::firstOrCreate(
                    ['unit_name' => strtoupper(trim($request->new_unit_name))],
                    ['created_by' => auth()->id()]
                );
                $unitId = $newUnit->getKey();
            }

            // 5. Update Item record
            $item->update([
                'module_id'     => $moduleId,
                'category_id'   => $categoryId,
                'service_id'    => $serviceId,
                'unit_id'       => $unitId, // <-- ADDED UNIT_ID ASSIGNMENT
                'item_name'     => $request->name,
                'internal_rate' => $request->internal_rate,
                'description'   => $request->description,
                'updated_by'    => auth()->id(),
            ]);
        });

        return redirect()->route('admin.index')->with('success', 'Item updated successfully!');
    }

    public function destroy($id)
    {
        // 1. find item through id
        $item = Item::findOrFail($id);

        // 2. delete id
        $item->delete();

        return redirect()->back()->with('success', 'Item successfully deleted!');
    }
}