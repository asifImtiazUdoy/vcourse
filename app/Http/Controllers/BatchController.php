<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Http\Requests\StoreBatchRequest;
use App\Http\Requests\UpdateBatchRequest;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasPermissionTo('batch.list')) {
            try {
                $batches = Batch::with('course')->get();

                return response()->json([
                    'status' => true,
                    'batches' => $batches
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
     * @param  \App\Http\Requests\StoreBatchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createBatch(StoreBatchRequest $request)
    {
        if (Auth::user()->hasPermissionTo('batch.create')) {
            try {
                $coursename = Course::find($request->course_id)->name;
                $batch = new Batch;
                $batch->name = $coursename . ' ( Batch: ' . $request->number . ')';
                $batch->course_id  = $request->course_id;
                $batch->number = $request->number;
                $batch->status = '1';
                $batch->last_ennrollment_date = $request->last_ennrollment_date;
                $batch->class_starting_date = $request->class_starting_date;
                $batch->max_seat = $request->max_seat;
                $batch->enrolled_students = 0;
                $batch->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Batch Created Successfully'
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
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function getBatch(Batch $batch)
    {
        if (Auth::user()->hasPermissionTo('batch.get')) {
            try {
                $batch = Batch::where('id',$batch->id)->with('students','lessons')->first();
                return response()->json([
                    'status' => true,
                    'batch' => $batch
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
     * @param  \App\Http\Requests\UpdateBatchRequest  $request
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function updateBatch(UpdateBatchRequest $request, Batch $batch)
    {
        if (Auth::user()->hasPermissionTo('batch.update')) {
            try {
                $batch->number = $request->number;
                $batch->course_id = $request->course_id;
                $batch->status = $request->status ?: '1';
                $batch->max_seat = $request->max_seat;
                $batch->last_ennrollment_date = $request->last_ennrollment_date;
                $batch->class_starting_date = $request->class_starting_date;        
                $batch->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Batch Updated Successfully'
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
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        if (Auth::user()->hasPermissionTo('batch.delete')) {
            try {
                $batch->delete();

                return response()->json([
                    'status' => true,
                    'message' => "Batch Deleted Successfully"
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
