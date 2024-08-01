<?php

namespace App\Http\Controllers;
use App\Models\Student;
 
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('students.index');
    }

    public function fetchStudents()
    {
        $students = Student::all();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $student = Student::create($request->all());
        return response()->json(['success' => 'Student added successfully.', 'student' => $student]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->all());
        return response()->json(['success' => 'Student updated successfully.']);
    }

    public function destroy($id)
    {
        Student::destroy($id);
        return response()->json(['success' => 'Student deleted successfully.']);
    }
}
