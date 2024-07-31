<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{

    //This will show all blogs.
    public function index(){

    }

    //This will insert all blogs.
    public function store(Request $request){
        //making required fields required.
        $validate = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'author' => 'required|min:3'
        ]);

        //if validation fails return status false and error message.
        if($validate->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'error' => $validate->errors()
            ]);
        }

        //else insert into DB.
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->shortDesc = $request->shortDesc;
        $blog->author = $request->author;
        $blog->save();
        
        return response()->json([
           'status' => true,
           'message' => 'Blog added successfully',
           'data' => $blog 
        ]);
    }

    //This will show a single blog.
    public function show(){

    }

    //This will edit a single blog.
    public function update(){

    }

    //This will delete a blog.
    public function destroy(){

    }
}
