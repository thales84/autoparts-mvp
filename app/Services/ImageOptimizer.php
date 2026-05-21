<?php

namespace App\Services;

class ImageOptimizer
{
    private const MAX_WIDTH  = 1200;
    private const MAX_HEIGHT = 1200;
    private const QUALITY    = 82;

    /**
     * Optimise une image uploadée et la sauvegarde en WebP.
     * Retourne le chemin relatif depuis public/.
     */
    public function optimizeUpload(string $sourcePath, string $destDir): string
    {
        $filename = bin2hex(random_bytes(8)) . '.webp';
        $destPath = rtrim($destDir, '/') . '/' . $filename;

        $image = $this->createFromFile($sourcePath);

        if ($image === null) {
            // GD ne peut pas lire le fichier → copie brute, pas d'optimisation
            $fallback = bin2hex(random_bytes(8)) . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
            copy($sourcePath, rtrim($destDir, '/') . '/' . $fallback);
            return $fallback;
        }

        $image = $this->resize($image);

        imagewebp($image, $destPath, self::QUALITY);
        imagedestroy($image);

        return $filename;
    }

    private function createFromFile(string $path): ?\GdImage
    {
        $mime = mime_content_type($path);

        return match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path) ?: null,
            'image/png'  => @imagecreatefrompng($path)  ?: null,
            'image/webp' => @imagecreatefromwebp($path) ?: null,
            default      => null,
        };
    }

    private function resize(\GdImage $image): \GdImage
    {
        $w = imagesx($image);
        $h = imagesy($image);

        if ($w <= self::MAX_WIDTH && $h <= self::MAX_HEIGHT) {
            return $image;
        }

        $ratio  = min(self::MAX_WIDTH / $w, self::MAX_HEIGHT / $h);
        $newW   = (int) round($w * $ratio);
        $newH   = (int) round($h * $ratio);

        $resized = imagecreatetruecolor($newW, $newH);

        // Préserver transparence PNG
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $w, $h);
        imagedestroy($image);

        return $resized;
    }
}
