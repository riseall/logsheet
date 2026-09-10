<!-- Sidebar Top Header -->
<div class="p-4 bg-slate-50/80 border-b border-slate-200/80">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center space-x-3">
            <div
                class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-base shadow-sm shadow-teal-200">
                🏢
            </div>
            <div>
                <div class="flex items-center gap-1.5 mb-1">
                    <span
                        class="text-[10px] font-extrabold px-2 py-0.5 rounded bg-teal-100 text-teal-900 border border-teal-200 uppercase tracking-wider">
                        {{ $category->name }}
                    </span>
                </div>
                <h2 class="text-sm font-bold text-slate-900 leading-tight">{{ $building->name }}</h2>
            </div>
        </div>
        <x-button href="{{ route('logsheet.buildings', $category->id) }}" variant="danger" size="xs"
            title="Ganti Gedung" class="flex-shrink-0 gap-1.5">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m15 19-7-7 7-7" />
            </svg>
            <span>Kembali</span>
        </x-button>
    </div>

    <!-- Search Input (WhatsApp search bar style) -->
    <div class="relative">
        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5 pointer-events-none" fill="none"
            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" x-model="search" placeholder="Cari nama mesin..."
            class="w-full text-xs rounded-xl bg-white border border-slate-200 pl-9 pr-3 py-2 text-slate-700 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-sm placeholder:text-slate-400">
    </div>
</div>

<!-- Scrollable Machine List Items -->
<div class="flex-1 overflow-y-auto relative bg-white">
    <!-- Sliding Indicator -->
    <div class="absolute left-0 w-1 bg-teal-600 rounded-r-md transition-all duration-300 ease-out z-10"
        :style="`top: ${indicatorTop}px; height: ${indicatorHeight}px; opacity: ${indicatorOpacity}`"></div>

    <div class="divide-y divide-slate-100">
        @forelse($machines as $m)
            @php
                $template = $m->latestTemplate;
                $paramCount = $template ? $template->parameters->count() : 0;
                $lastLogsheet = $m->logsheets->first();
            @endphp
            <div id="machine-item-{{ $m->id }}" @click="activeId = {{ $m->id }}; mobileShowDetail = true"
                x-show="!search || '{{ strtolower($m->name . ' ' . $m->code) }}'.includes(search.toLowerCase())"
                :class="activeId === {{ $m->id }} ? 'bg-teal-50/60' : 'hover:bg-slate-50/80'"
                class="p-3.5 cursor-pointer transition flex items-start space-x-3 select-none">

                <!-- Avatar / Machine Icon with active dot -->
                <div class="relative flex-shrink-0">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-lg font-bold transition"
                        :class="activeId === {{ $m->id }} ?
                            'bg-white text-teal-700 shadow-sm shadow-teal-200/50' :
                            'bg-slate-100 text-slate-500'">
                        <x-category-icon :code="$category->code" class="w-5 h-5" />
                    </div>
                    <span class="w-3 h-3 rounded-full absolute -bottom-0.5 -right-0.5 ring-2 transition-colors"
                        :class="activeId === {{ $m->id }} ?
                            '{{ $m->status_aktif ? 'bg-emerald-500' : 'bg-slate-400' }} ring-teal-50' :
                            '{{ $m->status_aktif ? 'bg-emerald-500' : 'bg-slate-400' }} ring-white'"></span>
                </div>

                <!-- Info Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <h3 class="text-xs font-bold text-slate-900 truncate"
                            :class="activeId === {{ $m->id }} ? 'text-teal-900' : 'text-slate-800'">
                            {{ $m->name }}
                        </h3>
                        @if ($lastLogsheet)
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ $lastLogsheet->date ? $lastLogsheet->date->format('d/m') : '' }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-500">
                        <span class="font-mono text-slate-400">{{ $m->code }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-md font-medium"
                            :class="activeId === {{ $m->id }} ? 'bg-teal-100 text-teal-800' :
                                'bg-slate-100 text-slate-600'">
                            {{ $paramCount }} Param
                        </span>
                    </div>

                    @if ($lastLogsheet)
                        <p class="text-[10px] text-slate-400 mt-1 truncate">
                            Terakhir: Shift {{ $lastLogsheet->shift }}
                            ({{ $lastLogsheet->teknisi->name ?? '-' }})
                        </p>
                    @else
                        <p class="text-[10px] text-amber-600 font-medium mt-1">
                            Belum ada logsheet hari ini &bull; Klik untuk isi
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-slate-400 text-xs">
                Tidak ada unit mesin di gedung ini.
            </div>
        @endforelse
    </div>
</div>

@if ($currentUser && ($currentUser->isSupervisor() || $currentUser->isAdmin()))
    <div class="p-3 bg-white border-slate-200/80" x-data="{ showModal: false }">
        <x-button @click="showModal = true" variant="teal" outline size="sm" class="w-full border-dashed">
            + Tambah Mesin Baru
        </x-button>

        <!-- Modal Tambah Mesin -->
        <x-modal show="showModal" title="Tambah Mesin Baru">
            <form
                action="{{ route('logsheet.machine.store', ['category' => $category->id, 'building' => $building->id]) }}"
                method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama Mesin *</label>
                    <x-input type="text" name="name" required placeholder="Contoh: Genset Utama" />
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Kode Mesin *</label>
                    <x-input type="text" name="code" required placeholder="Contoh: GEN-01" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Tipe / Model</label>
                        <x-input type="text" name="type" placeholder="Opsional" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">No. Asset</label>
                        <x-input type="text" name="asset_number" placeholder="Opsional" />
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Ruangan / Lokasi Spesifik</label>
                    <x-input type="text" name="room" placeholder="Opsional" />
                </div>
                <div class="pt-3 flex justify-end space-x-2">
                    <x-button type="button" @click="showModal = false" variant="secondary"
                        size="sm">Batal</x-button>
                    <x-button type="submit" variant="teal" size="sm">Simpan Mesin</x-button>
                </div>
            </form>
        </x-modal>
    </div>
@endif

<!-- Sidebar Bottom Info -->
<div
    class="px-4 bg-slate-50 border-t border-slate-200/80 text-[11px] text-slate-500 flex justify-between items-center h-[60px] flex-shrink-0">
    <span>{{ $machines->count() }} Mesin di Gedung ini</span>
    <span class="text-teal-600 font-medium">{{ $category->code }}</span>
</div>
