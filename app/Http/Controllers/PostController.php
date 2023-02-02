<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\PaginationTrait;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use PaginationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Set params defaults
        $search = $request->searchParam ? $request->searchParam : 'tag';
        $sortBy = $request->sortBy ? $request->sortBy : 'created_at';
        $sortDesc = $request->sortDesc ? 'desc' : 'asc';

        $posts = Post::where($search,'LIKE',"%".  $request->search . "%")->orderBy($sortBy,$sortDesc)->paginate($request->limit);

        return response()->json([
            'meta' => $this->meta($posts),
            'post' => PostResource::collection($posts)
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'tag' => 'required|array',
            'illness' => 'required',
            'subject'=>'required',
            'file' => 'mimes:jpg,jpeg,png,gif,webp,csv,psd,svg,pdf|max:50000',
        ]);

        if($validator->fails()){
            return response()->json([
                'validationMessages' => $validator->errors()
            ], 400);
        }
        try {
            // dosyanın adı ve uzantısını al
        $client_original_name = $request->file->getClientOriginalName();

            // dosyanın uzantısını al
        $client_original_extension = $request->file->getClientOriginalExtension();

            // dosyanın tipini al
        $mime_type = $request->file->getMimeType();

            // dosyanın uzantısını adından çıkar
        $filename_without_extension = Str::before($client_original_name, '.' .$client_original_extension);

            // dosya adının tüm harflerini küçült
            // boşlukları sil ve tire ekle
        $filename_without_extension = Str::slug(Str::lower($filename_without_extension));

            // dosyanın başına timestamp ekle
        $filename_without_extension = time(). '-' .$filename_without_extension;

            // dosyaya uzantısını ekle
        $new_filename = $filename_without_extension. '.' .$client_original_extension;

            //dosyanın path'i
        $path = Storage::disk('public')-> putFileAs('uploads', $request->file, $new_filename);

        $post = new Post();

        $post->filename = $new_filename;

        $post->filepath = $path;

        $post->mime_type= $mime_type;

        $post->title=$request->title;

        $post->description=$request->description;

        $post->tag=$request->tag;

        $post->illness=$request->illness;

        $post->subject=$request->subject;

        $post->save();

        if(!$post){
            return response()->json([
                'message' => 'Error: Try Again !'
            ]);
        }

        return response()->json([
            'message' => 'Succesful',
            'post' => new PostResource($post)
        ]);
    }
    catch (\Exception $e) {
        dd($e);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return response()->json([
            'post' => new PostResource($post)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'tag' => 'required',
            'illness' => 'required',
            'subject'=>'required',
            'file' => ''
        ]);

        if($validator->fails()){
            return response()->json([
                'validationMessages' => $validator->errors()
            ], 400);
        }

        $title=$request->title;
        $description=$request->description;
        $tag=$request->tag;
        $illness=$request->illness;
        $subject=$request->subject;

        $result = $post->update([
            "title"=>$title,
            "description"=>$description,
            "tag"=> $tag,
            "illness"=> $illness,
            "subject"=> $subject,
        ]);

        if(!$result){
            return response()->json([
                'message' => 'Error: Try Again !'
            ]);
        }
        return response()->json([
            'message' => 'Succesful',
            'post' => new PostResource($post)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'message' => 'Deleted'
        ]);
    }
}
