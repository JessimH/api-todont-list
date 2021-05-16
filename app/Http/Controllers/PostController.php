<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {

        $postsUser = auth()->user()->posts()->get();
        return response()->json([
//            'posts'=>Post::get()
            'posts'=>$postsUser
        ]);

    }
    public function store(Request $request)
    {

        if(!$request->user()->tokenCan('posts:write')) {
            return response()->json(['message'=> 'You dont have the ability to do that.']);
        }

        return response()->json(['message'=>'Created'], 201);
    }

    public function show(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response()->json(["message" => "Not Found"]);
        }

        if($post->user->id != $request->user()->id) {
            return response()->json(["message"=>"Forbidden"], 403);
        }

        return response()->json($post);

    }
}
