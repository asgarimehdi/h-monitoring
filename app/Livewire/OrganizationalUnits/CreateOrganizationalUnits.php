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
        $query = OrganizationalUnit::query();

        if ($this->unit_type_id) {
            $selectedType = UnitType::find($this->unit_type_id);
            if ($selectedType) {
                // دریافت شناسه‌های انواع والد مجاز برای نوع انتخاب شده
                $allowedParentTypeIds = $selectedType->allowedParentTypes->pluck('id')->toArray();
                $query->whereIn('unit_type_id', $allowedParentTypeIds);
            }
        }

        if ($this->province_id) {
            $query->where('province_id', $this->province_id);
        }

        return $query->get();
    }


    public function createUnit()
    {
        $this->validate([
            'name'         => 'required|string|max:255|unique:organizational_units,name',
            'unit_type_id' => 'required|exists:unit_types,id',
            'province_id'  => 'nullable|exists:provinces,id',
            'county_id'    => 'nullable|exists:counties,id',
            'parent_id'    => 'nullable|exists:organizational_units,id',
        ]);
        if ($this->county_id == "") {
            $this->county_id = null;
        }
        if ($this->parent_id == null && $this->unit_type_id == 1) {
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
        } elseif ($this->parent_id == null) {

            $this->addError('parent_id', 'انتخاب واحد والد خالی مجاز نیست.');
            return;
        }
    }

    public function render()
    {
        return view('livewire.OrganizationalUnits.create-organizational-units');
    }
}