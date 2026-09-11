@extends('layouts.app')

@section('title', 'Pilih & Isi Logsheet Mesin - ' . $building->name)

@section('content')
    <div class="max-w-7xl mx-auto" x-data="{
        activeId: {{ request('machine_id', $selectedMachineId ?? ($machines->first()->id ?? 0)) }},
        search: '',
        mobileShowDetail: false,
        indicatorTop: 0,
        indicatorHeight: 0,
        indicatorOpacity: 0,
        updateIndicator() {
            this.$nextTick(() => {
                const activeEl = document.getElementById('machine-item-' + this.activeId);
                if (activeEl && activeEl.style.display !== 'none') {
                    this.indicatorTop = activeEl.offsetTop;
                    this.indicatorHeight = activeEl.offsetHeight;
                    this.indicatorOpacity = 1;
                } else {
                    this.indicatorOpacity = 0;
                }
            });
        },
        init() {
            this.updateIndicator();
            this.$watch('activeId', () => this.updateIndicator());
            this.$watch('search', () => setTimeout(() => this.updateIndicator(), 50));
        }
    }">

        <!-- Breadcrumb Nav -->
        <div class="mb-4 flex items-center justify-between text-xs text-slate-500">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-teal-600 font-medium">Home</a>
                <span class="text-slate-300">&rsaquo;</span>
                <a href="{{ route('logsheet.index') }}"
                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-teal-50 text-teal-800 border border-teal-200 font-bold hover:bg-teal-100 transition shadow-xs"
                    title="Ganti Kategori">
                    <x-category-icon :code="$category->code" class="w-3.5 h-3.5 text-teal-600" />
                    <span>Kategori: {{ $category->name }}</span>
                </a>
                <span class="text-slate-300">&rsaquo;</span>
                <a href="{{ route('logsheet.buildings', $category->id) }}"
                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200 font-semibold hover:bg-slate-200 transition"
                    title="Ganti Gedung">
                    <span>🏢</span>
                    <span>{{ $building->name }}</span>
                </a>
                <span class="text-slate-300">&rsaquo;</span>
                <span class="font-bold text-slate-900 bg-white px-2 py-0.5 rounded border border-slate-200">Unit
                    Mesin</span>
            </div>
            <div class="hidden sm:flex items-center space-x-2">
                <span class="relative flex size-3">
                    <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex size-3 rounded-full bg-emerald-500"></span>
                </span>
            </div>
        </div>

        <!-- WhatsApp Web Style Split Layout Window -->
        <div
            class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col md:flex-row h-[820px] max-h-[calc(100vh-8.5rem)]">

            <!-- ==================== LEFT SIDEBAR (DAFTAR MESIN) ==================== -->
            <div class="w-full md:w-80 lg:w-96 flex-shrink-0 border-r border-slate-200/80 bg-white flex flex-col h-full"
                :class="mobileShowDetail ? 'hidden md:flex' : 'flex'">

                @include('logsheet.partials.machine-sidebar')
            </div>

            <!-- ==================== RIGHT PANEL (FORM PENGISIAN & EMBEDDED FORM BUILDER) ==================== -->
            <div class="flex-1 flex flex-col bg-white overflow-hidden h-full"
                :class="mobileShowDetail ? 'flex' : 'hidden md:flex'">

                @forelse($machines as $machine)
                    @php
                        $template = $machine->latestTemplate;
                    @endphp
                    <div x-show="activeId === {{ $machine->id }}" x-data="{ activeTab: '{{ request('machine_id') == $machine->id ? request('tab', 'form') : 'form' }}' }"
                        class="flex flex-col h-full overflow-hidden" style="display: none;">

                        <!-- Right Panel Top Header -->
                        <div
                            class="p-4 bg-slate-50/80 border-b border-slate-200/80 flex items-center justify-between flex-shrink-0">
                            <div class="flex items-center space-x-3">
                                <!-- Mobile Back Button -->
                                <button @click="mobileShowDetail = false"
                                    class="md:hidden text-slate-600 hover:text-slate-900 pr-2">
                                    &larr;
                                </button>

                                <div
                                    class="w-10 h-10 rounded-2xl bg-teal-600 text-white flex items-center justify-center text-lg font-bold shadow-sm shadow-teal-200">
                                    <x-category-icon :code="$category->code" class="w-5 h-5" />
                                </div>

                                <div>
                                    <div class="flex items-center space-x-2">
                                        <h2 class="text-sm font-bold text-slate-900">{{ $machine->name }}</h2>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            ● Aktif
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1">
                                        <span class="font-semibold">{{ $machine->type ?? '-' }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="hidden sm:flex items-center space-x-2">
                                <div
                                    class="items-center px-3 py-1.5 rounded-lg bg-teal-50 border border-teal-200/80 text-teal-800 text-xs font-semibold">
                                    <span>📝 Input Logsheet</span>
                                </div>

                                @if ($currentUser && ($currentUser->isSupervisor() || $currentUser->isAdmin()))
                                    <div x-data="{ showEditModal: false }">
                                        <x-button @click="showEditModal = true" icon outline variant="warning"
                                            title="Edit Mesin" size="xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd"
                                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                            </svg>
                                        </x-button>

                                        <!-- Edit Modal -->
                                        <x-modal show="showEditModal" title="Edit Data Mesin">
                                            <form action="{{ route('logsheet.machine.update', $machine->id) }}"
                                                method="POST" class="p-5 space-y-4">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="code" value="{{ $machine->code }}">
                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama
                                                        Mesin (Pilih dari PMMT) *</label>
                                                    <select name="name" required
                                                        class="select2-pmmt-edit w-full text-xs rounded-xl bg-white border border-slate-200 px-3 py-2 text-slate-700 focus:ring-2 focus:ring-teal-500 shadow-sm"
                                                        style="width: 100%">
                                                        <option value="">-- Cari & Pilih Mesin --</option>
                                                        @php $found = false; @endphp
                                                        @foreach ($pmmtMachines as $pmmt)
                                                            @php
                                                                if ($machine->name == $pmmt->nama_mesin) {
                                                                    $found = true;
                                                                }
                                                            @endphp
                                                            <option value="{{ $pmmt->nama_mesin }}"
                                                                data-type="{{ $pmmt->tipe_mesin }}"
                                                                data-asset="{{ $pmmt->no_asset }}"
                                                                {{ $machine->name == $pmmt->nama_mesin ? 'selected' : '' }}>
                                                                {{ $pmmt->nama_mesin }}</option>
                                                        @endforeach

                                                        @if (!$found && $machine->name)
                                                            <option value="{{ $machine->name }}"
                                                                data-type="{{ $machine->type }}"
                                                                data-asset="{{ $machine->asset_number }}" selected>
                                                                {{ $machine->name }} (Manual)</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Tipe
                                                            / Jenis</label>
                                                        <x-input type="text" name="type" value="{{ $machine->type }}"
                                                            class="input-type-edit" placeholder="Tipe Mesin" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">No.
                                                            Asset</label>
                                                        <x-input type="text" name="asset_number" class="input-asset-edit"
                                                            value="{{ $machine->asset_number }}" placeholder="No. Asset" />
                                                    </div>
                                                </div>
                                                <div class="pt-3 flex justify-end space-x-2">
                                                    <x-button type="button" @click="showEditModal = false"
                                                        variant="secondary" size="sm">Batal</x-button>
                                                    <x-button type="submit" variant="teal" size="sm">Update
                                                        Mesin</x-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </div>

                                    <form action="{{ route('logsheet.machine.destroy', $machine->id) }}" method="POST"
                                        data-confirm="Apakah Anda yakin ingin menonaktifkan mesin ini?"
                                        data-confirm-title="Nonaktifkan Mesin" data-confirm-button="Ya, Nonaktifkan">
                                        @csrf @method('DELETE')
                                        <x-button type="submit" icon outline variant="danger" size="xs"
                                            title="Hapus Mesin">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                                                <path
                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM6.5 1a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                                            </svg>
                                        </x-button>
                                    </form>
                                @endif
                            </div>
                        </div>


                        <!-- ==================== TAB 1: FORM PENGISIAN LOGSHEET ==================== -->
                        @include('logsheet.partials.machine-form')

                        <!-- ==================== TAB 2: EMBEDDED FORM BUILDER ==================== -->
                        @include('logsheet.partials.machine-builder')

                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="flex-1 flex flex-col items-center justify-center p-12 text-center text-slate-400">
                        <div class="w-16 h-16 rounded-3xl bg-slate-100 flex items-center justify-center text-3xl mb-4">
                            🏭
                        </div>
                        <h3 class="text-base font-bold text-slate-700">Belum Ada Mesin</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm">
                            Tidak ada mesin aktif yang terdaftar pada kategori dan gedung ini.
                        </p>
                    </div>
                @endforelse

                <!-- Default Empty placeholder when no machine selected -->
                <div x-show="!activeId && {{ $machines->count() }} > 0"
                    class="flex-1 flex flex-col items-center justify-center p-12 text-center text-slate-400">
                    <div
                        class="w-16 h-16 rounded-3xl bg-teal-50 text-teal-600 flex items-center justify-center text-3xl mb-4">
                        💬
                    </div>
                    <h3 class="text-base font-bold text-slate-700">Pilih Unit Mesin</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm">
                        Pilih salah satu unit mesin di panel sebelah kiri untuk langsung membuka formulir logsheet dan
                        form
                        builder.
                    </p>
                </div>

            </div>

        </div>
    </div>
@endsection
