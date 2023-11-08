<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Post $post)
    {
        $data = $request->validated();
        $images = isset($data['images']) ? $data['images'] : null;
        $imageIdsForDelete = isset($data['image_ids_for_delete']) ? $data['image_ids_for_delete'] : null;
        $imageUrlsForDelete = isset($data['image_urls_for_delete']) ? $data['image_urls_for_delete'] : null;
        unset($data['images'], $data['image_ids_for_delete'], $data['image_urls_for_delete']);
        $post->update($data);
        $currentImages = $post->images;
        if ($imageIdsForDelete) {
            foreach ($currentImages as $currentImage) {
                if (in_array($currentImage->id, $imageIdsForDelete)) {
                    Storage::disk('public')->delete($currentImage->path);
                    Storage::disk('public')->delete(str_replace('images/', 'images/prev_', $currentImage->path));
                    $currentImage->delete();
                }
            }
        }
        if ($imageUrlsForDelete) {
            foreach ($imageUrlsForDelete as $imageUrlForDelete) {
                if (in_array($currentImage->url, $imageUrlsForDelete)) {
                    $path = str_replace($request->root() . '/storage/', '', $imageUrlsForDelete);
                    Storage::disk('public')->delete($path);
                }
            }
        }
        if ($images) {
            foreach ($images as $image) {
                $name = md5(Carbon::now() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                $filePath = Storage::disk('public')->putFileAs('/images', $image, $name);
                $previreImage = 'prev_' . $name;
                Image::create([
                    'path' => $filePath,
                    'url' => url('/storage/' . $filePath),
                    'post_id' => $post->id,
                    'preview_url' => url('/storage/images/' . $previreImage),
                ]);
                InterventionImage::make($image)->fit(100, 100)->save(storage_path('app/public/images/' . $previreImage));
            }
        }
        return response([]);
    }
}
