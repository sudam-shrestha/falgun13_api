<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return CourseResource::collection($courses);
    }

    public function store(Request $request)
    {
        $course = new Course();
        $course->name = $request->name;
        $course->price = $request->price;
        $course->description = $request->description;
        $file = $request->image;
        if ($file) {
            $file_name = time() . "." . $file->getClientOriginalExtension();  //images/hero.jpg
            $file->move("images/", $file_name);
            $course->image = "images/$file_name";
        }
        $course->save();

        return response()->json([
            "success" => true,
            "message" => "Course created successfully!"
        ]);
    }


    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                "success" => false,
                "message" => "Course not found"
            ]);
        }
        $course->name = $request->name;
        $course->price = $request->price;
        $course->description = $request->description;
        $file = $request->image;
        if ($file) {
            $file_name = time() . "." . $file->getClientOriginalExtension();  //images/hero.jpg
            $file->move("images/", $file_name);
            $course->image = "images/$file_name";
        }
        $course->save();

        return response()->json([
            "success" => true,
            "message" => "Course updated successfully!"
        ]);
    }

    public function delete($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                "success" => false,
                "message" => "Course not found"
            ]);
        }
        $course->delete();
        return response()->json([
            "success" => true,
            "message" => "Course deleted successfully!"
        ]);
    }
}
