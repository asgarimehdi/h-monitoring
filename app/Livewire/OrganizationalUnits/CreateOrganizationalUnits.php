<?php

namespace App\Livewire\OrganizationalUnits;

use Livewire\Component;
use App\Models\OrganizationalUnit;
use App\Models\Province;
use App\Models\County;
use App\Models\UnitType;

class CreateOrganizationalUnits extends Component
{
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

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // دریافت داده‌های اولیه جهت نمایش لیست و dropdown ها
        $this->units = OrganizationalUnit::with(['province', 'county', 'parent', 'unitType'])->get();
        $this->provinces = Province::all();
        $this->counties = County::all();
        $this->parentUnits = OrganizationalUnit::all();
        $this->unitTypes = UnitType::all();
    }

    /**
     * محاسبه property برای دریافت واحدهای والد مجاز بر اساس نوع انتخاب شده
     */
    public function getAllowedParentUnitsProperty()
    {
        if (!$this->unit_type_id) {
            return OrganizationalUnit::all();
        }

        $selectedType = UnitType::find($this->unit_type_id);
        if (!$selectedType) {
            return OrganizationalUnit::all();
        }
        // دریافت شناسه‌های انواع والد مجاز برای نوع انتخاب شده
        $allowedParentTypeIds = $selectedType->allowedParentTypes->pluck('id')->toArray();

        return OrganizationalUnit::whereIn('unit_type_id', $allowedParentTypeIds)->get();
    }

    public function createUnit()
    {
        $this->validate([
            'name'         => 'required|string|max:255',
            'unit_type_id' => 'required|exists:unit_types,id',
            'province_id'  => 'nullable|exists:provinces,id',
            'county_id'    => 'nullable|exists:counties,id',
            'parent_id'    => 'nullable|exists:organizational_units,id',
        ]);

        // اعتبارسنجی اضافی: اگر واحد والد انتخاب شده است، مطمئن شویم نوع آن مجاز است
        if ($this->parent_id) {
            $parentUnit = OrganizationalUnit::find($this->parent_id);
            $selectedType = UnitType::find($this->unit_type_id);
            $allowedParentTypeIds = $selectedType->allowedParentTypes->pluck('id')->toArray();
            if (! in_array($parentUnit->unit_type_id, $allowedParentTypeIds)) {
                $this->addError('parent_id', 'انتخاب واحد والد مجاز نیست.');
                return;
            }
        }
      //  dd($this->unit_type_id);
        OrganizationalUnit::create([
            'name'          => $this->name,
            'description'   => $this->description,
            'unit_type_id'  => $this->unit_type_id,
            'province_id'   => $this->province_id,
            'county_id'     => $this->county_id,
            'parent_id'     => $this->parent_id,
        ]);

        $this->reset(['name', 'description', 'unit_type_id', 'province_id', 'county_id', 'parent_id']);
        $this->loadData();
        session()->flash('message', 'Organizational Unit created successfully.');
    }

    public function render()
    {
        return view('livewire.OrganizationalUnits.create-organizational-units');
    }
}