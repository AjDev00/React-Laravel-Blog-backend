<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{

    //This will show all blogs.
    public function index(){
        $blogs = Blog::orderBy("created_at")->get();

        return response()->json([
            'status' => true,
            'data'=> $blogs
        ]);
    }


    //This will display the blog with id, 24.
    public function showFirst(){
        $firstBlog = Cache::remember('first_blog', 30, function(){
            return Blog::find(24);
        });

        return response()->json([
            'status' => true,
            'data'=> $firstBlog
        ]);
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

        //save image.
        $tempImage = TempImage::find($request->image_id);

        if($tempImage != null){
            $imageExtArray = explode('.', $tempImage->name); //seperates the image name and it's extension.
            $ext = last($imageExtArray); //save the extension in a variable.
            $imageName = time().'-'.$blog->id.'.'.$ext; //create a unique name for the image with the time function followed by the blog id and the previously saved extension.

            $blog->image = $imageName;
            $blog->save();

            //move image from a temporary directory to a permanent directory with the new image name.
            $sourcePath = public_path('uploads/temp/'.$tempImage->name);
            $destinationPath = public_path('uploads/blogs/'.$imageName);

            File::copy($sourcePath, $destinationPath); //copies the image from the source path to the destination path.
        }
        
        return response()->json([
           'status' => true,
           'message' => 'Blog added successfully',
           'data' => $blog 
        ]);
    }

    //This will show a single blog.
    public function show($id){
        $blog = Blog::find($id);

        if($blog === null){
            return response()->json([
                'status' => false,
                'message' => 'Blog Not Found!'
            ]);
        }

        //better date format.
        $blog['date'] = \Carbon\Carbon::parse($blog->created_at)->format('d M, Y');

        return response()->json([
            'status' => true,
            'data' => $blog
        ]);
    }

    //This will edit a single blog.
    public function update($id, Request $request){
        //get a certain blog with the id.
        $blog = Blog::find($id);

        //returns this if the blog is not found.
        if($blog === null){
            return response()->json([
                'status' => false,
                'message' => 'Blog Not Found!'
            ]);
        }

        //validate required field while updating.
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
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->shortDesc = $request->shortDesc;
        $blog->author = $request->author;
        $blog->save();

        //save image.
        $tempImage = TempImage::find($request->image_id);

        if($tempImage != null){
            $imageExtArray = explode('.', $tempImage->name); //seperates the image name and it's extension.
            $ext = last($imageExtArray); //save the extension in a variable.
            $imageName = time().'-'.$blog->id.'.'.$ext; //create a unique name for the image with the time function followed by the blog id and the previously saved extension.

            $blog->image = $imageName;
            $blog->save();

            //move image from a temporary directory to a permanent directory with the new image name.
            $sourcePath = public_path('uploads/temp/'.$tempImage->name);
            $destinationPath = public_path('uploads/blogs/'.$imageName);

            File::copy($sourcePath, $destinationPath); //copies the image from the source path to the destination path.
        }
        
        //return success message.
        return response()->json([
           'status' => true,
           'message' => 'Blog updated successfully',
           'data' => $blog 
        ]);

    }

    //This will delete a blog.
    public function destroy(){

    }
}
