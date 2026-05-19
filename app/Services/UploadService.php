<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class UploadService
{
    /**
     * Store an image, compress it to max 1600px wide at quality 80, save as JPG.
     * Returns the storage path (relative to the public disk).
     */
    public function storeImage(UploadedFile $file, string $folder): string
    {
        $folder = trim($folder, '/');
        $disk = Storage::disk('public');
        $disk->makeDirectory($folder);

        $filename = $folder.'/'.Str::uuid().'.jpg';
        $absolutePath = $disk->path($filename);

        try {
            $image = Image::read($file->getPathname());
            if ($image->width() > 1600) {
                $image->scaleDown(width: 1600);
            }
            $image->toJpeg(quality: 80)->save($absolutePath);
        } catch (\Throwable $e) {
            // Fallback: store original if compression fails
            $disk->putFileAs($folder, $file, basename($filename));
        }

        return $filename;
    }

    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }
        if (str_contains($path, 'placeholder')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }
}
