<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        // return Hash::make($request->password);
        $validator = Validator::make(
            $request->all(),
            [
                "course_id" => "required|exists:courses,id",
                "remark" => "nullable|max:255"
            ],
            [
                "course_id.exists" => "Provided course donot exists."
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ]);
        }

        Enrollment::create([
            "user_id" => Auth::user()->id,
            "course_id" => $request->course_id,
            "remark" => $request->remark
        ]);

        return response()->json([
            "success" => true,
            "message" => "Enrollment successfull."
        ]);
    }


    public function index()
    {
        $enrollments = Enrollment::all();
        return EnrollmentResource::collection($enrollments);
    }
}
