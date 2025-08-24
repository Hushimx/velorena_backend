<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Swagger Documentation Routes
|--------------------------------------------------------------------------
|
| Here we register the routes for the Swagger API documentation.
| These routes are loaded outside of any middleware groups to avoid conflicts.
|
*/

// Direct Swagger UI route
Route::get('/api/documentation', function () {
    try {
        // Check if documentation file exists
        $docPath = storage_path('api-docs/api-docs.json');
        if (!file_exists($docPath)) {
            return response()->json([
                'error' => 'API documentation not found. Please run: php artisan l5-swagger:generate'
            ], 404);
        }

        // Serve the HTML file directly
        return response()->file(public_path('swagger-ui.html'));
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to load API documentation: ' . $e->getMessage()
        ], 500);
    }
})->name('l5-swagger.default.docs');

// API JSON endpoint
Route::get('/api/documentation/json', function () {
    $docPath = storage_path('api-docs/api-docs.json');
    if (!file_exists($docPath)) {
        return response()->json([
            'error' => 'API documentation not found. Please run: php artisan l5-swagger:generate'
        ], 404);
    }
    
    return response()->file($docPath, [
        'Content-Type' => 'application/json'
    ]);
})->name('l5-swagger.default.api');

// Assets route
Route::get('/api/documentation/asset/{asset}', function ($asset) {
    $assetPath = public_path('vendor/swagger-api/swagger-ui/dist/' . $asset);
    if (!file_exists($assetPath)) {
        abort(404);
    }
    
    return response()->file($assetPath);
})->name('l5-swagger.default.asset');

// Redirect route
Route::get('/docs', function () {
    return redirect('/api/documentation');
})->name('docs');
