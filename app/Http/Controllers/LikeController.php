<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use http\Env\Response;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    //like or unlike
    public  function likeOrUnlike($id){
        $post=Post::find($id);
        if(!$post){
            return response([
                'message'=>'page not found.'
            ],403);
        }
        $like= $post->likes()->where('user_id',auth()->user()->id)->first();

        if(!$like){
            Like::create([
                'post_id'=>$id,
                'user_id'=>auth()->user()->id
            ]);
            return response([
                'message'=>'Liked'
            ],200);
        }
        $like->delete();
        return  response([
            'message'=>'Disliked'
        ],200);
    }
}
