@extends('layouts.app')

@section('title', 'Pilih Bangunan - ' . $category->name)

@section('content')
    <div class="space-y-6" x-data="{ showAddModal: false }">

        <!-- Header & Step Indicator -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <!-- Left: Category Identity & Title -->
                <div class="flex items-start sm:items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-2xl bg-teal-50 border border-teal-200 text-teal-700 flex items-center justify-center flex-shrink-0 shadow-xs mt-0.5">
                        <x-category-icon :code="$category->code" class="w-7 h-7" />
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-extrabold bg-teal-100 text-teal-900 border border-teal-200 shadow-xs">
                                <span class="w-2 h-2 rounded-full bg-teal-600 animate-pulse"></span>
                                Kategori: {{ $category->name }}
                            </span>
                        </div>
                        <h1 class="text-2xl font-headline font-bold text-[#1E3A5F]">Pilih Gedung / Bangunan</h1>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Menampilkan seluruh gedung yang memiliki unit mesin kategori <strong
                                class="text-teal-800 font-bold">{{ $category->name }}</strong>.
                        </p>
                    </div>
                </div>

                <!-- Right: Step Progression & Actions -->
                <div
                    class="flex flex-col sm:flex-row lg:flex-col sm:items-center lg:items-end gap-3 pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                    <div class="flex items-center space-x-2 text-xs">
                        <a href="{{ route('logsheet.index') }}" title="Klik untuk ganti kategori"
                            class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold hover:bg-emerald-100 transition group shadow-xs">
                            <span
                                class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px]">&check;</span>
                            <span class="group-hover:underline">{{ $category->name }}</span>
                        </a>
                        <span class="text-slate-300">&rarr;</span>
                        <span
                            class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-600 text-white font-bold shadow-sm shadow-emerald-200">
                            <span
                                class="w-4 h-4 rounded-full bg-white text-emerald-700 flex items-center justify-center text-[10px]">2</span>
                            <span>Bangunan</span>
                        </span>
                        <span class="text-slate-300">&rarr;</span>
                        <span class="flex items-center gap-1.5 px-2.5 py-1 text-slate-400 font-medium">
                            <span
                                class="w-4 h-4 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[10px]">3</span>
                            <span>Mesin</span>
                        </span>
                    </div>
                    @if ($currentUser && ($currentUser->isSupervisor() || $currentUser->isAdmin()))
                        <x-button @click="showAddModal = true" variant="teal" outline size="xs">
                            + Tambah Gedung
                        </x-button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Building Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($buildings as $b)
                <div x-data="{ editMode: false }"
                    class="relative group bg-white p-6 rounded-xl border border-slate-200 hover:border-emerald-600 hover:shadow-md transition flex flex-col justify-between">

                    <!-- Normal View -->
                    <div class="flex flex-col h-full justify-between">
                        <a href="{{ route('logsheet.machines', [$category->id, $b->id]) }}" class="block">
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="w-12 h-12 rounded-lg bg-slate-100 text-slate-700 group-hover:bg-emerald-600 group-hover:text-white transition flex items-center justify-center font-headline font-bold text-sm">
                                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M4 4a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2v14a1 1 0 1 1 0 2H5a1 1 0 1 1 0-2V5a1 1 0 0 1-1-1Zm5 2a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H9Zm5 0a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-1Zm-5 4a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1H9Zm5 0a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-1Zm-3 4a2 2 0 0 0-2 2v3h2v-3h2v3h2v-3a2 2 0 0 0-2-2h-2Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 font-semibold">
                                    {{ $b->mesins_count }} Mesin {{ $category->name }}
                                </span>
                            </div>
                            <h2
                                class="text-lg font-headline font-bold text-[#1E3A5F] group-hover:text-emerald-700 transition">
                                {{ $b->name }}</h2>
                            <p class="text-xs text-slate-500 mt-2 flex items-center">
                                <span class="mr-1">📍</span> {{ $b->location ?? 'Area Pabrik' }}
                            </p>
                        </a>

                        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('logsheet.machines', [$category->id, $b->id]) }}"
                                class="text-xs font-semibold text-slate-500 group-hover:text-[#1E3A5F] flex-1">Pilih Gedung
                                Ini</a>

                            @if ($currentUser && ($currentUser->isSupervisor() || $currentUser->isAdmin()))
                                <div class="flex space-x-2 relative z-10">
                                    <button @click="editMode = true" type="button"
                                        class="w-9 h-9 rounded-full bg-slate-100/80 hover:bg-amber-100 hover:scale-110 text-slate-500 hover:text-amber-600 flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow"
                                        title="Edit Gedung">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('logsheet.building.destroy', $b->id) }}" method="POST"
                                        class="inline" id="form-delete-{{ $b->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $b->id }}')"
                                            class="w-9 h-9 rounded-full bg-slate-100/80 hover:bg-red-100 hover:scale-110 text-slate-500 hover:text-red-600 flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow"
                                            title="Hapus Gedung">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('logsheet.machines', [$category->id, $b->id]) }}"
                                    class="w-8 h-8 rounded-full bg-slate-50 group-hover:bg-emerald-50 text-slate-400 group-hover:text-emerald-700 flex items-center justify-center transition">
                                    &rarr;
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Modal Edit Gedung -->
                    @if ($currentUser && ($currentUser->isSupervisor() || $currentUser->isAdmin()))
                        <x-modal show="editMode" title="Edit Data Gedung: {{ $b->name }}">
                            <form action="{{ route('logsheet.building.update', $b->id) }}" method="POST"
                                class="p-5 space-y-4">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama Gedung *</label>
                                    <x-input type="text" name="name" value="{{ $b->name }}" required />
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Lokasi (Opsional)</label>
                                    <x-input type="text" name="location" value="{{ $b->location }}"
                                        placeholder="Contoh: Area Produksi" />
                                </div>
                                <div class="pt-2 flex justify-end space-x-2">
                                    <x-button @click="editMode = false" type="button" variant="secondary"
                                        size="sm">Batal</x-button>
                                    <x-button type="submit" variant="teal" size="sm">Simpan Perubahan</x-button>
                                </div>
                            </form>
                        </x-modal>
                    @endif
                </div>
            @empty
                <div class="col-span-3 bg-white p-12 rounded-xl border border-slate-200 text-center">
                    <p class="text-slate-500 text-sm">Belum ada mesin untuk kategori {{ $category->name }} di gedung
                        manapun.</p>
                    <div class="mt-4">
                        <a href="{{ route('logsheet.index') }}"
                            class="text-xs font-semibold text-emerald-700 hover:underline">&larr; Kembali ke Kategori</a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Modal Tambah Gedung (Admin / SPV) -->
        @if ($currentUser && ($currentUser->isSupervisor() || $currentUser->isAdmin()))
            <x-modal show="showAddModal" title="Tambah Data Gedung">
                <form action="{{ route('logsheet.building.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama Gedung *</label>
                        <x-input type="text" name="name" required placeholder="Contoh: Gedung A" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Lokasi (Opsional)</label>
                        <x-input type="text" name="location" placeholder="Contoh: Area Produksi" />
                    </div>
                    <div class="pt-2 flex justify-end space-x-2">
                        <x-button type="button" @click="showAddModal = false" variant="secondary" size="sm">
                            Batal
                        </x-button>
                        <x-button type="submit" variant="teal" size="sm">
                            Simpan
                        </x-button>
                    </div>
                </form>
            </x-modal>
        @endif
    </div>

    @push('scripts')
        <script>
            function confirmDelete(id) {
                Notify.confirm('Hapus Gedung?', 'Pastikan gedung ini tidak memiliki unit mesin di dalamnya!', () => {
                    document.getElementById('form-delete-' + id).submit();
                });
            }
        </script>
    @endpush
@endsection
