<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use function PHPUnit\Framework\returnArgument;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @created by adarshthavarool@gmail.com on (28 April 2022 at 7:38 PM)
     */
    public function index()
    {

        $students = Student::with('teacher.user:id,name')->get();
        $data = [];
        foreach ($students as $student) {
            array_push($data, ["id" => $student->id,
                "name" => $student->name,
                "age" => $student->age,
                "gender" => $student->gender,
                "reporting_teacher" => $student->teacher->user->name]);
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
     * @created by adarshthavarool@gmail.com on (28 April 2022 at 7:45 PM)
     */
    public function store(StudentRequest $request)
    {

        if (!$request->student_id) {
            $newStudent = Student::create($request->all());
            if ($newStudent) {
                return response()->json(['message' => 'Student Added Successfully', 'status' => 201]);
            } else {
                return response()->json(['message' => $request, 'status' => 202]);
            }
        } else {
            $student = Student::find($request->student_id);
            $updated = $student->update($request->all());
            if ($updated) {
                return response()->json(['message' => 'Student Updated Successfully', 'status' => 201]);
            } else {
                return response()->json(['message' => $request, 'status' => 422]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Student $student
     * @return \Illuminate\Http\Response
     */
//    public function show(Student $student)
    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student', 'student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Student $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Student $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Student $student
     * @return \Illuminate\Http\Response
     * @created by adarshthavarool@gmail.com on (28 April 2022 at 8:30 PM)
     */
    public function destroy(Student $student)
    {
        $student = Student::with('mark')->findOrFail($student->id);
        // Deletes the related mark list raw before deleting student.
        $student->mark()->delete();
        $student->delete();
            return response()->json(['message' => 'Student Deleted Successfully', 'status' => 201]);
    }

    public function teachersData()
    {
        $teachers = Teacher::with('user', 'user:id,name')->get()->toArray();
        return $teachers;
    }

    public function studentsData(Request $request)
    {
        if ($request->process == 'add') {
            $markedStudents = Mark::pluck('student_id')->all();
            $markedStudents = \App\Models\Student::whereNotIn('id', $markedStudents)->get();
            return $markedStudents;
        } else {
            $markedStudents = \App\Models\Student::all();
            return $markedStudents;
        }

    }
}
