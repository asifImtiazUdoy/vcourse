<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasPermissionTo('lesson.list')) {
            try {
                $lessons = Lesson::with('course')->get();

                return response()->json([
                    'status' => true,
                    'lessones' => $lessons
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
     * @param  \App\Http\Requests\StoreLessonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createLesson(StoreLessonRequest $request)
    {
        if (Auth::user()->hasPermissionTo('lesson.create')) {
            try {
                $coursename = Course::find($request->course_id)->name;
                $lesson = new Lesson;
                $lesson->name = $coursename . ' ( Lesson: ' . $request->number . ')';
                $lesson->course_id  = $request->course_id;
                $lesson->number = $request->number;
                $lesson->status = '1';
                $lesson->last_ennrollment_date = $request->last_ennrollment_date;
                $lesson->class_starting_date = $request->class_starting_date;
                $lesson->max_seat = $request->max_seat;
                $lesson->enrolled_students = 0;
                $lesson->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Lesson Created Successfully'
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
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function getLesson(Lesson $lesson)
    {
        if (Auth::user()->hasPermissionTo('lesson.get')) {
            try {
                $lesson = Lesson::where('id',$lesson->id)->with('students','lessons')->first();
                return response()->json([
                    'status' => true,
                    'lesson' => $lesson
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
     * @param  \App\Http\Requests\UpdateLessonRequest  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function updateLesson(UpdateLessonRequest $request, Lesson $lesson)
    {
        if (Auth::user()->hasPermissionTo('lesson.update')) {
            try {
                $lesson->number = $request->number;
                $lesson->course_id = $request->course_id;
                $lesson->status = $request->status ?: '1';
                $lesson->max_seat = $request->max_seat;
                $lesson->last_ennrollment_date = $request->last_ennrollment_date;
                $lesson->class_starting_date = $request->class_starting_date;        
                $lesson->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Lesson Updated Successfully'
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
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        if (Auth::user()->hasPermissionTo('lesson.delete')) {
            try {
                $lesson->delete();

                return response()->json([
                    'status' => true,
                    'message' => "Lesson Deleted Successfully"
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
