<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{

    public function index(){
      /**add a pagination, so when you go to the next page and refresh, still in the next page
       * we don'y use the plugin, comment it
       */
      $posts = auth()->user()->posts()->paginate(5);
      //$posts = Post::all(); /**it isn't food as any people can view all posts */
      return view('admin.posts.index',['posts'=>$posts]);
    }

    //instead of send the id, will send the post itsef, change it also in route

    public function show(Post $post){
        
      //  Post::findOrFail($id);
        return view('blog-post',['post'=>$post]);
    }

    public function create(){
      $this->authorize('create',Post::class); 
        
        //  Post::findOrFail($id);
          return view('admin.posts.create');
      }

      public function store(){

        $this->authorize('create',Post::class); 

        /**we could also pass a request parameter
         * dd($request->input('post_image'));
         * dd($request->post_image->orginalName);
         */
        //display data, _token attribute which we take, is an indecation for submitting the form via post method(in web.php)
        //we get this attr. as we add @csrf, and id defined as meta in layouts->app.php
        //dd(request()->all());


        //Auth::user get the user or
        //auth()->user();


        /**validation for not empty inputs */

        $inputs = request()->validate([
          'title' => 'required|min:8|max:255',
          'body' => 'required',
          'post_image' =>'file',
              //'mimes:jpeg,png',

        ]);
          //store the image on a folder, request here is a helper function
        if(request('post_image')){
          $inputs['post_image'] = request('post_image')->store('images');
        }
        $user = auth()->user();
        $user->posts()->create($inputs);
        //return back(); //means: go back to the same page where you create a page!!
        session()->flash('success','Post with title was created:' .$inputs['title']);
        return redirect()->route('post.index');

      }

      //we want to store the photo in the bubli not in the storage(see the filesystem.php)
      //then add a line at the end of .env file
      //through the command write:php artisanstorage:link
      //co create a symbolic link


      /**delete */
      public function destroy(Post $post){

        $this->authorize('view',$post); 
        //if(auth()->user()->id !== $post->user_id)
        /**better soln: make a miidleware which prevent the auth. user from doing somethings */
        $post->delete();

        /**FLASH MESSAGES */
        Session::flash('message','Post was deleted');

        /**ANOTHER WAT FOR FLASH 
         * PASS PARAM REQUEST
        */
        //$request->session()->flash('message','Post was deleted');
        return back();
      }

      /**edit */

      public function edit(Post $post){
          /**don't see edit page, only for the owners */
          $this->authorize('view',$post); //see another way
          //if(auth()->user()->can('view',$post))
        return view('admin.posts.edit',['post'=>$post]);
      }

    /**update */
    public function update(Post $post){
      $inputs = request()->validate([
        'title' => 'required|min:8|max:255',
        'body' => 'required',
        'post_image' =>'file',
            //'mimes:jpeg,png',

      ]);
        //store the image on a folder, request here is a helper function
      if(request('post_image')){
        $inputs['post_image'] = request('post_image')->store('images');
          $post->post_image = $inputs['post_image'];
      }
        $post->title = $inputs['title'];
        $post->body = $inputs['body'];

        /**the user update its posts only 
         * we add this here after add its poicies in the poilcy folder
        */
        $this->authorize('update',$post);  //willprevent al users including the owner, must include a model of which one to prevent it from
        
        $post->save();

        session()->flash('post-updated-message','The post was updated');

        return redirect()->route('post.index');
      
    }
}
