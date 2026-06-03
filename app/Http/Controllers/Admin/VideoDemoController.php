<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class VideoDemoController extends Controller
{
    private static array $SUPPORTED_EXTENSIONS = ['mp4', 'webm', 'mov'];

    private static array $MIME_MAP = [
        'mp4'  => 'video/mp4',
        'webm' => 'video/webm',
        'mov'  => 'video/quicktime',
    ];

    public function index()
    {
        $dir    = storage_path('app/videos');
        $videos = collect();

        if (is_dir($dir)) {
            $videos = collect(scandir($dir))
                ->filter(fn($f) => in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), self::$SUPPORTED_EXTENSIONS))
                ->map(fn($f) => [
                    'filename' => pathinfo($f, PATHINFO_FILENAME),
                    'label'    => str_replace(['-', '_'], ' ', pathinfo($f, PATHINFO_FILENAME)),
                    'url'      => route('admin.video-demo.stream', pathinfo($f, PATHINFO_FILENAME), false),
                    'mime'     => self::$MIME_MAP[strtolower(pathinfo($f, PATHINFO_EXTENSION))] ?? 'video/mp4',
                ])
                ->values();
        }

        return Inertia::render('Admin/VideoDemo/Index', [
            'videos' => $videos,
        ]);
    }

    public function stream(Request $request, string $filename)
    {
        abort_unless(preg_match('/^[a-zA-Z0-9_\-]+$/', $filename), 404);

        $found = null;
        foreach (array_keys(self::$MIME_MAP) as $ext) {
            $path = storage_path("app/videos/{$filename}.{$ext}");
            if (file_exists($path)) {
                $found = ['path' => $path, 'mime' => self::$MIME_MAP[$ext]];
                break;
            }
        }

        abort_unless($found, 404);

        $path = $found['path'];
        $mime = $found['mime'];
        $size = filesize($path);

        $start  = 0;
        $end    = $size - 1;
        $status = 200;

        $headers = [
            'Content-Type'           => $mime,
            'Content-Disposition'    => 'inline',
            'Accept-Ranges'          => 'bytes',
            'Cache-Control'          => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($request->hasHeader('Range')) {
            preg_match('/bytes=(\d+)-(\d*)/', $request->header('Range'), $m);
            $start  = (int) $m[1];
            $end    = isset($m[2]) && $m[2] !== '' ? (int) $m[2] : $size - 1;
            $status = 206;
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        }

        $length = $end - $start + 1;
        $headers['Content-Length'] = $length;

        // Close session before streaming to prevent session-lock blocking concurrent requests
        session()->save();

        return response()->stream(function () use ($path, $start, $length) {
            $fp = fopen($path, 'rb');
            fseek($fp, $start);
            $remaining = $length;
            while (!feof($fp) && $remaining > 0) {
                $chunk = (int) min(65536, $remaining);
                echo fread($fp, $chunk);
                $remaining -= $chunk;
                flush();
            }
            fclose($fp);
        }, $status, $headers);
    }
}
