<?php

namespace App\Support;

class ImageWatermarker
{
    // Stamps a semi-transparent bar + text across the bottom of the image at $path (on the given
    // storage disk) and overwrites it in place. Works on jpg/png/webp. Safe to call more than once —
    // each call draws on top of whatever is currently there, so don't watermark an already-watermarked
    // file if you want to avoid a doubled-up bar (the admin controller only calls this once, right
    // after a fresh upload).
    public static function stamp(string $absolutePath, string $text): void
    {
        if (! file_exists($absolutePath)) {
            return;
        }

        $info = @getimagesize($absolutePath);
        if (! $info) {
            return; // not a readable image — skip silently rather than break the upload
        }

        $mime = $info['mime'];

        $image = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($absolutePath),
            'image/png' => imagecreatefrompng($absolutePath),
            'image/webp' => imagecreatefromwebp($absolutePath),
            default => null,
        };

        if (! $image) {
            return;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Bar height scales with the image, with sensible min/max so it looks right on both
        // a small thumbnail-ish upload and a large DSLR photo.
        $barHeight = (int) max(32, min(70, $height * 0.09));

        // Semi-transparent black bar along the bottom edge.
        $barColor = imagecolorallocatealpha($image, 0, 0, 0, 55); // 0 = opaque, 127 = fully transparent
        imagefilledrectangle($image, 0, $height - $barHeight, $width, $height, $barColor);

        // White text, centered in the bar.
        $fontPath = resource_path('fonts/Roboto-Bold.ttf');
        $fontSize = (int) max(10, min(22, $barHeight * 0.38));
        $white = imagecolorallocate($image, 255, 255, 255);

        if (file_exists($fontPath)) {
            $box = imagettfbbox($fontSize, 0, $fontPath, $text);
            $textWidth = abs($box[4] - $box[0]);
            $x = (int) (($width - $textWidth) / 2);
            $y = (int) ($height - ($barHeight / 2) + ($fontSize / 3));
            imagettftext($image, $fontSize, 0, max(10, $x), $y, $white, $fontPath, $text);
        } else {
            // Fallback if the font file is missing for some reason — built-in GD font, no TTF needed.
            $x = (int) (($width - (imagefontwidth(5) * strlen($text))) / 2);
            $y = (int) ($height - $barHeight + ($barHeight - imagefontheight(5)) / 2);
            imagestring($image, 5, max(5, $x), $y, $text, $white);
        }

        match ($mime) {
            'image/jpeg' => imagejpeg($image, $absolutePath, 88),
            'image/png' => imagepng($image, $absolutePath),
            'image/webp' => imagewebp($image, $absolutePath, 88),
            default => null,
        };

        imagedestroy($image);
    }
}
