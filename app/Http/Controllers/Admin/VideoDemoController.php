<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoDemoController extends Controller
{
    public function index()
    {
        // Scan available videos from private storage
        $videos = collect(Storage::disk('local')->files('videos'))
            ->filter(fn($f) => in_array(pathinfo($f, PATHINFO_EXTENSION), ['mp4', 'webm']))
            ->map(fn($f) => [
                'filename' => pathinfo($f, PATHINFO_FILENAME),
                'label'    => str_replace(['-', '_'], ' ', pathinfo($f, PATHINFO_FILENAME)),
                'url'      => route('admin.video-demo.stream', pathinfo($f, PATHINFO_FILENAME)),
            ])
            ->values();

        return Inertia::render('Admin/VideoDemo/Index', [
            'videos' => $videos,
        ]);
    }

    public function stream(Request $request, string $filename): StreamedResponse
    {
        // Only allow alphanumeric filenames (enforced by route pattern too)
        abort_unless(preg_match('/^[a-zA-Z0-9_\-]+$/', $filename), 404);

        // Try mp4 first, then webm
        $found = null;
        foreach (['mp4', 'webm'] as $ext) {
            $path = storage_path("app/videos/{$filename}.{$ext}");
            if (file_exists($path)) {
                $found = ['path' => $path, 'mime' => $ext === 'mp4' ? 'video/mp4' : 'video/webm'];
                break;
            }
        }

        abort_unless($found, 404);

        $path     = $found['path'];
        $mime     = $found['mime'];
        $size     = filesize($path);
        $start    = 0;
        $end      = $size - 1;
        $status   = 200;
        $headers  = [
            'Content-Type'              => $mime,
            'Content-Disposition'       => 'inline',
            'Accept-Ranges'             => 'bytes',
            'Cache-Control'             => 'private, no-store, no-cache',
            'X-Content-Type-Options'    => 'nosniff',
        ];

        // Support HTTP range requests (needed for video seeking)
        if ($request->hasHeader('Range')) {
            [$rangeUnit, $rangeSpec] = explode('=', $request->header('Range'), 2);
            [$start, $reqEnd] = array_pad(explode('-', $rangeSpec, 2), 2, null);
            $start  = (int) $start;
            $end    = $reqEnd !== '' && $reqEnd !== null ? (int) $reqEnd : $size - 1;
            $end    = min($end, $size - 1);
            $status = 206;
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        }

        $length = $end - $start + 1;
        $headers['Content-Length'] = $length;

        return response()->stream(function () use ($path, $start, $length) {
            $fp = fopen($path, 'rb');
            fseek($fp, $start);
            $remaining = $length;
            while ($remaining > 0 && !feof($fp)) {
                $chunk = min(8192, $remaining);
                echo fread($fp, $chunk);
                $remaining -= $chunk;
                flush();
            }
            fclose($fp);
        }, $status, $headers);
    }
}
