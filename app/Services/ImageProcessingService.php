<?php

namespace App\Services;

use Imagick;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageProcessingService
{
    private string $tempFolder;
    private int $maxDimension = 2048;
    private int $thumbnailSize = 300;

    public function __construct()
    {
        $this->tempFolder = public_path('temp/');
        $this->ensureTempFolder();
    }

    /**
     * Main function to process image
     * @param string $sourcePath Local or remote file path
     * @param array $options Options: ['quality'=>80,'formats'=>['jpeg','webp'],'sizes'=>['thumb','medium','large']]
     * @return array Processed image paths
     */
    public function processImage(string $sourcePath, array $options = []): array
    {
        $quality = $options['quality'] ?? 80;
        $formats = $options['formats'] ?? ['jpeg'];
        $sizes = $options['sizes'] ?? ['thumb', 'medium', 'large', 'original'];

        $results = [];

        // Convert HEIC if needed
        if ($this->isHeic($sourcePath)) {
            $sourcePath = $this->convertHeicToJpg($sourcePath);
        }

        $localPath = $this->getLocalImage($sourcePath);
        if (!$localPath) return [];

        foreach ($sizes as $size) {
            foreach ($formats as $format) {
                $results[$size][$format] = $this->generateImageVersion($localPath, $size, $format, $quality);
            }
        }

        return $results;
    }

    /**
     * Generate image version (resize + format + compress)
     */
    private function generateImageVersion(string $path, string $size, string $format, int $quality): string
    {
        $img = new Imagick($path);

        // Orientation
        $img->autoOrient();

        // Resize
        [$width, $height] = $this->getResizeDimensions($img, $size);
        if ($width && $height) {
            $img->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
        }

        // Strip metadata
        $img->stripImage();

        // Compression
        $img->setImageCompression(Imagick::COMPRESSION_JPEG);
        $img->setImageCompressionQuality($quality);

        // Set format
        $img->setImageFormat($format);

        // Generate temp file name
        $outPath = $this->tempFolder . Str::random(12) . "_{$size}." . $format;
        $img->writeImage($outPath);

        $img->clear();
        $this->deleteTempFile($path);

        return $outPath;
    }

    /**
     * Calculate new dimensions based on requested size
     */
    private function getResizeDimensions(Imagick $img, string $size): array
    {
        $width = $img->getImageWidth();
        $height = $img->getImageHeight();
        $ratio = $width / $height;

        switch ($size) {
            case 'thumb':
                $max = $this->thumbnailSize;
                if ($width > $height) {
                    return [$max, intval($max / $ratio)];
                } else {
                    return [intval($max * $ratio), $max];
                }
            case 'medium':
                $max = $this->maxDimension / 2;
                if ($width > $height) {
                    return [$max, intval($max / $ratio)];
                } else {
                    return [intval($max * $ratio), $max];
                }
            case 'large':
                $max = $this->maxDimension;
                if ($width > $height) {
                    return [$max, intval($max / $ratio)];
                } else {
                    return [intval($max * $ratio), $max];
                }
            case 'original':
            default:
                return [$width, $height];
        }
    }

    /**
     * Ensure temp folder exists
     */
    private function ensureTempFolder()
    {
        if (!is_dir($this->tempFolder)) {
            mkdir($this->tempFolder, 0777, true);
        }
    }

    /**
     * Check if HEIC
     */
    private function isHeic(string $path): bool
    {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['heic', 'heif']);
    }

    /**
     * Convert HEIC to JPG
     */
    private function convertHeicToJpg(string $path): string
    {
        $jpgPath = preg_replace('/\.(heic|heif)$/i', '.jpg', $path);
        $img = new Imagick($path);
        $img->setImageFormat('jpeg');
        $img->writeImage($jpgPath);
        $img->clear();
        return $jpgPath;
    }

    /**
     * Download remote image if needed
     */
    private function getLocalImage(string $path): ?string
    {
        if (str_starts_with($path, 'http')) {
            return $this->downloadRemote($path);
        }

        return file_exists($path) ? $path : null;
    }

    private function downloadRemote(string $url): ?string
    {
        $target = $this->tempFolder . Str::random(12) . ".jpg";
        $ch = curl_init($url);
        $fp = fopen($target, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        return file_exists($target) ? $target : null;
    }

    /**
     * Optional: move processed images to storage
     */
    public function saveToStorage(string $filePath, string $folder = 'uploads'): string
    {
        $filename = basename($filePath);
        $storagePath = $folder . '/' . $filename;

        Storage::disk('public')->put($storagePath, file_get_contents($filePath));

        return $storagePath;
    }

    private function deleteTempFile(string $filePath): void
    {
        if (file_exists($filePath) && str_starts_with($filePath, $this->tempFolder)) {
            @unlink($filePath);
        }
    }
}
