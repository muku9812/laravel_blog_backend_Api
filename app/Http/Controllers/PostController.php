<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        return response([
            'posts'=>Post::orderBy('created_at','desc')->with('user:id,name,image')->withCount('comments','likes')
                ->with('likes',function ($likes){
                    return $likes->where('user_id',auth()->user()->id)->select('id','user_id','post_id')->get();})->get()

        ],200);
    }
    public function show($id){
      return  response([
          'post'=>Post::where('id',$id)->withCount('comments','likes')->get()
      ],200);
    }
    public function store(Request $request){
        $attrs=$request->validate([
            'body'=>'required|String'
        ]);
        $image=$this->saveImage($request->image,'posts');
        $post=Post::create([
            'body'=>$attrs['body'],
            'user_id'=>auth()->user()->id,
            'image'=>$image,
        ]);
        return response([
            'message'=>'Post Created',
            'post'=>$post
        ],200);
    }
    public function update(Request $request,$id){
        $post=Post::find($id);
        if (!$post){
            return response([
                'message'=>'Post not found'
            ],403);
        }
        if ($post->user_id != auth()->user()->id){
            return response([
                'message'=>'permission denied.'
            ],403);
        }

        $attrs=$request->validate([
            'body'=>'required|String'
        ]);

       $post->update([
           'body'=>$attrs['body']
       ]);
        return response([
            'message'=>'Post updated',
            'post'=>$post
        ],200);
    }

    public function destroy($id){
        $post=Post::find($id);
        if (!$post){
            return response([
                'message'=>'Post not found'
            ],403);
        }
        if ($post->user_id != auth()->user()->id){
            return response([
                'message'=>'permission denied.'
            ],403);
        }
        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();
        return response([
            'message'=>'Post deleted',
            'post'=>$post
        ],200);


    }
}
