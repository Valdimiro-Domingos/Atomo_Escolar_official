<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Models\Classes;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Discipline;
use App\Models\Period;
use App\Models\Schedule;
use App\Models\SchoolYear;
use App\Models\MiniSchedule;
use App\Models\Turma;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Config\ConfigController;


class ScheduleController extends Controller
{

    protected $colletion;
    private $pautaId;
    private $miniId;

    public function __construct() {
      $this->colletion = new ConfigController();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $schedules = Schedule::where('company_id',  '=',Auth::user()->company->id)->get();
        return response(
            ['schedules'=>ScheduleResource::collection($schedules)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
                       return response(['schedules'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');

        }
    }

    public function view(){
        return response([
            'schedules'=>ScheduleResource::collection(
                Schedule::where('company_id', Auth::user()->company_id)->orderByDesc('id')->get()),
                "items"=>$this->colletion->model()->original],200)
                ->header('Content-Type', 'application/json');
    }
    
    public function updateFile(Request $request, $id)
    {   
        $schedule = Schedule::findOrFail(intval($id));

        if ($request->hasFile('file')) {
            
                // Removendo o arquivo anterior, se existir
                if ($schedule->file) {
                    $logoPath = public_path('secretary/shedule') . '/' . $schedule->file;
                    if (file_exists($logoPath)) {
                        unlink($logoPath);
                    }
                }
            
                // Salvando o novo arquivo
                $file = $request->file('file');
                $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

                $fileName = time() . '.'.$extension;
                $file->move(public_path('secretary/shedule/'), $fileName);
                $schedule->update(['file' => $fileName]);

            return $this->view();

        }
        return response()->json(['message' => 'Nenhum arquivo foi enviado.'], 400);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $school_year = (intval($request->input('school_year_id')));
            $period = (intval($request->input('period_id')));
            $classe = (intval($request->input('classe_id')));
            $class_room = (intval($request->input('class_room_id')));
            $turma = (intval($request->input('turma_id')));
            $course = (intval($request->input('course_id')));
            
           
            $count = Schedule::where('company_id', Auth::user()->company_id)
            ->where('school_year_id', $school_year)
            ->where('period_id', $period)
            ->where('classe_id', $classe)
            ->where('class_room_id', $class_room)
            ->where('turma_id', $turma)
            ->where('course_id', $course)
            ->count();

            if($count > 0){
                return response()->json(['message'=>'Existe pauta com esses dados'], 500);
            }else{
                Schedule::create([
                    'designation' => "Pauta ".SchoolYear::where('company_id', Auth::user()->company_id)->findOrFail($school_year)->designation,
                    'description' => $request->input('description'),
                    'school_year_id' => $school_year,
                    'course_id' => $course,
                    'turma_id' => $turma,
                    'class_room_id' => $class_room,
                    'classe_id' => $classe,
                    'period_id' => $period,
                    'status'=> '1',
                    'company_id' => Auth::user()->company_id,
                ]);
            }

            return response(['schedules'=>ScheduleResource::collection(Schedule::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
                       return response(['schedules'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $schedule = Schedule::findOrFail(intval($id));
        return response(
            ['schedule'=>ScheduleResource::make($schedule)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['schedules'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->update($request->all());
            $schedule->update(["designation" => "Pauta ".SchoolYear::where('company_id', Auth::user()->company_id)->findOrFail($request->school_year_id)->designation]);
            return response(['schedules'=>ScheduleResource::collection(Schedule::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['schedules'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
        }
    }


    public function destroy(string $id)
    {
        try {
            
            if(MiniSchedule::where('company_id', Auth::user()->company_id)->where('schedule_id', $id)->count() > 0){
                            return response()->json(["message" => "Não podemos eliminar este registro, obrigado!"], 500);
            }
            
            $schedule = Schedule::where('company_id', Auth::user()->company_id)->findOrFail($id);
            $schedule->delete();
            return response(['schedules'=>ScheduleResource::collection(Schedule::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            if($th->getCode() == 23000){
                return response(['classe'=>$th->getMessage(), "message"=>"Não é possível excluir esta classe devido a registros associados. Por favor, remova ou atualize esses registros antes de tentar excluir a classe novamente."],500)->header('Content-type', 'application/json');
            }else{
                return response(['classe'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
            }
        }
    }
}
