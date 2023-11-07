<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $images = $data['images'];
        unset($data['images']);
        $post = Post::firstOrCreate($data);
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
        return response([]);
    }
}
