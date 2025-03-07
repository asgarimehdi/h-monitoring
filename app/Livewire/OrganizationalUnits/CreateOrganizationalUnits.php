<?php

namespace App\Livewire\OrganizationalUnits;

use Livewire\Component;
use App\Models\OrganizationalUnit;
use App\Models\Province;
use App\Models\County;

class CreateOrganizationalUnits  extends Component
{
    public $units;         // لیست واحدها
    public $name;
    public $description;
    public $province_id;
    public $county_id;
    public $parent_id;

    // داده‌های کمکی برای dropdown ها
    public $provinces;
    public $counties;
    public $parentUnits;

    // در زمان mount، داده‌های اولیه را بارگذاری می‌کنیم
    public function mount()
    {
        $this->loadData();
    }

    // متد کمکی جهت بارگذاری داده‌ها
    public function loadData()
    {
        $this->units = OrganizationalUnit::with(['province', 'county', 'parent'])->get();
        $this->provinces = Province::all();
        $this->counties = County::all();
        $this->parentUnits = OrganizationalUnit::all();
    }

    // متد ایجاد واحد جدید
    public function createUnit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            // province_id و county_id قابل nullable هستند؛ اگر انتخاب نشده باشند، اعتبارسنجی عبور می‌کنند
            'province_id' => 'nullable|exists:provinces,id',
            'county_id'   => 'nullable|exists:counties,id',
            'parent_id'   => 'nullable|exists:organizational_units,id',
        ]);

        OrganizationalUnit::create([
            'name'             => $this->name,
            'description'      => $this->description,
            'province_id'      => $this->province_id,
            'county_id'        => $this->county_id,
            'parent_id'        => $this->parent_id,
        ]);

        // پس از ایجاد، فرم را ریست می‌کنیم و داده‌ها را مجدداً بارگذاری می‌کنیم
        $this->reset(['name', 'description', 'province_id', 'county_id', 'parent_id']);
        $this->loadData();
        session()->flash('message', 'Organizational Unit created successfully.');
    }

    public function render()
    {
        return view('livewire.OrganizationalUnits.create-organizational-units');
    }
}