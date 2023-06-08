<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebcamController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required',
        ]);
        $image = $request->image;
        $name = uniqid().'.jpg';
        $extension = '.jpg';

        $image_parts = explode(';base64,', $image);

        $image_base64 = base64_decode($image_parts[1]);

        $location = "shots/'".$name;

        file_put_contents($location, $image_base64); //save as file

        \App\Models\Screenshot::create([
            'name' => $name,
            'image' => 'data:image/'.$extension.';base64,'.base64_encode($image_base64),
        ]);

        return 'success!';
    }
}
