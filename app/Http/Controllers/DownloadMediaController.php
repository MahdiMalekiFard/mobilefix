<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadMediaController extends Controller
{
    public function __invoke(Media $media): StreamedResponse|RedirectResponse
    {
        // (Optional) Ensure the current user is allowed to access this media
        // $this->authorize('view', $media);

        $disk = Storage::disk($media->disk);
        $path = method_exists($media, 'getPathRelativeToRoot')
            ? $media->getPathRelativeToRoot()   // Spatie v10
            : ltrim($media->getPath(), '/');    // older versions (absolute path for local)
        $filename = $media->file_name ?: ('file.' . ($media->extension ?? 'bin'));

        // S3 / cloud: redirect to signed URL that FORCES DOWNLOAD
        if (Str::startsWith($media->disk, 's3')) {
            $url = $disk->temporaryUrl($path, now()->addMinutes(5), [
                'ResponseContentDisposition' => 'attachment; filename="' . $filename . '"',
                'ResponseContentType'        => $media->mime_type ?: 'application/octet-stream',
            ]);

            return redirect()->away($url);
        }

        // Local (or any non-cloud driver): stream the file as an attachment
        // Use a stream to avoid loading the file into memory.
        return response()->streamDownload(
            function () use ($disk, $path) {
                $stream = $disk->readStream($path);
                while ( ! feof($stream)) {
                    echo fread($stream, 8192);
                }
                fclose($stream);
            },
            $filename,
            [
                'Content-Type'           => $media->mime_type ?: 'application/octet-stream',
                'Content-Disposition'    => 'attachment; filename="' . $filename . '"',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }
}
