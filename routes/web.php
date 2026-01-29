<?php

use Illuminate\Support\Facades\Route;

// Health check endpoint for Render monitoring
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'version' => app()->version(),
    ]);
});

// API documentation placeholder
Route::get('/docs', function () {
    return response()->json([
        'api' => 'Laravel Marketplace API',
        'version' => '1.0',
        'documentation' => 'API documentation will be available soon',
        'endpoints' => [
            'GET /health' => 'Health check',
            'GET /docs' => 'This documentation',
            'GET /' => 'Home page',
        ],
    ]);
});

// Home page
Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to Laravel Marketplace API',
        'documentation' => '/docs',
        'health' => '/health',
        'environment' => app()->environment(),
    ]);
});




