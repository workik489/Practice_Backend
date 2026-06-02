<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessImage;
use Illuminate\Http\Request;

class ProcessImageController extends Controller
{
    public function upload()
    {
        for ($i = 1; $i <= 100; $i++) {
            ProcessImage::dispatch("image_$i.jpg")->onQueue('high');
        }

        return response()->json([
            'message' => '100 jobs queued'
        ]);
    }
}
