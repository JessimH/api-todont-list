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
            return response()->json(["message" => "Not Found"]);
        }

        if($post->user->id != $request->user()->id) {
            return response()->json(["message"=>"Forbidden"], 403);
        }

        return response()->json($post);

    }

    public function destroy($id)
    {
        $postToDelete = Post::where('id', $id)->get();

        if(count($postToDelete)>0){
            if($postToDelete[0]->id === auth()->user()->id){
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
