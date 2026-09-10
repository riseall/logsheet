<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-sm mb-4"
            x-data="{ show: true }" x-show="show">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-xl text-sm shadow-sm mb-4">
            <div class="font-bold mb-1 font-headline">Terdapat kesalahan pengisian:</div>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
