<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $postsUser = auth()->user()->posts()->orderBy('created_at')->get();
        return response()->json([
            'posts'=>$postsUser
        ], 201);
    }

    public function store(Request $request)
    {
        if(!$request->user()->tokenCan('posts:write')) {
            return response()->json(['message'=> 'You dont have the ability to do that.'], 401);
        }

        $request->validate([
            'body' => 'required',
        ]);

        $post = new Post();
        $post->body = $request['body'];
        $post->user_id = auth()->user()->id;
        $post->save();

        return response()->json(['message'=>'Created'], 201);
    }

    public function show(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response()->json(["message" => "Not Found"], 404);
        }

        if($post->user->id != $request->user()->id) {
            return response()->json(["message"=>"Accès à la tâche non autorisé"], 403);
        }

        return response()->json($post, 200);

    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response()->json(["message" => "Not Found"], 404);
        }

        if($post->user->id != $request->user()->id) {
            return response()->json(["message"=>"Accès à la tâche non autorisé"], 403);
        }

        $request->validate([
            'body' => 'required',
            'done' => 'required',
        ]);
        $post = Post::where('id', $id)->first();
        $post->body = $request->body;
        $post->done = $request->done;
        $post->save();
       
        return response()->json(['message'=>'OK'], 200);
    }

    public function destroy($id, Request $request)
    {
        $postToDelete = Post::where('id', $id)->get();

        if(count($postToDelete)>0){
            // dd($postToDelete[0]->id === $request->user()->id);
            if($postToDelete[0]->user_id === $request->user()->id){
                Post::where('id', $id )->delete();
                return response()->json(['message'=>'OK'], 200);
            }
            else{
                return response()->json(["message"=>"Accès à la tâche non autorisé"], 403);
            } 
        }
        return response()->json(['error'=>'La tâche n\'existe pas'], 404);
    }
}
