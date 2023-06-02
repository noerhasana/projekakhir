<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadTempImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required',
            'images.*' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $paths = [];
        $images = $request->file('images');

        if ($images) {
            foreach ($images as $image) {
                $paths[] = $image->store('public/temp');
            }
        }

        return response()->json($paths);
    }
}
