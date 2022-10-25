<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasPermissionTo('course.list')) {
            try {
                if (request('type') == 'pending') {
                    $courses = Course::where('status', 'Pending')->get();
                }
                $courses = Course::orderBy('id', 'DESC')->get();

                return response()->json([
                    'status' => true,
                    'courses' => $courses
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createCourse(StoreCourseRequest $request)
    {
        if (Auth::user()->hasPermissionTo('course.create')) {
            try {
                $course = new Course;
                $course->name = $request->name;
                if ($request->file('coursethumb')) {

                    $thumbnail = $request->file('coursethumb');
                    $image_full_name = time() . '_' . str_replace([" ", "."], ["_", "a"], $course->name) . $course->id . '.' . $thumbnail->getClientOriginalExtension();
                    $upload_path = 'images/frontimages/courses/';
                    $image_url = $upload_path . $image_full_name;
                    $success = $thumbnail->move($upload_path, $image_full_name);
                    $course->thumbnail = $image_url;
                }

                $course->user_id = Auth::user()->id;

                $course->category_id = $request->category_id;
                if (Auth::user()->hasRole('superadmin')) {
                    $course->status = 'Approved';
                }else{
                    $course->status = 'Pending';
                }
                $course->type = $request->type;
                $course->time_duration = $request->time_duration;
                $course->media_link = $request->media_link;
                $course->rating_number = '1';
                $course->rating_quantity = '5';
                $course->number_of_lessons = '1';
                $course->old_price = $request->price;
                $course->discount = $request->discount;
                if ($discount = $request->discount) {
                    // discounted_price = original_price - (original_price * discount / 100)
                    $original_price = $request->price;
                    $discount = $request->discount;
                    $discounted_price = $original_price - ($original_price * $discount / 100);
                    $course->price = $discounted_price;
                } else {
                    $course->price = $request->price;
                }
                $course->timing = $request->timing;
                $course->venu = $request->venu;
                $course->description = $request->description;
                $course->requirments = $request->requirments;
                $course->forwho = $request->forwho;
                $course->what_will_learn = $request->what_will_learn;
                $course->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Course Created Successfully'
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function getCourse(Course $course)
    {
        if (Auth::user()->hasPermissionTo('course.get')) {
            try {
                return response()->json([
                    'status' => true,
                    'course' => $course
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function approved(Course $course)
    {
        if (Auth::user()->hasPermissionTo('course.list')) {
            try {
                $course->status = 'Approved';
                $course->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Course Approved'
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCourseRequest  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function updateCourse(UpdateCourseRequest $request, Course $course)
    {
        if (Auth::user()->hasPermissionTo('course.update')) {
            try {
                if ($request->name != $course->name) {
                    $request->validate(['name' => 'required|unique:courses,name']);
                }
                if ($request->name) {
                    $course->name = $request->name;
                }
                if ($request->user_id) {
                    $course->user_id = $request->user_id;
                }

                if ($request->file('thumbnail')) {

                    if (File::exists($course->thumbnail)) {
                        File::delete($course->thumbnail);
                    }
                    $thumbnail = $request->file('thumbnail');
                    $image_full_name = time() . '_' . str_replace([" ", ".", "/"], ["_", "a", "i"], $course->name) . $course->id . '.' . $thumbnail->getClientOriginalExtension();
                    $upload_path = 'images/frontimages/courses/';
                    $image_url = $upload_path . $image_full_name;
                    $success = $thumbnail->move($upload_path, $image_full_name);
                    $course->thumbnail = $image_url;
                }



                // $course->category_id = $request->category;
                // $course->status = $request->status;
                // $course->time_duration = $request->time_duration;
                // $course->media_link = $request->media_link;

                if ($request->price) {
                    $discount = $request->discount;
                    $course->discount = $discount;
                    $course->old_price = $request->price;

                    // discounted_price = original_price - (original_price * discount / 100)
                    $discounted_price = $request->price - ($request->price * $discount / 100);
                    $course->price = $discounted_price;
                }

                // $course->timing = $request->timing;
                // $course->venu = $request->venu;

                if ($request->description) {
                    $course->description = $request->description;
                }
                if ($request->requirments) {
                    $course->requirments = $request->requirments;
                }
                if ($request->forwho) {
                    $course->forwho = $request->forwho;
                }
                if ($request->what_will_learn) {
                    $course->what_will_learn = $request->what_will_learn;
                }

                $course->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Course Updated Successfully'
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        if (Auth::user()->hasPermissionTo('course.delete')) {
            try {
                $course->delete();

                return response()->json([
                    'status' => true,
                    'message' => "Course Deleted Successfully"
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
