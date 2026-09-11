@if ($currentUser && ($currentUser->isSupervisor() || $currentUser->isAdmin()))
    <div x-show="activeTab === 'builder'" class="flex-1 overflow-y-auto p-5 space-y-5" style="display: none;">

        <!-- Clean Card Tambah Parameter -->
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
            <h3 class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center">
                <svg class="w-4 h-4 mr-1 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Tambah Parameter Baru ({{ $machine->name }})
            </h3>
            <p class="text-[11px] text-slate-500 mb-3">
                Parameter yang ditambahkan akan otomatis muncul di form pengisian logsheet mesin ini.
            </p>

            <form
                action="{{ route('form-builder.parameter.store', $machine->id) }}?machine_id={{ $machine->id }}&tab=builder"
                method="POST">
                @csrf
                <input type="hidden" name="template_id" value="{{ $template->id }}">

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <div class="sm:col-span-5">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Nama Parameter *</label>
                        <x-input type="text" name="name" required
                            placeholder="Contoh: Suhu Bearing, Tekanan Oli..." class="bg-white shadow-sm" />
                    </div>
                    <div class="sm:col-span-5">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Syarat / Acuan (Teks
                            Bebas)</label>
                        <x-input type="text" name="requirement" placeholder="Contoh: < 35 °C, Baik"
                            class="bg-white shadow-sm" />
                    </div>
                    <div class="sm:col-span-2 flex items-end">
                        <x-button type="submit" variant="teal" size="xs" class="w-full justify-center">
                            Simpan
                        </x-button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Daftar Parameter Aktif -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <div
                        class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Daftar Parameter Terdaftar</h3>
                        <p class="text-slate-400 text-[10px] mt-0.5">Sebanyak {{ $template->parameters->count() }}
                            parameter aktif</p>
                    </div>
                </div>
                <button type="button" @click="activeTab = 'form'"
                    class="text-[11px] font-bold underline text-rose-500 hover:text-rose-700 flex items-center space-x-1">
                    <span>&larr; Selesai & Kembali ke Form</span>
                </button>
            </div>

            <table class="w-full text-left text-xs">
                <thead
                    class="bg-slate-50/50 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3 w-12 text-center">No</th>
                        <th class="px-4 py-3">Nama Parameter</th>
                        <th class="px-4 py-3">Syarat (Acuan Teks Bebas)</th>
                        <th class="px-4 py-3 text-right w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($template->parameters as $idx => $param)
                        <tr class="hover:bg-slate-50/80 transition-colors" x-data="{ editing: false }">
                            <td class="px-4 py-3 text-center text-slate-400 font-medium">
                                {{ $idx + 1 }}
                            </td>

                            <!-- Normal Mode -->
                            <td class="px-4 py-3 font-bold text-slate-800" x-show="!editing">
                                {{ $param->name }}
                            </td>
                            <td class="px-4 py-3" x-show="!editing">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded bg-amber-50 text-amber-900 border border-amber-200 text-[10px] font-medium">
                                    {{ $param->requirement ?? 'Bebas / Baik' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right" x-show="!editing">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <x-button type="button" @click="editing = true" icon outline variant="warning"
                                        size="xs" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </x-button>
                                    <form
                                        action="{{ route('form-builder.parameter.destroy', $param->id) }}?machine_id={{ $machine->id }}&tab=builder"
                                        method="POST" class="inline" data-confirm="Hapus parameter ini?"
                                        data-confirm-title="Hapus Parameter" data-confirm-button="Ya, Hapus">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="danger" icon outline size="xs"
                                            title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                                                <path
                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM6.5 1a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                                            </svg>
                                        </x-button>
                                    </form>
                                </div>
                            </td>

                            <!-- Inline Edit Mode -->
                            <td colspan="3" class="px-4 py-3 bg-teal-50/30" x-show="editing"
                                style="display: none;">
                                <form
                                    action="{{ route('form-builder.parameter.update', $param->id) }}?machine_id={{ $machine->id }}&tab=builder"
                                    method="POST" class="flex items-center gap-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex-1">
                                        <x-input type="text" name="name" value="{{ $param->name }}" required
                                            class="w-full shadow-sm bg-white" />
                                    </div>
                                    <div class="w-64">
                                        <x-input type="text" name="requirement" value="{{ $param->requirement }}"
                                            placeholder="Syarat..." class="w-full shadow-sm bg-white" />
                                    </div>
                                    <div class="flex items-center justify-end space-x-1.5">
                                        <x-button type="submit" variant="teal" size="xs" class="px-2"
                                            title="Simpan">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </x-button>
                                        <x-button type="button" @click="editing = false" outline variant="tertiary"
                                            size="xs" class="px-2" title="Batal">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </x-button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">
                                Belum ada parameter terdaftar. Tambahkan parameter melalui form di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endif
