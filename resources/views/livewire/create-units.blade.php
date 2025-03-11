<?php

use Livewire\Volt\Component;
use App\Models\Unit;
use App\Models\Province;
use App\Models\County;
use App\Models\UnitType;
use App\Models\UnitTypeRelationship;

new class extends Component {
     public $units;         // لیست واحدهای سازمانی
    public $name;
    public $description;
    public $unit_type_id;  // شناسه نوع واحد انتخاب شده
    public $province_id;
    public $county_id;
    public $parent_id;

    // داده‌های کمکی برای dropdown ها
    public $unitTypes;   // لیست انواع واحدها
    public $provinces;   // لیست استان‌ها
    public $counties;    // لیست شهرستان‌ها
    public $parentUnits; // تمام واحدهای سازمانی (برای انتخاب والد)
    public function updatedProvinceId($value)
    {
        $this->county_id = null; // ریست کردن انتخاب شهرستان
        $this->counties = County::where('province_id', $value)->get();
        //  $this->parentUnits= OrganizationalUnit::where('province_id', $value)->get();
    }
    public function updatedUnitTypeId()
    {
        $this->reset(['province_id', 'county_id', 'parent_id']);
    }


    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // دریافت داده‌های اولیه جهت نمایش لیست و dropdown ها
        $this->units = Unit::with(['province', 'county', 'parent', 'unitType'])->get();
        $this->provinces = Province::all();
        $this->counties = County::all();
        $this->parentUnits = Unit::all();
        // $this->unitTypes = UnitType::all();
        $this->unitTypes = UnitType::where('id', '!=', 1)->get();
    }
 
public function getAllowedParentUnitsProperty()
{
    if (!$this->unit_type_id) {
        return collect();
    }

    // دریافت شناسه‌های نوع واحدهای والد مجاز
    $allowedParentTypeIds = UnitTypeRelationship::where('child_unit_type_id', $this->unit_type_id)
        ->pluck('allowed_parent_unit_type_id');

    // فیلتر واحدها بر اساس نوع واحدهای والد مجاز و استان انتخاب‌شده
    $parentUnits = Unit::whereIn('unit_type_id', $allowedParentTypeIds)
        ->where('province_id', $this->province_id)
        ->get();

    // اگر هیچ واحد والدی در استان انتخاب‌شده وجود نداشت
    if ($parentUnits->isEmpty()) {
        // اضافه کردن وزارت‌خانه به عنوان گزینه پیش‌فرض
        $parentUnits = Unit::where('unit_type_id', 1)->get();
    }

    return $parentUnits;
}

    public function createUnit()
    {
        $this->validate([
            'name'         => 'required|string|max:255|unique:units,name',
            'unit_type_id' => 'required|exists:unit_types,id',
            'province_id'  => 'nullable|exists:provinces,id',
            'county_id'    => 'nullable|exists:counties,id',
            'parent_id'    => 'nullable|exists:units,id',
        ]);
        if ($this->county_id == "") {
            $this->county_id = null;
        }
        if ($this->parent_id == null) {

            $this->addError('parent_id', 'هیچ واحد بالادستی انتخاب یا ایجاد نشده است');
            return;
        }
         if ($this->parent_id) {
        $parentUnit = Unit::find($this->parent_id);
        // دریافت شناسه‌های نوع والد مجاز برای نوع فرزند انتخاب‌شده
        $allowedParentTypeIds = UnitTypeRelationship::where('child_unit_type_id', $this->unit_type_id)
            ->pluck('allowed_parent_unit_type_id')->toArray();
        
        if (! in_array($parentUnit->unit_type_id, $allowedParentTypeIds)) {
            $this->addError('parent_id', 'واحد بالادستی انتخاب شده مجاز نیست.');
            return;
        }
    }

        Unit::create([
            'name'          => $this->name,
            'description'   => $this->description,
            'unit_type_id'  => $this->unit_type_id,
            'province_id'   => $this->province_id,
            'county_id'     => $this->county_id,
            'parent_id'     => $this->parent_id,
        ]);

        $this->loadData();
        // session()->flash('message', ' Unit created successfully.');
        session()->flash('message', 'واحد ' . $this->name . ' با موفقیت ایجاد شد.');
        $this->reset(['name', 'description', 'unit_type_id', 'province_id', 'county_id', 'parent_id']);

    }
}; ?>

<div>
   <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Manage Units</h1>

    <!-- نمایش پیام موفقیت -->
    @if (session()->has('message'))
        <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- فرم ایجاد واحد جدید -->
    <form wire:submit.prevent="createUnit" class="space-y-4">
        <flux:input
            wire:model="name"
            label="Unit Name"
            placeholder="Enter unit name"
            required
        />

        <flux:input
            wire:model="description"
            label="Description"
            placeholder="Enter description"
        />

        <!-- فیلد انتخاب نوع واحد (داده از جدول unit_types) -->
        <flux:select wire:model.live="unit_type_id" label="Unit Type">
            <option value="">Select a Unit Type</option>
            @foreach($unitTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="province_id" label="Province">
            <option value="">Select a Province</option>
            @foreach($provinces as $province)
                <option value="{{ $province->id }}">{{ $province->name }}</option>
            @endforeach
        </flux:select>

       <flux:select wire:model.live="county_id" label="County">
    @if($province_id)
        <option value="">Select a County</option>
        @foreach($this->counties as $county)
            <option value="{{ $county->id }}">{{ $county->name }}</option>
        @endforeach
    @else
        <option value="">Select a Province first</option>
    @endif
</flux:select>

<flux:select wire:model="parent_id" label="Parent Unit">
    @if($province_id && $unit_type_id)
        <option value="">No Parent</option>
        @foreach($this->allowedParentUnits as $unit)
            <option value="{{ $unit->id }}">
                {{ $unit->name }} ({{ optional($unit->unitType)->name ?? '-' }})
            </option>
        @endforeach
    @else
        <option value="">Select a Province and Unit Type first</option>
    @endif
</flux:select>

        <flux:button type="submit" variant="primary" class="w-full">
            Create Unit
        </flux:button>
    </form>

    <!-- لیست واحدهای سازمانی -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-2"> Units List</h2>
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Province</th>
                    <th class="px-4 py-2 text-left">County</th>
                    <th class="px-4 py-2 text-left">Parent Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $unit->name }}</td>
                        <td class="px-4 py-2">{{ $unit->unitType ? $unit->unitType->name : '-' }}</td>
                        <td class="px-4 py-2">
                            {{ $unit->province ? $unit->province->name : '-' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $unit->county ? $unit->county->name : '-' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $unit->parent ? $unit->parent->name . ' (' . ($unit->parent->unitType ? $unit->parent->unitType->name : '-') . ')' : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>
