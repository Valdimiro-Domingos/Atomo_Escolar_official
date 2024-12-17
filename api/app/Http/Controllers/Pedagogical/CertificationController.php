<?php

namespace App\Http\Controllers\Pedagogical;

// dates
use App\Http\Controllers\Config\ConfigController;
use App\Http\Controllers\Controller;
use App\Http\Resources\EnrollmentItemResource;
use App\Models\Certification;
use App\Models\Enrollment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificationController extends Controller
{
    protected $colletion;

    public function __construct()
    {
        $this->colletion = new ConfigController();
    }

    public function index()
    {

        try {
            $students = EnrollmentItemResource::collection(Enrollment::with('student')->join('students', 'enrollments.student_id', '=', 'students.id')
                    ->where('enrollments.status', '1')
                    ->where('enrollments.company_id', Auth::user()->company_id)
                    ->orderBy('students.name', 'asc')
                    ->get(['enrollments.*', 'students.name']));

            return response()->json(
                [
                    "students" => ($students),
                    "items" => ($this->colletion->model()->original),
                ], 200);

        } catch (Exception $th) {
            return response(['students' => $th->getMessage(), "message" => "Problema Interno"], 500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function uplode(Request $request, $id)
    {
       try {
        $certification = Certification::where('student_id', $id)->first();
        
        if (!$certification) {
            $certification = new Certification();
            $certification->student_id = $id;
            $certification->company_id = Auth::user()->company_id;
            $certification->save();
        }
        
        if ($request->hasFile('file')) {
            // Removendo o arquivo anterior, se existir
            if ($certification->file) {
                $logoPath = public_path('secretary/shedule') . '/' . $certification->file;
                if (file_exists($logoPath)) {
                    unlink($logoPath);
                }
            }
            // Salvando o novo arquivo
            $file = $request->file('file');
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        
            $fileName = time() . '.' . $extension;
            $file->move(public_path('secretary/shedule/'), $fileName);
            $certification->update(['file' => $fileName]);
            return $this->index();
        }
        
        return response()->json(['message' => 'Nenhum arquivo foi enviado.'], 400);
        
    } catch (\Throwable $th) {
        //throw $th;
        return response()->json(['message' => 'Pedimos que tente novamente.'], 400);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(Certification $certification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certification $certification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certification $certification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certification $certification)
    {
        //
    }
}
