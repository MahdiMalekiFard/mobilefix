<?php

declare(strict_types=1);

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
    public function makePoster(
        string $videoPath,
        string $saveToPath,
        int $seconds = 1,
        int $targetWidth = 1280,
        int $targetHeight = 720,
        string $fit = 'pad' // 'pad' | 'crop'
    ): string {
        // Ensure directory exists
        $dir = dirname($saveToPath);
        if ( ! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        // Validate video
        $ffprobe = FFProbe::create(['ffprobe.binaries' => $this->ffprobeBin]);
        $ffprobe->streams($videoPath)->videos()->first(); // throws if invalid

        // Extract raw frame to a temp file
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => $this->ffmpegBin,
            'ffprobe.binaries' => $this->ffprobeBin,
            'timeout'          => 3600,
            'threads'          => 2,
        ]);

        $video   = $ffmpeg->open($videoPath);
        $rawPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('poster_raw_', true) . '.jpg';
        $video->frame(TimeCode::fromSeconds($seconds))->save($rawPath);

        // ---- Resize with GD ----
        $src = @imagecreatefromjpeg($rawPath);
        if ( ! $src) {
            @unlink($rawPath);

            throw new RuntimeException("GD could not open poster: {$rawPath}");
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);
        $dst  = imagecreatetruecolor($targetWidth, $targetHeight);

        // Always fill destination with black first (background)
        $black = imagecolorallocate($dst, 0, 0, 0);
        imagefill($dst, 0, 0, $black);

        if ($fit === 'crop') {
            // Cover: crop source to target aspect, then scale -> no black bars
            $targetAR = $targetWidth / $targetHeight;
            $srcAR    = $srcW / $srcH;

            if ($srcAR > $targetAR) {
                // Source wider: crop left/right
                $cropW = (int) round($srcH * $targetAR);
                $cropH = $srcH;
                $srcX  = (int) floor(($srcW - $cropW) / 2);
                $srcY  = 0;
            } else {
                // Source taller: crop top/bottom
                $cropW = $srcW;
                $cropH = (int) round($srcW / $targetAR);
                $srcX  = 0;
                $srcY  = (int) floor(($srcH - $cropH) / 2);
            }

            imagecopyresampled(
                $dst,
                $src,
                0,
                0,                // dst x,y
                $srcX,
                $srcY,        // src x,y (cropped region start)
                $targetWidth,
                $targetHeight, // dst size
                $cropW,
                $cropH       // src cropped region size
            );
        } else { // 'pad' (letterbox)
            // Fit inside, keep all, letterbox with black
            $ratio     = min($targetWidth / $srcW, $targetHeight / $srcH);
            $newW      = (int) round($srcW * $ratio);
            $newH      = (int) round($srcH * $ratio);
            $dstX      = (int) floor(($targetWidth - $newW) / 2);
            $dstY      = (int) floor(($targetHeight - $newH) / 2);

            imagecopyresampled(
                $dst,
                $src,
                $dstX,
                $dstY,        // dst x,y (centered)
                0,
                0,                // src x,y
                $newW,
                $newH,        // dst size
                $srcW,
                $srcH         // src size
            );
        }

        // Save final JPG
        imagejpeg($dst, $saveToPath, 85);

        // Cleanup
        imagedestroy($src);
        imagedestroy($dst);
        @unlink($rawPath);

        return $saveToPath;
    }
}
