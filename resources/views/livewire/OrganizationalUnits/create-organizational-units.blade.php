<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Manage Organizational Units</h1>

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

        <!-- انتخاب واحد والد با استفاده از property محاسبه شده -->
        {{-- <flux:select wire:model="parent_id" label="Parent Unit">
             @if($province_id &&$unit_type_id)
            <option value="">No Parent</option>
           @foreach($this->allowedParentUnits as $unit)
    <option value="{{ $unit->id }}">
        {{ $unit->name }} ({{ optional($unit->unitType)->name ?? '-' }})
    </option>
@endforeach
 @else
        <option value="">Select a Province and Unit Type first</option>
    @endif
        </flux:select> --}}
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
        <h2 class="text-xl font-semibold mb-2">Organizational Units List</h2>
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
