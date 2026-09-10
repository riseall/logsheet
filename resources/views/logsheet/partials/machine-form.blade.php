<div x-show="activeTab === 'form'" class="flex-1 flex flex-col overflow-hidden">
    <form action="{{ route('logsheet.store', $machine->id) }}" method="POST" class="flex-1 flex flex-col overflow-hidden">
        @csrf
        <input type="hidden" name="form_template_id" value="{{ $template->id }}">

        <div class="flex-1 overflow-y-auto p-5 space-y-5">

            <!-- Info Bar: Tanggal, Shift, Petugas -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tanggal
                            Pemeriksaan *</label>

                        <x-input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required />

                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Shift
                            Kerja *</label>
                        <x-select name="shift" required>
                            <option value="1" {{ old('shift') == '1' ? 'selected' : '' }}>
                                Shift 1 (Pagi: 07.00 - 15.00)</option>
                            <option value="2" {{ old('shift') == '2' ? 'selected' : '' }}>
                                Shift 2 (Sore: 15.00 - 23.00)</option>
                            <option value="3" {{ old('shift') == '3' ? 'selected' : '' }}>
                                Shift 3 (Malam: 23.00 - 07.00)</option>
                        </x-select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Teknisi
                            Pelaksana</label>
                        <x-input type="text" readonly disabled value="{{ $currentUser->name ?? 'Teknisi Lapangan' }}" />
                    </div>
                </div>
            </div>

            <!-- Parameter Table -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
                <div class="p-3.5 bg-slate-50/90 border-b border-slate-200 flex justify-between items-center text-xs">
                    <div>
                        <span class="font-bold text-slate-800">Parameter Pemeriksaan</span>
                        <span class="text-slate-400 text-[11px] ml-1">({{ $template->parameters->count() }}
                            item)</span>
                    </div>
                    @if ($currentUser && ($currentUser->isSupervisor() || $currentUser->isAdmin()))
                        <button type="button" @click="activeTab = 'builder'"
                            class="text-[11px] font-semibold text-teal-600 hover:text-teal-800 hover:underline flex items-center space-x-1">
                            <span>+ Kelola / Tambah Parameter</span>
                        </button>
                    @endif
                </div>

                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50/50 text-slate-500 uppercase font-semibold border-b border-slate-200 text-[10px]">
                        <tr>
                            <th class="px-4 py-2.5 w-10 text-center">No</th>
                            <th class="px-4 py-2.5">Parameter</th>
                            <th class="px-4 py-2.5">Syarat (Acuan)</th>
                            <th class="px-4 py-2.5 w-44">Nilai Isian</th>
                            <th class="px-4 py-2.5 w-48 text-center">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($template->parameters as $idx => $param)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3 text-center text-slate-400 font-bold">
                                    {{ $idx + 1 }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-bold text-slate-900 block">{{ $param->name }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-block px-2 py-0.5 rounded bg-amber-50 text-amber-900 border border-amber-200 text-[11px] font-medium">
                                        {{ $param->requirement ?? 'Baik' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <x-input type="text" name="params[{{ $param->id }}][value]"
                                        value="{{ old('params.' . $param->id . '.value') }}"
                                        placeholder="Hasil periksa..." required />
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center space-x-2">
                                        <label
                                            class="flex items-center space-x-1 cursor-pointer text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-lg border border-emerald-200 transition">
                                            <input type="radio" name="params[{{ $param->id }}][condition_status]"
                                                value="baik"
                                                {{ old('params.' . $param->id . '.condition_status', 'baik') === 'baik' ? 'checked' : '' }}
                                                class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 focus:ring-emerald-500 focus:ring-2">
                                            <span>Baik</span>
                                        </label>

                                        <label
                                            class="flex items-center space-x-1 cursor-pointer text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded-lg border border-rose-200 transition">
                                            <input type="radio" name="params[{{ $param->id }}][condition_status]"
                                                value="perlu_perhatian"
                                                {{ old('params.' . $param->id . '.condition_status') === 'perlu_perhatian' ? 'checked' : '' }}
                                                class="w-4 h-4 text-rose-600 bg-gray-100 border-gray-300 focus:ring-rose-500 focus:ring-2">
                                            <span>Abnormal</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                    Belum ada parameter form untuk mesin ini.
                                    <button type="button" @click="activeTab = 'builder'"
                                        class="text-teal-600 underline font-semibold ml-1">
                                        Klik di sini untuk menambah parameter
                                    </button>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Action Footer -->
        <div
            class="px-5 bg-slate-50 border-t border-slate-200 flex items-center justify-between flex-shrink-0 h-[60px]">
            <span class="text-[11px] text-slate-500">
                Satu submission untuk seluruh parameter mesin ini
            </span>

            <div class="flex items-center space-x-2">
                {{-- <button type="submit" name="action" value="draft"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 transition shadow-xs">
                    Simpan Draft
                </button> --}}
                <x-button type="submit" name="action" value="submit" variant="teal">
                    🚀 Submit
                </x-button>
            </div>
        </div>

    </form>
</div>
