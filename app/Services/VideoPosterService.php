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

        return $saveToPath;
    }
}
