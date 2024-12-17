<?php

namespace App\Http\Controllers\Config;

use Illuminate\Http\Request;


use App\Models\User;
use App\Models\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// periods
use App\Http\Resources\PeriodResource;
use App\Models\Period;

// turma
use App\Models\Turma;
use App\Http\Resources\TurmaResource;

// class
use App\Models\Classes;
use App\Http\Resources\ClasseResource;

//  school
use App\Http\Resources\SchoolYearResource;
use App\Models\SchoolYear;

// curses
use App\Models\Course;
use App\Http\Resources\CourseResource;

// class
use App\Http\Resources\ClassRoomResource;
use App\Models\ClassRoom;


// gerador de relatorios por turma
use PDF;

// enrollement
use App\Models\Enrollment;
use App\Http\Resources\EnrollmentResource;

// disciplines
use App\Http\Resources\DisciplineResource;
use App\Models\Discipline;


// trimestre
use App\Http\Resources\TrimestreResource;
use App\Models\Trimestre;

use TCPDF;
    

// fatura-recibo

use App\Http\Controllers\Finance\InvoiceReceiptController;
    

class ConfigController extends Controller
{   
    private $invoice;
    
    public function __construct()
    {
        $this->invoice = new InvoiceReceiptController();
    }
    
    
    public function model()
    {
        try {
            return response(
                [
                'turmas'=> TurmaResource::collection(Turma::where('company_id', Auth::user()->company_id)->get()),
                'classes'=>ClasseResource::collection( Classes::where('company_id', Auth::user()->company_id)->get()),
                'school_year'=> SchoolYearResource::collection(SchoolYear::where('company_id', Auth::user()->company_id)->get()),
                'courses'=>CourseResource::collection(Course::where('company_id',  Auth::user()->company->id)->get()),
                'company_id'=> Auth::user()->company_id,
                'disciplines'=>DisciplineResource::collection(Discipline::where('company_id',  Auth::user()->company_id)->get()),
                'classrooms'=> ClassRoomResource::collection(ClassRoom::where('company_id',  Auth::user()->company_id)->get()),
                "periods" =>  PeriodResource::collection(Period::where('company_id' ,Auth::user()->company_id)->get()),
                "professores" => User::where('company_id', Auth::user()->company_id)->whereHas('role', function($query){  $query->where('role', 'professor'); }) ->get(),
                'trimestres'=> TrimestreResource::collection(Trimestre::where('company_id', Auth::user()->company_id)->get())

                ],200
            )->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response(['turmas'=> $e->getMessage()],400)->header('Content-type', 'application/json');

        }
    }
    
    public function relatorio(Request $request){
      
        try {
                $mes = $request->mes;
                $company = Company::findOrFail($request->company);
                $turma = Turma::findOrFail($request->turma);
                $periodo = Period::findOrFail($request->period);
                $anoLetivo = SchoolYear::findOrFail($request->school_year);
                $curso = Course::findOrFail($request->course);
                $sala = ClassRoom::findOrFail($request->class_room);
                $classe = Classes::findOrFail($request->classe);
        
                $documento =($request->document);
                $page = '';
            
            // studantes
            $enrollment =  Enrollment::with(['class_room','course','period','classe','turma','student','school_year'])
                ->where('company_id', $request->company)
                ->where('status', '1')
                ->where('course_id', $request->course)
                ->where('class_room_id', $request->class_room)
                ->where('turma_id', $request->turma)
                ->where('classe_id', $request->classe)
                ->where('school_year_id', $request->school_year)
                ->where('period_id', $request->period)

                // ->groupBy('student.name')
                ->orderByDesc("id")
                ->get();
                
                
           
            switch ($request->document) {
                case 'encarregados':
                    $page = 'documents.relatorio';
                    // return $enrollment;
                     return $this->relatorioEncarregados($enrollment, $company, $documento, $turma, $anoLetivo, $classe, $periodo, $curso, $sala);
                   break;
                   
           case 'propinas':
                       foreach ($enrollment as $key => $student) { // Loop pelos estudantes
                       
                        if (count($this->invoice->propinaEstudentId($student->student_id)) < 1) { // Verifica se não há propinas para o estudante
                            $enrollment[$key]['propinas'] =  []; // Define um array vazio de propinas para o estudante
                        }else{
                            foreach ($this->invoice->propinaEstudentId($student->student_id) as $propinaKey => $propinas) { // Loop pelas propinas do estudante
                                    $enrollment[$key]['propinas'] = $propinas->invoice_receipt_itens; // Adiciona as propinas ao array do estudante
                                    foreach ($propinas->invoice_receipt_itens as $i => $propina) { // Loop pelos itens de propina
                                        $enrollment[$key]['mensalidade'] = $propina->article->price;
                                        $enrollment[$key]['propinas'][$propinaKey]['invoice'] = $propinas->invoice_number;  // Adiciona o número da fatura ao item de propina
                                        $enrollment[$key]['propinas'][$propinaKey]['designation'] = $propina->article->designation; // Adiciona a designação do artigo ao item de propina
                                        unset($enrollment[$key]['propinas'][$propinaKey]['article']); // Remove o artigo do item de propina (opcional)
                                    }
                                }
                            }
                        }
                        
                        
                        $dados = Array("empresa"=> $company, "dados"=> $enrollment);                        
                        $pdf = PDF::loadView('documents.relatorio_propinas_current', compact('dados', 'request', 'turma', 'anoLetivo', 'classe', 'periodo', 'sala'));
                        $pdf->setPaper('A4', 'landscape');
                        return $pdf->download("RelatorioPropinas".time().".pdf");
                    break;

            case 'devedores':
                
                foreach ($enrollment as $key => $student) { // Loop pelos estudantes
                    foreach ($this->invoice->propinaEstudentId($student->student_id) as $propinaKey => $propinas) { // Loop pelas propinas do estudante
                        $enrollment[$key]['propinas'] = $propinas->invoice_receipt_itens; // Adiciona as propinas ao array do estudante
                        foreach ($propinas->invoice_receipt_itens as $i => $propina) { // Loop pelos itens de propina
                                     $enrollment[$key]['mensalidade'] = $propina->article->price;
                                     $enrollment[$key]['propinas'][$propinaKey]['invoice'] = $propinas->invoice_number;  // Adiciona o número da fatura ao item de propina
                                     $enrollment[$key]['propinas'][$propinaKey]['designation'] = $propina->article->designation; // Adiciona a designação do artigo ao item de propina
                                     unset($enrollment[$key]['propinas'][$propinaKey]['article']); // Remove o artigo do item de propina (opcional)
                                 }
                             }
                             if (!$this->invoice->propinaEstudentId($student->student_id)) { // Verifica se não há propinas para o estudante
                                 $enrollment[$key]['propinas'] =  []; // Define um array vazio de propinas para o estudante
                             }
                         }


                         $page = 'documents.relatorio-propina';
                     break;
                    
            case 'estudantes':
                        return $this->relatorioaluno($enrollment, $company, $documento, $turma, $anoLetivo, $classe, $periodo, $curso, $sala);
                        $page = 'documents.relatorio';
                    break;
                    
            default:
                    return [];
                    break;
            }
            
            // $this->relactorioaluno($compact('enrollment', 'company', 'documento', 'turma', 'anoLetivo', 'classe', 'periodo'));
            
            $pdf = PDF::loadView($page,
            compact('enrollment', 'company', 'documento', 'turma', 'anoLetivo', 'classe', 'periodo', 'mes'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download("Relatorio.pdf");

            return response(
                ['enrollment'=> ($enrollment)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['enrollment'=> $th->getMessage(),"message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],404)->header('Content-type', 'application/json');
        }
    }
    
    
    // Telatorio de Estudantes
    public function relatorioaluno($enrollment = [], $company = null, $documento = null, $turma = null, $anoLetivo = null, $classe = null, $periodo = null, $curso = null, $sala = null)
    {
        $pdf = new TCPDF();
        $pdf->SetFont('times', 'B', 16);
        
        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Aluno');
        $pdf->SetSubject('Aluno');
        $pdf->AddPage();

            // . $this->baseinstituicao->getAll()[0]->logotipo
        $pdf->Image(public_path('images/logo.png'),102, 13, 15, 15, '', '', '', false, 100);


        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(198, 12, '', 0, 1);
        $pdf->Cell(198, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(198, 5, 'MINISTÉRIO DA EDUCAÇO', 0, 1, 'C');
        $pdf->Cell(198, 5, $company->designation, 0, 1, 'C');

        $pdf->Cell(198, 20, '', 0, 1);
        $pdf->SetFont('Times', 'B', 14, '', true);
        $pdf->Cell(198, 5, 'LISTA NOMINAL', 0, 1, 'C');
    
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 12, '', 0, 1);
        if (intval($classe->designation) > 9) {
            $pdf->Cell(190, 5, 'CURSO: ' . $curso->designation, 0, 1);
        }
        $pdf->Cell(190, 5, 'CLASSE: ' . $classe->designation, 0, 1);
        $pdf->Cell(190, 5, 'TURMA: ' . $turma->designation, 0, 1);
        $pdf->Cell(190, 5, 'SALA: ' . $sala->designation, 0, 1);
        $pdf->Cell(190, 5, 'PERÍODO: ' . $periodo->designation, 0, 1);
        $pdf->Cell(190, 5, 'ANO LECTIVO ' . $anoLetivo->designation, 0, 1);


        $pdf->SetFillColor(222, 222, 222);

        // End Title

        $pdf->SetY($pdf->GetY() - 28);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Ln(40);
        $pdf->Cell(189, 1, 'DADOS DOS ESTUDANTES', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 10);
        $pdf->Ln(9);
        $pdf->SetFont('Times', 'B', 10);

        $pdf->Cell(5, 5, '#', 1, 0);
        $pdf->Cell(80, 5, 'NOME COMPLETO', 1, 0);
        $pdf->Cell(55, 5, 'Nº DE IDENTIFICAÇÂO', 1, 0);
        $pdf->Cell(25, 5, 'GÉNERO', 1, 0);
        $pdf->Cell(24, 5, 'ESTADO', 1, 1);
      
        $pdf->SetFont('Times', '', 10);
        $cont = 1;
        foreach ($enrollment as $item) {
            $pdf->Cell(5, 5, $cont++, 1, 0);
            $pdf->Cell(80, 5, $item->student->name, 1, 0);
            $pdf->Cell(55, 5, strtoupper($item->student->identity), 1, 0);
            $pdf->Cell(25, 5, strtoupper(($item->student->gender  == 'M'? 'Masculino' : 'Femenino' )), 1, 0);
            $pdf->Cell(24, 5, strtoupper($item->status), 1, 1);
        }
        
        if(count($enrollment) == 0){
         $pdf->Cell(189, 5, "Sem Estudantes", 1, 0);
        }

        $pdf->SetFont('Times', '', 10);
        $pdf->Ln((251) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(190, 5, 'ASSINATURA', 0, 1, 'C');
        $pdf->Cell(190, 5, '_____________________________', 0, 1, 'C');
        $pdf->Cell(190, 10, '', 0, 1, 'C');


        $pdf->SetFont('Times', '', 10);
        $pdf->Line(19, $pdf->getY(), 193, $pdf->getY());
        $pdf->SetX(18);
        $pdf->Cell(30, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        // $pdf->Output();
        //$pdf->Output();
        $pdf->Output('ESTUDANTES'.time().'.pdf', 'D');
    }
    
    public function relatorioEncarregados($enrollment = [], $company = null, $documento = null, $turma = null, $anoLetivo = null, $classe = null, $periodo = null, $curso = null, $sala = null)
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetFont('times', 'B', 16);
        
        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Aluno');
        $pdf->SetSubject('Aluno');
        $pdf->AddPage();

            // . $this->baseinstituicao->getAll()[0]->logotipo
        // Calcular a posição X para centralizar a imagem
        $page = $pdf->getPageWidth();
        $imageWidth = 15; // Largura da imagem
        $imageX = ($page - $imageWidth) / 2;
        
        // Inserir a imagem centralizada
        $pdf->Image(public_path('images/logo.png'), $imageX, 13, 15, 15, '', '', '', false, 100);



        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(0, 10, '', 0, 1);
        $pdf->Cell(0, 10, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(0, 0, 'MINISTÉRIO DA EDUCAÇÃO', 0, 1, 'C');
        $pdf->Cell(0, 0, $company->designation, 0, 1, 'C');

        $pdf->Cell($page, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 14, '', true);
        $pdf->Cell($page-20, 5, 'LISTA DE ENCARREGADOS', 0, 1, 'C');
    
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell($page, 12, '', 0, 1);
        if (intval($classe->designation) > 9) {
            $pdf->Cell(190, 5, 'CURSO: ' . $curso->designation, 0, 1);
        }
        $pdf->Cell($page, 5, 'CLASSE: ' . $classe->designation, 0, 1);
        $pdf->Cell(190, 5, 'TURMA: ' . $turma->designation, 0, 1);
        $pdf->Cell(190, 5, 'SALA: ' . $sala->designation, 0, 1);
        $pdf->Cell(190, 5, 'PERÍODO: ' . $periodo->designation, 0, 1);
        $pdf->Cell(190, 5, 'ANO LECTIVO ' . $anoLetivo->designation, 0, 1);


        $pdf->SetFillColor(222, 222, 222);

        // End Title

        $pdf->SetY($pdf->GetY() - 28);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Ln(40);
        $pdf->Cell($page-20, 1, 'DADOS DOS ESTUDANTES', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 10);
        $pdf->Ln(9);
        $pdf->SetFont('Times', 'B', 10);

        $pdf->Cell(5, 5, '#', 1, 0);
        $pdf->Cell(60, 5, 'NOME COMPLETO', 1, 0);
        $pdf->Cell(46, 5, 'Nº DE IDENTIFICAÇÂO', 1, 0);
        $pdf->Cell(55, 5, 'NOME PAI', 1, 0);
        $pdf->Cell(55, 5, 'NOME MÃE', 1, 0);
        $pdf->Cell(28, 5, 'GÉNERO', 1, 0);
        $pdf->Cell(28, 5, 'ESTADO', 1, 1);
      
        $pdf->SetFont('Times', '', 10);
        $cont = 1;
        foreach ($enrollment as $item) {
            $pdf->Cell(5, 5, $cont++, 1, 0);
            $pdf->Cell(60, 5, $item->student->name, 1, 0);
            $pdf->Cell(46, 5, strtoupper($item->student->identity), 1, 0);
            $pdf->Cell(55, 5, strtoupper($item->student->father_name), 1, 0);
            $pdf->Cell(55, 5, strtoupper($item->student->mother_name), 1, 0);
            $pdf->Cell(28, 5, strtoupper(($item->student->gender  == 'M'? 'Masculino' : 'Femenino' )), 1, 0);
            $pdf->Cell(28, 5, strtoupper($item->status), 1, 1);
        }
        
        if(count($enrollment) == 0){
         $pdf->Cell($page-20, 5, "Sem Estudantes", 1, 0);
        }

        $pdf->SetFont('Times', '', 10);
        $pdf->Ln((251) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell($page-20, 5, 'ASSINATURA', 0, 1, 'C');
        $pdf->Cell($page-20, 5, '_____________________________', 0, 1, 'C');
        $pdf->Cell($page-20, 10, '', 0, 1, 'C');


        $pdf->SetFont('Times', '', 10);
        $pdf->Line(19, $pdf->getY(), $page-20, $pdf->getY());
        $pdf->SetX(18);
        $pdf->Cell(30, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        // $pdf->Output();
        $pdf->Output('ENCARREGADOS'.time().'.pdf', 'D');
    }
    
    
    
    private function propinaEstudents($list){
        return $list;
    }

    
}
