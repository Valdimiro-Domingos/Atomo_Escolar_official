<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Http\Resources\MiniScheduleResource;
use App\Http\Resources\ScheduleResource;
use App\Models\{
    Discipline,
    Grade,
    Turma,
    Course,
    Classes,
    ClassRoom,
    Period,
    MiniSchedule,
    Schedule,
    Student,
    Trimestre,
    User,
};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Config\ConfigController;







class MiniScheduleController extends Controller
{
    protected $colletion;
    private $pautaId;
    private $miniId;

    public function __construct() {
      $this->colletion = new ConfigController();
    }
    
    public function index(){
        try{  
        
            $mini_schedule = MiniSchedule::where('company_id', Auth::user()->company_id)->get();
            return response(['mini_schedules'=>MiniScheduleResource::collection($mini_schedule), ],200)->header('Content-Type', 'application/json');

        }catch(Exception $e){
            return response(['mini_schedules'=> $e->getMessage(),
             "message"=>"Problema Interno"],500)->header('Content-type', 'application/json');
        }
    }
    
    
    public function view(){
        return response()->json([
        'mini_schedules'=>MiniScheduleResource::collection(MiniSchedule::where('company_id', Auth::user()->company_id)->get()), 
        "items"=>['professores'=>$this->colletion->model()->original['professores'], 
        'trimestres'=>$this->colletion->model()->original['trimestres'], 
        'disciplines'=> $this->colletion->model()->original['disciplines']]], 200);
    }
    
    
    
    public function store(Request $request)
    { 
        
         
         
        if($request->input('id')){
            return $this->update($request, $request->input('id'));
        }
        
         DB::beginTransaction();
        try {
            $professorId =(($request->input('profeessor_id')));
            $trimestreId = (($request->input('trimestre_id')));
            $disciplineId = Discipline::findOrfail($request->input('discipline_id'));
            
            
            // caso existir
            if($this->validationMiniPauta($request)){
                return response()->json(["message" => "Já existe esta Mini-Pauta"], 500);
            }else{
                // caso nao 
                $miniPautaWhere = new MiniSchedule();
                $miniPautaWhere->designation = "MP-".$disciplineId->designation;
                $miniPautaWhere->profeessor_id = $professorId;
                $miniPautaWhere->schedule_id = $request->schedule_id;
                $miniPautaWhere->discipline_id = $disciplineId->id;
                $miniPautaWhere->trimestre_id = $trimestreId;
                $miniPautaWhere->company_id = Auth::user()->company_id;
                $miniPautaWhere->save();
            }
            
               if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName(); // Adicionando um separador para evitar conflitos de nomes de arquivos
                $file->move(public_path('secretary/shedule/'), $fileName); // Corrigido o nome da pasta para 'schedule'
            
                // Supondo que $miniPautaWhere seja uma instância de um modelo Eloquent
                $miniPautaWhere->update(['file' => $fileName]);
            }

              
            
            DB::commit();
            
            return $this->all_mini_schedule_of_schedule($request->schedule_id);
        } catch (Exception $th) {
            DB::rollBack();
            return response(['mini_schedules'=> $th->getMessage(), 'line'=>$th->getLine(), "message"=>"Problema Interno"],500)->header('Content-type', 'application/json');

        }
    }
    
    /*Validacao Mini PAUTA */
    
    private function validationMiniPauta($request){
            $professorId =(($request->input('profeessor_id')));
            $trimestreId = (($request->input('trimestre_id')));
            $disciplineId = ($request->input('discipline_id'));
            $scheduleId = (intval($request->input('schedule_id')));
      
           
            // consulta da mini-pauta
            $miniPautaWhere = MiniSchedule::where('trimestre_id', $trimestreId)
                ->where('discipline_id', $disciplineId)
                ->where('schedule_id', $scheduleId)
                ->where('profeessor_id', $professorId)
                ->where('company_id', Auth::user()->company_id)
                ->first();
                // ->where(function ($query) use ($professorId) {
                //     $query->where('profeessor_id', $professorId)
                //           ->orWhere('profeessor_id', '<>', $professorId);
                // })
                
            // caso existir
            if(($miniPautaWhere)){
                return true;
            }else{
                return false;
            }
    }
   
    
    

    public function show(string $id)
    {
        try {
            $mini_schedules = MiniSchedule::findOrFail(intval($id));
        return response(
            ['schedule'=>MiniScheduleResource::make($mini_schedules)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['mini_schedules'=> $th->getMessage(), "message"=>"Mini pauta não encontrada!"],500)->header('Content-type', 'application/json');
        }
    }
    public function all_mini_schedule_of_schedule( $id)
    {
        try {
            $mini_schedules = MiniSchedule::where('company_id', Auth::user()->company_id)
            // ->where('schedule_id',$id)
            ->get();

            if(count($mini_schedules) == 0){
                return response()->json([
                    'mini_schedules'=>[],
                    "items"=>['professores'=>$this->colletion->model()->original['professores'], 
                    'trimestres'=>$this->colletion->model()->original['trimestres'], 
                    'disciplines'=>  $this->colletion->model()->original['disciplines']]], 200);
            }

        return response()->json(
            ['mini_schedules'=>MiniScheduleResource::collection($mini_schedules), 
                "items"=>['professores'=>$this->colletion->model()->original['professores'], 
                'trimestres'=>$this->colletion->model()->original['trimestres'], 
                'disciplines'=> $this->colletion->model()->original['disciplines']]
            ],200
        );
        } catch (Exception $th) {
            return response([ "message"=>"Mini pauta não encontrada!"],500)->header('Content-type', 'application/json');
        }
    }
    
    
    public function updateFile(Request $request, $id)
    {   
        $schedule = MiniSchedule::findOrFail(intval($id));

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

            return $this->all_mini_schedule_of_schedule($schedule->schedule_id);

        }
        return response()->json(['message' => 'Nenhum arquivo foi enviado.'], 400);
    }


    public function update(Request $request, $id) {
   
    
        try {
            // Buscando o schedule pelo ID
            $schedule = MiniSchedule::findOrFail(intval($id));
            
            // Atualizando os campos do schedule
            $schedule->update([
                "designation" => "MP-" . Discipline::findOrFail($request->discipline_id)->designation,
                'trimestre_id' => $request->input('trimestre_id'),
                'profeessor_id' => $request->input('profeessor_id'),
                'discipline_id' => $request->input('discipline_id'),
                'status' => '1',
            ]);
            
          
    
            // Retornando todos os mini schedules do schedule
            return $this->all_mini_schedule_of_schedule($schedule->schedule_id);
    
        } catch (Exception $th) {
            return response([
                'mini_schedules' => $th->getMessage(),
                'line' => $th->getLine(),
                "message" => "Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."
            ], 500)->header('Content-type', 'application/json');
        }
    }

        /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            
             if(Grade::where('company_id', Auth::user()->company_id)->where('mini_schedule_id', $id)->count() > 0){
                return response()->json(["message" => "Não podemos eliminar este registro, obrigado!"], 500);
            }
        
            $minis_schedule = MiniSchedule::where('company_id', Auth::user()->company_id)->findOrFail($id);
            $minis_schedule->delete();
            return $this->view()->original;

        } catch (Exception $th) {
            if($th->getCode() == 23000){
                return response(['minis_schedule'=>$th->getMessage(),  "message"=>"Não é possível excluir esta Mini pauta devido a registros associados. Por favor, remova ou atualize esses registros antes de tentar excluir a classe novamente."],500)->header('Content-type', 'application/json');
            }else{
                return response(['minis_schedule'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
            }
        }
    }
}

