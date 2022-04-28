<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MarksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @created by adarshthavarool@gmail.com on (28 April 2022 at 9:18 PM)
     */
    public function index()
    {
        $marks = Mark::with('student:id,name,teacher_id', 'student.teacher.user:id,name')->get();
        $data = [];

        foreach ($marks as $mark) {
            $id = $mark->id;
            $total = $mark->maths + $mark->science + $mark->history;
            $evaluator = User::where('id', $mark->evaluator_id)->pluck('name');
            $reporting_teacher = User::where('id', $mark->student->teacher_id)->pluck('name');
            $createdOn=Carbon::parse($mark->created_at)->format('M d Y H:i A');
            array_push($data, ["marklist_id" => $mark->id,
                "name" => $mark->student->name,
                "maths" => $mark->maths,
                "science" => $mark->science,
                "history" => $mark->history,
                "term" => $mark->term,
                "total_mark" => $total,
                "evaluator" => $evaluator,
                "created_on" => $createdOn,
                "reporting_teacher" => $reporting_teacher]);
        }
        return datatables($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @created by adarshthavarool@gmail.com on (28 April 2022 at 9:31 PM)
     */
    public function store(Request $request)
    {
        if (!$request->marklist_id) {
            $newMark = Mark::create($request->all());
            if ($newMark) {
                return response()->json(['message' => 'Score Added Successfully', 'status' => 201]);
            } else {
                return response()->json(['message' => $request, 'status' => 202]);
            }
        } else {
            $mark = Mark::find($request->marklist_id);
            $updated = $mark->update($request->all());
            if ($updated) {
                return response()->json(['message' => 'Score Updated Successfully', 'status' => 201]);
            } else {
                return response()->json(['message' => $request, 'status' => 202]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @created by adarshthavarool@gmail.com on (28 April 2022 at 9:53 PM)
     */
    public function destroy($id)
    {
        $mark = Mark::findOrFail($id);

        if ($mark->delete()) {
            return response()->json(['message' => 'Score Deleted Successfully', 'status' => 201]);
        } else {
            return response()->json(['message' => "Someting Went Wrong", 'status' => 202]);
        }
    }
}
