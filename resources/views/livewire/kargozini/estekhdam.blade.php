<?php

use Livewire\Volt\Component;
use App\Models\Estekhdam;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $name;
    public $editingId = null;
    public $search = '';

    public function with(): array
    {
        $query = Estekhdam::query();

        if (!empty($this->search)) {
            $query->where('name', 'LIKE', '%' . $this->search . '%');
        }

        return [
            'estekhdams' => $query->paginate(10),
        ];
    }

    public function createEstekhdam()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:estekhdams,name',
        ]);

        Estekhdam::create(['name' => $this->name]);

        session()->flash('message', ' با موفقیت ایجاد شد.');
        $this->reset(['name']);
    }

    public function editEstekhdam($id)
    {
        $estekhdam = Estekhdam::findOrFail($id);
        $this->editingId = $id;
        $this->name = $estekhdam->name;
    }

    public function updateEstekhdam()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:estekhdams,name,' . $this->editingId,
        ]);

        $estekhdam = Estekhdam::findOrFail($this->editingId);
        $estekhdam->update(['name' => $this->name]);

        session()->flash('message', 'واحد با موفقیت بروزرسانی شد.');
        $this->reset(['name', 'editingId']);
    }

    public function delete($id)
    {
        $estekhdam = Estekhdam::find($id);
        if ($estekhdam) {
            $estekhdam->delete();
        }
        session()->flash('message', 'رکورد با موفقیت حذف شد.');

    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
};
?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3 sm:grid-cols-2 h-30">
        <div class="relative  overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 font-bold">
            مدیریت وضعیت‌های استخدام
        </div>

        <div class="relative  overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 font-bold">
            <flux:modal.trigger name="create-estekhdam">
                <flux:button icon="plus" variant="primary" class="w-full">ایجاد وضعیت استخدام جدید</flux:button>
            </flux:modal.trigger>

            <flux:modal name="create-estekhdam" class="md:w-96">
                <div class="space-y-6">
                    <form wire:submit.prevent="createEstekhdam" class="space-y-4">
                        <flux:input
                            wire:model="name"
                            label="Estekhdam title"
                            placeholder="عنوان"
                            required
                        />
                        <div class="flex">
                            <flux:spacer />
                            <flux:button type="submit" variant="primary" class="w-full">
                                ایجاد وضعیت استخدام جدید
                            </flux:button>
                        </div>
                    </form>
                </div>
            </flux:modal>
        </div>

        @if (session()->has('message'))
            <div class="relative  overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 font-bold  bg-green-100 text-green-800">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="relative h-full flex  items-center justify-center  overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden max-w-screen-lg  md:w-3/5  h-8/12">
            <div class="overflow-x-auto">
                <flux:input  wire:model.live="search" placeholder="جستجو..." class="w-full"/>
                <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 w-15 border-r">ردیف</th>
                        <th scope="col" class="px-4 py-3 border-r">نام</th>
                        <th scope="col" class="px-4 py-3 w-28 border-r">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($estekhdams as $estekhdam)
                        <tr wire:key="{{ $estekhdam->id }}" class="border-b dark:border-gray-700 text-right">
                            <td class="px-4 py-3 border-r">{{ $estekhdam->id }}</td>
                            <td class="px-4 py-3 border-r">
                                @if ($editingId === $estekhdam->id)
                                    <flux:input type="text" wire:model="name" class="border rounded p-1"/>
                                    <flux:button wire:click="updateEstekhdam" class="ml-2 text-blue-500">✔️</flux:button>
                                    <flux:button wire:click="$set('editingId', null)" class="text-red-500">✖️</flux:button>
                                @else
                                    {{ $estekhdam->name }}
                                @endif
                            </td>
                            <td class="px-4 py-3 flex items-center justify-end border-r">
                                <flux:button wire:click="editEstekhdam({{ $estekhdam->id }})" class="px-1 py-1 text-blue-500 rounded m-1">
                                    ✏️
                                </flux:button>
                                <flux:button wire:confirm="مطمئن هستید؟" wire:click="delete({{ $estekhdam->id }})"
                                        class="px-1 py-1 text-black rounded m-1">
                                    🗑️
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $estekhdams->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
