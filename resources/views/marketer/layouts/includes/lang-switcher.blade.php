@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    $current = LaravelLocalization::getCurrentLocale();
@endphp

<div x-data="{ open: false }" class="relative">
    <!-- Trigger Button -->
    <button @click="open = !open" type="button"
        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
        aria-haspopup="listbox" :aria-expanded="open">
        <span class="inline-flex items-center gap-2">
            <span class="text-xs rounded px-2 py-0.5 border bg-blue-50 text-blue-600 font-medium">{{ strtoupper($current) }}</span>
            <span class="hidden sm:inline text-gray-700">
                {{ $current === 'ar' ? 'العربية' : 'English' }}
            </span>
        </span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown -->
    <div x-cloak x-show="open" @click.outside="open = false"
        class="absolute z-50 mt-2 w-44 rounded-xl border border-gray-200 bg-white shadow-lg"
        :class="{
            'right-0': '{{ LaravelLocalization::getCurrentLocaleDirection() }}'
            === 'rtl',
            'left-0': '{{ LaravelLocalization::getCurrentLocaleDirection() }}'
            === 'ltr'
        }">
        <ul class="py-1" role="listbox">
            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                @php
                    $url = LaravelLocalization::getLocalizedURL($localeCode, null, [], true);
                    $active = $localeCode === $current;
                @endphp

                <li>
                    <a href="{{ $url }}" hreflang="{{ $localeCode }}" rel="alternate"
                        class="flex items-center justify-between px-4 py-3 text-sm hover:bg-blue-50 transition-colors duration-200 @if ($active) font-semibold bg-blue-50 @endif">
                        <span class="flex items-center gap-3">
                            <span class="text-xs rounded px-2 py-0.5 border bg-blue-50 text-blue-600 font-medium">{{ strtoupper($localeCode) }}</span>
                            <span class="text-gray-700">{{ $properties['native'] ?? strtoupper($localeCode) }}</span>
                        </span>
                        @if ($active)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-7.364 7.364a1 1 0 01-1.414 0L3.293 10.435a1 1 0 111.414-1.414l3.222 3.222 6.657-6.657a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
