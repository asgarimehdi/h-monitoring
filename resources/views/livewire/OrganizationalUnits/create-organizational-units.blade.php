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

        <flux:select wire:model="province_id" label="Province">
            <option value="">Select a Province</option>
            @foreach($provinces as $province)
                <option value="{{ $province->id }}">{{ $province->name }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model="county_id" label="County">
            <option value="">Select a County</option>
            @foreach($counties as $county)
                <option value="{{ $county->id }}">{{ $county->name }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model="parent_id" label="Parent Unit">
            <option value="">No Parent</option>
            @foreach($parentUnits as $unit)
                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
            @endforeach
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
                    <th class="px-4 py-2 text-left">Province</th>
                    <th class="px-4 py-2 text-left">County</th>
                    <th class="px-4 py-2 text-left">Parent Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $unit->name }}</td>
                        <td class="px-4 py-2">
                            {{ $unit->province ? $unit->province->name : '-' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $unit->county ? $unit->county->name : '-' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $unit->parent ? $unit->parent->name : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
