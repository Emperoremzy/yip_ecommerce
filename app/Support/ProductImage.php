<?php

namespace App\Support;

class ProductImage
{
    /** Stable Picsum image IDs — real photographs, HTTPS-friendly URLs. */
    private const FALLBACK_IDS = [668, 21, 48, 180, 250, 325, 399, 512];

    /** Default width/height for list + detail layouts. */
    private const WIDTH = 900;

    private const HEIGHT = 675;

    /** Return stored URL if valid, otherwise a deterministic fallback photo URL. */
    public static function forProduct(?string $stored, int $productId): string
    {
        $trimmed = trim((string) $stored);
        if ($trimmed !== '') {
            return $trimmed;
        }

        return self::picsumId(self::fallbackIdFor($productId, 0), self::WIDTH, self::HEIGHT);
    }

    /** Extra gallery image (distinct from primary when `$offset` differs). */
    public static function galleryVariant(int $productId, int $offset): string
    {
        return self::picsumId(self::fallbackIdFor($productId, max(1, $offset)), 400, 300);
    }

    private static function fallbackIdFor(int $productId, int $offset): int
    {
        return self::FALLBACK_IDS[($productId + $offset) % count(self::FALLBACK_IDS)];
    }

    private static function picsumId(int $picsumNumericId, int $w, int $h): string
    {
        return sprintf('https://picsum.photos/id/%d/%d/%d', $picsumNumericId, $w, $h);
    }
}
