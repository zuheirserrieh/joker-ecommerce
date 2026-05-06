<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UploadController extends Controller
{
    public function storeProductImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'],
        ]);

        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file('image'))->scaleDown(width: 1200, height: 1200)->toJpeg(85);
        $path = 'products/'.uniqid('', true).'.jpg';

        Storage::disk('public')->put($path, (string) $image);

        return response()->json([
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
        ], 201);
    }
}
