<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class VideoController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'video' => 'required|file|mimetypes:video/mp4',
        ]);
        $video = new Video;
        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('videos', ['disk' =>      'my_files']);
            $video->video = $path;
        }
        $video->save();

        return response()->json([
            'message' => 'Succesful',
            'video' => new VideoResource($video)
        ]);
    }
}
