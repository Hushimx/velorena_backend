@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    $currentLocale = LaravelLocalization::getCurrentLocale();
    $supportedLocales = LaravelLocalization::getSupportedLocales();
@endphp

<div class="dropdown language-dropdown">
    <button class="btn btn-outline-dark dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fas fa-globe me-1"></i>
        {{ $supportedLocales[$currentLocale]['native'] ?? 'العربية' }}
    </button>
    <ul class="dropdown-menu py-0" aria-labelledby="languageDropdown" style="display: none;">
        @foreach ($supportedLocales as $localeCode => $properties)
            @php
                $url = LaravelLocalization::getLocalizedURL($localeCode, null, [], true);
                $active = $localeCode === $currentLocale;
            @endphp
            <li>
                <a class="dropdown-item py-2 {{ $active ? 'active' : '' }}" href="{{ $url }}"
                    hreflang="{{ $localeCode }}" rel="alternate">
                    {{ $properties['native'] ?? strtoupper($localeCode) }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<script>
    // Simple manual dropdown toggle
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButton = document.getElementById('languageDropdown');
        const dropdownMenu = dropdownButton.nextElementSibling;

        dropdownButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Toggle dropdown
            if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
                dropdownMenu.style.display = 'block';
                dropdownButton.setAttribute('aria-expanded', 'true');
            } else {
                dropdownMenu.style.display = 'none';
                dropdownButton.setAttribute('aria-expanded', 'false');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.style.display = 'none';
                dropdownButton.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>
