<div x-show="activeTab === 'form'" class="flex-1 flex flex-col overflow-hidden">
    <form action="{{ route('logsheet.store', $machine->id) }}" method="POST" class="flex-1 flex flex-col overflow-hidden">
        @csrf
        <input type="hidden" name="form_template_id" value="{{ $template->id }}">

        <div class="flex-1 overflow-y-auto p-5 space-y-5">

            <!-- Clean Info Bar -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Tanggal Pemeriksaan *</label>
                        <x-input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                            class="bg-white shadow-sm" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Shift Kerja *</label>
                        <x-select name="shift" required class="bg-white shadow-sm">
                            <option value="1" {{ old('shift') == '1' ? 'selected' : '' }}>Shift 1 (Pagi: 07.00 -
                                15.00)</option>
                            <option value="2" {{ old('shift') == '2' ? 'selected' : '' }}>Shift 2 (Sore: 15.00 -
                                23.00)</option>
                            <option value="3" {{ old('shift') == '3' ? 'selected' : '' }}>Shift 3 (Malam: 23.00 -
                                07.00)</option>
                        </x-select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Teknisi Pelaksana</label>
                        <x-input type="text" readonly disabled value="{{ $currentUser->name ?? 'Teknisi Lapangan' }}"
                            class="bg-slate-100 border-slate-200 text-slate-500 shadow-none font-medium cursor-not-allowed" />
                    </div>
                </div>
            </div>

            <!-- Clean Parameter Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm">Daftar Parameter Uji</h3>
                            <p class="text-slate-400 text-[10px] mt-0.5">Sebanyak {{ $template->parameters->count() }}
                                parameter butuh pemeriksaan</p>
                        </div>
                    </div>
                    @if ($currentUser && ($currentUser->isSupervisor() || $currentUser->isAdmin()))
                        <button type="button" @click="activeTab = 'builder'"
                            class="text-[11px] font-bold underline text-teal-500 hover:text-teal-600 flex items-center space-x-1">
                            <span>+ Kelola Parameter</span>
                        </button>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead
                            class="bg-slate-50/50 text-slate-500 font-semibold border-b border-slate-100 text-[10px] uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 w-10 text-center">No</th>
                                <th class="px-4 py-3 w-52">Parameter Uji</th>
                                <th class="px-4 py-3 w-48">Syarat (Acuan)</th>
                                <th class="px-4 py-3 w-36">Nilai Isian</th>
                                <th class="px-4 py-3 w-48 text-center">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($template->parameters as $idx => $param)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3 text-center text-slate-400 font-medium">
                                        {{ $idx + 1 }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-bold text-slate-800 block">{{ $param->name }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded bg-amber-50 text-amber-900 border border-amber-200 text-[10px] font-medium">
                                            {{ $param->requirement ?? 'Baik' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <x-input type="text" name="params[{{ $param->id }}][value]"
                                            value="{{ old('params.' . $param->id . '.value') }}"
                                            placeholder="Hasil Periksa..." required class="shadow-sm w-full" />
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <!-- Simple Pill Toggle: Baik -->
                                            <label
                                                class="relative flex-1 flex items-center justify-center cursor-pointer text-[10px] py-0.5 font-semibold text-emerald-700 bg-emerald-50/50 border border-emerald-200 rounded-lg transition-colors hover:bg-emerald-100 has-[:checked]:bg-emerald-500 has-[:checked]:text-white has-[:checked]:border-emerald-600">
                                                <input type="radio"
                                                    name="params[{{ $param->id }}][condition_status]" value="baik"
                                                    {{ old('params.' . $param->id . '.condition_status', 'baik') === 'baik' ? 'checked' : '' }}
                                                    class="peer sr-only">
                                                <span>Baik</span>
                                            </label>

                                            <!-- Simple Pill Toggle: Abnormal -->
                                            <label
                                                class="relative flex-1 flex items-center justify-center cursor-pointer text-[10px] py-0.5 font-semibold text-rose-700 bg-rose-50/50 border border-rose-200 rounded-lg transition-colors hover:bg-rose-100 has-[:checked]:bg-rose-600 has-[:checked]:text-white has-[:checked]:border-rose-700">
                                                <input type="radio"
                                                    name="params[{{ $param->id }}][condition_status]"
                                                    value="perlu_perhatian"
                                                    {{ old('params.' . $param->id . '.condition_status') === 'perlu_perhatian' ? 'checked' : '' }}
                                                    class="peer sr-only">
                                                <span>Abnormal</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                        Belum ada parameter form untuk mesin ini.
                                        @if ($currentUser->isSupervisor() || $currentUser->isAdmin())
                                            <button type="button" @click="activeTab = 'builder'"
                                                class="text-teal-600 underline font-medium ml-1">
                                                Klik di sini untuk menambah parameter
                                            </button>.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Clean Action Footer -->
        <div class="px-5 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between flex-shrink-0">
            <span class="text-[11px] text-slate-500">
                Data akan direkam atas nama <strong>{{ explode(' ', $currentUser->name ?? 'Teknisi')[0] }}</strong>
            </span>

            <div class="flex items-center space-x-2">
                <x-button type="submit" name="action" value="submit" variant="teal" class="shadow-sm" size="xs">
                    🚀 Kirim Logsheet
                </x-button>
            </div>
        </div>

    </form>
</div>
