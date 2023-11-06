<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function __invoke()
    {
        dump(request()->all());
        return response()->json(['message' => 'ok']);
    }
}
