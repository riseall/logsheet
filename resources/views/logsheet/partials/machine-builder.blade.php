@if ($currentUser && ($currentUser->isSupervisor() || $currentUser->isAdmin()))
    <div x-show="activeTab === 'builder'" class="flex-1 overflow-y-auto p-5 space-y-5"
        style="display: none;">

        <!-- Card Tambah Parameter Baru Langsung di Mesin ini -->
        <div class="bg-teal-50/50 p-4 rounded-2xl border border-teal-200/70">
            <h3 class="text-xs font-bold text-teal-900 uppercase tracking-wider mb-2">
                + Tambah Parameter Baru untuk {{ $machine->name }}
            </h3>
            <p class="text-[11px] text-slate-500 mb-3">
                Parameter yang ditambahkan akan otomatis muncul di form pengisian logsheet mesin
                ini.
            </p>

            <form
                action="{{ route('form-builder.parameter.store', $machine->id) }}?machine_id={{ $machine->id }}&tab=builder"
                method="POST">
                @csrf
                <input type="hidden" name="template_id" value="{{ $template->id }}">

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <div class="sm:col-span-6">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama
                            Parameter *</label>
                        <x-input type="text" name="name" required
                            placeholder="Contoh: Suhu Bearing, Tekanan Oli, Tegangan Accu" />
                    </div>
                    <div class="sm:col-span-4">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Syarat /
                            Acuan (Teks Bebas)</label>
                        <x-input type="text" name="requirement"
                            placeholder="Contoh: < 35 °C, 3 - 5 Bar, Baik" />
                    </div>
                    <div class="sm:col-span-2 flex items-end">
                        <x-button type="submit" variant="teal" size="sm" class="w-full">
                            + Tambah
                        </x-button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Daftar Parameter Aktif -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
            <div
                class="p-3.5 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-xs">
                <div>
                    <span class="font-bold text-slate-800">Daftar Parameter Terdaftar</span>
                    <span
                        class="text-slate-400 text-[11px] ml-1">({{ $template->parameters->count() }}
                        parameter)</span>
                </div>
                <x-button type="button" @click="activeTab = 'form'" variant="secondary" size="xs">
                    &larr; Selesai & Kembali ke Form
                </x-button>
            </div>

            <table class="w-full text-left text-xs">
                <thead
                    class="bg-slate-50/50 text-slate-500 uppercase font-semibold border-b border-slate-200 text-[10px]">
                    <tr>
                        <th class="px-4 py-2.5 w-12 text-center">Urutan</th>
                        <th class="px-4 py-2.5">Nama Parameter</th>
                        <th class="px-4 py-2.5">Syarat (Acuan Teks Bebas)</th>
                        <th class="px-4 py-2.5 text-right w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($template->parameters as $idx => $param)
                        <tr class="hover:bg-slate-50/60 transition" x-data="{ editing: false }">
                            <td class="px-4 py-3 text-center text-slate-400 font-bold">
                                {{ $idx + 1 }}
                            </td>

                            <!-- Normal Mode -->
                            <td class="px-4 py-3 font-bold text-slate-900" x-show="!editing">
                                {{ $param->name }}
                            </td>
                            <td class="px-4 py-3" x-show="!editing">
                                <span
                                    class="bg-amber-50 text-amber-900 border border-amber-200 text-[11px] px-2 py-0.5 rounded-md font-medium">
                                    {{ $param->requirement ?? 'Bebas / Baik' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2" x-show="!editing">
                                <x-button type="button" @click="editing = true" variant="ghost" size="xs">
                                    Edit
                                </x-button>
                                <form
                                    action="{{ route('form-builder.parameter.destroy', $param->id) }}?machine_id={{ $machine->id }}&tab=builder"
                                    method="POST" class="inline"
                                    data-confirm="Hapus parameter ini?"
                                    data-confirm-title="Hapus Parameter"
                                    data-confirm-button="Ya, Hapus">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="ghost" size="xs" class="text-rose-600 hover:text-rose-800">
                                        Hapus
                                    </x-button>
                                </form>
                            </td>

                            <!-- Inline Edit Mode -->
                            <td colspan="3" class="px-4 py-2.5 bg-teal-50/40" x-show="editing"
                                style="display: none;">
                                <form
                                    action="{{ route('form-builder.parameter.update', $param->id) }}?machine_id={{ $machine->id }}&tab=builder"
                                    method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <x-input type="text" name="name"
                                        value="{{ $param->name }}" required class="w-1/2" />
                                    <x-input type="text" name="requirement"
                                        value="{{ $param->requirement }}" placeholder="Syarat..." class="w-1/3" />
                                    <x-button type="submit" variant="teal" size="xs">
                                        Simpan
                                    </x-button>
                                    <x-button type="button" @click="editing = false" variant="secondary" size="xs">
                                        Batal
                                    </x-button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">
                                Belum ada parameter terdaftar. Tambahkan parameter melalui form di
                                atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endif
