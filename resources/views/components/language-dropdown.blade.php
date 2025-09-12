@php
    $currentLocale = app()->getLocale();
    $supportedLocales = ['ar' => 'العربية', 'en' => 'English'];
@endphp

<div class="dropdown language-dropdown">
    <button class="btn btn-outline-dark dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fas fa-globe me-1"></i>
        {{ $supportedLocales[$currentLocale] ?? 'العربية' }}
    </button>
    <ul class="dropdown-menu py-0" aria-labelledby="languageDropdown">
        @foreach ($supportedLocales as $locale => $name)
            <li>
                <a class="dropdown-item py-2 {{ $currentLocale === $locale ? 'active' : '' }}"
                    href="{{ route('language.switch', $locale) }}">
                    {{ $name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
