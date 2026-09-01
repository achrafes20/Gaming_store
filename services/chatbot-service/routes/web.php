<?php

use App\Support\Metrics;
use Illuminate\Support\Facades\Route;
use Prometheus\RenderTextFormat;

Route::get('/', function () {
    return view('welcome');
});

// Never reachable through the public gateway — see gateway/nginx.conf (it
// only proxies /api/*, /uploads/, and / -> web-bff) — Prometheus scrapes
// this directly, container-to-container, same as any other internal call.
Route::get('/metrics', fn () => response(Metrics::render())->header('Content-Type', RenderTextFormat::MIME_TYPE));
