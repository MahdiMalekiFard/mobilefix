<?php

namespace App\Services;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use RuntimeException;

class VideoPosterService
{
    public function __construct(
        private ?string $ffmpegBin  = null,
        private ?string $ffprobeBin = null,
    ) {
        // If you didn’t add to PATH, pass absolute exe paths via .env or here.
        // Example defaults for Windows:
        $this->ffmpegBin ??= env('FFMPEG_BIN',  'C:\ffmpeg\bin\ffmpeg.exe');
        $this->ffprobeBin ??= env('FFPROBE_BIN', 'C:\ffmpeg\bin\ffprobe.exe');
    }

    /**
     * Extracts a poster frame at $seconds and saves as a JPG at $saveToPath.
     * Returns the absolute path of the saved poster.
     */
    public function makePoster(string $videoPath, string $saveToPath, int $seconds = 1): string
    {
        // Make sure directory exists (Windows-safe)
        $dir = dirname($saveToPath);
        if ( ! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        // Probe to ensure file/codecs are valid
        $ffprobe = FFProbe::create([
            'ffprobe.binaries' => $this->ffprobeBin,
        ]);
        $ffprobe->streams($videoPath)->videos()->first(); // throws if invalid

        // Capture frame with ffmpeg
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => $this->ffmpegBin,
            'ffprobe.binaries' => $this->ffprobeBin,
            'timeout'          => 3600,
            'threads'          => 2,
        ]);

        $video = $ffmpeg->open($videoPath);
        $frame = $video->frame(TimeCode::fromSeconds($seconds));
        $frame->save($saveToPath);

        // ✅ Resize with GD
        $targetWidth  = 1280;
        $targetHeight = 720;

        $src = imagecreatefromjpeg($saveToPath); // frame is saved as jpg
        if ( ! $src) {
            throw new RuntimeException("GD could not open poster: {$saveToPath}");
        }

        $srcWidth  = imagesx($src);
        $srcHeight = imagesy($src);

        // Create a new true color image with target dimensions
        $dst = imagecreatetruecolor($targetWidth, $targetHeight);

        // Fill with black background (to pad if aspect ratio doesn’t match)
        $black = imagecolorallocate($dst, 0, 0, 0);
        imagefill($dst, 0, 0, $black);

        // Calculate resize while keeping aspect ratio
        $ratio      = min($targetWidth / $srcWidth, $targetHeight / $srcHeight);
        $newWidth   = ($srcWidth * $ratio);
        $newHeight  = ($srcHeight * $ratio);
        $dstX       = (int) (($targetWidth - $newWidth) / 2);
        $dstY       = (int) (($targetHeight - $newHeight) / 2);

        // Resample
        imagecopyresampled($dst, $src, $dstX, $dstY, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

        // Save resized image back to file
        imagejpeg($dst, $saveToPath, 85);

        // Free memory
        imagedestroy($src);
        imagedestroy($dst);

        return $saveToPath;
    }
}
