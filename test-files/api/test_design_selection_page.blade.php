<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Design Selection</title>
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">🧪 Test Design Selection</h1>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Design Selection Test</h2>

            <!-- Design Selector Component -->
            @livewire('design-selector')

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-medium text-blue-800 mb-2">Debug Information</h3>
                <div id="debug-info" class="text-sm text-blue-700">
                    <p>Selected Designs: <span id="selected-count">0</span></p>
                    <p>Design Notes: <span id="notes-count">0</span></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Appointment Booking Test</h2>

            <!-- Book Appointment Component -->
            @livewire('book-appointment-with-orders', ['userId' => 1])
        </div>
    </div>

    @livewireScripts

    <script>
        // Debug script to monitor design selection
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔍 Design Selection Test Page Loaded');

            // Listen for Livewire events
            Livewire.on('designAdded', (data) => {
                console.log('🎨 Design Added Event:', data);
                updateDebugInfo();
            });

            Livewire.on('designRemoved', (data) => {
                console.log('❌ Design Removed Event:', data);
                updateDebugInfo();
            });

            Livewire.on('designNoteUpdated', (data) => {
                console.log('📝 Design Note Updated Event:', data);
                updateDebugInfo();
            });

            Livewire.on('designsCleared', () => {
                console.log('🧹 Designs Cleared Event');
                updateDebugInfo();
            });

            function updateDebugInfo() {
                // This would need to be updated to get actual data from Livewire
                console.log('🔄 Updating debug info...');
            }

            // Monitor checkbox changes
            document.addEventListener('change', function(e) {
                if (e.target.type === 'checkbox' && e.target.name === 'selectedDesigns') {
                    console.log('☑️ Checkbox changed:', {
                        designId: e.target.value,
                        checked: e.target.checked
                    });
                }
            });
        });
    </script>
</body>

</html>
