<?php

namespace App\Http\Controllers\Config\Reports;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;

// periods

// turma
use App\Http\Resources\EnrollmentResource;
use App\Http\Resources\GradeScheduleResource;

// class
use App\Http\Resources\MiniScheduleResource;

//  school
use App\Http\Resources\ScheduleResource;

// curses

// class
use App\Models\Company;

// gerador de relatorios por turma
use App\Models\Enrollment;

// company
use App\Models\Grade;

// enrollement
use App\Models\InvoiceReceipt;
use App\Models\InvoiceReceiptItens;
use App\Models\MiniSchedule;

// disciplines
use App\Models\Schedule;

// trimestre

// faturacao
use App\Models\Turma;

// pauta
use Illuminate\Http\Request;
use PDF;
use TCPDF;

class RelatorioController extends Controller
{

    public function matricula(Request $request, $id)
    {
        $enrollment = Enrollment::with('student')->findOrFail(intval($id));

        $dados = [
            'dados' => EnrollmentResource::make($enrollment),
            'student' => $enrollment->student,
            'empresa' => Company::findOrFail(intval($enrollment->company_id)),
            'bancos' => "",
        ];

        $pdf = PDF::loadView('documents.matricula', compact('dados'));
        $pdf->setPaper('A4', 'portrait');
        #download
        return $pdf->download('matrícula-' . time() . '.pdf');
    }

    public function fatura_recibo(Request $request, $id)
    {

        try {
            $invoice_receipt = InvoiceReceipt::with('user', 'student')->findOrFail(intval($id));
            $enrollment = Enrollment::with(['class_room', 'course', 'period', 'classe', 'turma', 'student', 'school_year'])->findOrFail(intval($invoice_receipt->student->enrollmentOne->id));

            $nome_arquivo = $invoice_receipt->invoice_number . '.pdf';

            $dados = [
                'dados' => ($invoice_receipt),
                'empresa' => Company::findOrFail(intval($invoice_receipt->company_id)),
                'item' => InvoiceReceiptItens::with('article', 'article.article_category')->where('invoice_receipt_id', $invoice_receipt->id)->get(),
                'bancos' => "",
                "enrollement" => EnrollmentResource::make($enrollment),
                "arquivo" => $nome_arquivo,
            ];

            $pdf = PDF::loadView('documents.factura.factura-recibo', compact('dados'));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream($nome_arquivo);

        } catch (Exception $th) {
            return response([], 500)->header('Content-type', 'application/json');
        }
    }

    //return redirect('https://escolar.atomotecnologias.com/page');
    public function documentsSecretary(Request $request)
    {
        $type = $request->documento;
        $query = Enrollment::with('student')
            ->join('students', 'enrollments.student_id', '=', 'students.id')->distinct('enrollments.student_id')
            ->where('enrollments.company_id', $request->company)
            ->whereBetween('enrollments.created_at', [$request->date_issure . ' 00:00:00', $request->date_end . ' 23:59:59'])
            ->orderBy('students.name', 'asc');

        if ($type === 'dropout') {
            $query->where('enrollments.dropout', 1)->get(['enrollments.*', 'students.name']); // Ou qualquer condição que você precise
        } elseif ($type == 'confirmacao') {
           $query = Enrollment::withRegistrations();
        } else {
            $query->where('enrollments.message', $type)->where('enrollments.dropout', '0')->where('enrollments.status', '1')->get(['enrollments.*', 'students.name']);
        }

        // ->where('enrollments.dropout', '1')

        $enrollment = EnrollmentResource::collection($query->get());

        $dados = [
            'dados' => ($enrollment),
            'empresa' => Company::findOrFail(intval($request->company)),
            "request" => $request,
        ];

        $pdf = PDF::loadView('documents.inscricao_matricula', compact('dados'));
        $pdf->setPaper('A4', 'portrait');
        #download
        return $pdf->stream('matrícula-' . time() . '.pdf');
    }

    public function pautageral($id)
    {

        $pauta = ScheduleResource::make(Schedule::findOrFail($id));

        // $idanolectivo = $pauta->school_year_id;
        // $idclasse = $pauta->classe_id;
        // $idcurso = $pauta->course_id;
        // $idsala = $pauta->class_room_id;
        // $idturma = $pauta->turma_id;
        // $idperiodo = $pauta->period_id;
        $empresa = CompanyResource::make(Company::findOrFail($pauta->company_id));

        // $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        return view('documents.schedule', compact('pauta', 'empresa'));
        $pdf = new TCPDF();
        $pdf->SetFont('times', 'B', 16);

        // $pdf->SetAuthor('Hilquias');
        // $pdf->SetTitle('Aluno');
        // $pdf->SetSubject('Aluno');
        // $pdf->AddPage();

        // . $this->baseinstituicao->getAll()[0]->logotipo

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Pauta');
        $pdf->SetSubject('Pauta');
        $pdf->AddPage();

        $pdf->Image(public_path('images/logo.png'), 102, 13, 15, 15, '', '', '', false, 100);
        // $pdf->Image(base_url() . "assets/media/imagem/insignia.jpg", 38, 10, 15, 'JPG');

        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(277, 12, '', 0, 1);
        $pdf->Cell(70, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(70, 5, 'MINISTÉRIO DA EDUCAÇÃO', 0, 1, 'C');

        $pdf->SetFont('Times', 'B', 14, '', true);
        $pdf->SetXY($pdf->GetX() + 30, $pdf->GetY() - 30);
        if ($idclasse <= 5) {
            $pdf->Cell(277, 5, 'PAUTA PARA AS CLASSES DE TRANSIÇÃO DO ENSINO PRIMÁRIO GERAL', 0, 1, 'C');
        } elseif ($idclasse <= 11) {
            $pdf->Cell(277, 5, 'PAUTA PARA AS CLASSES DE EXAME DO 1º CICLO DO ENSINO SECUNDÁRIO', 0, 1, 'C');
        } else {
            $pdf->Cell(277, 5, 'PAUTA PARA AS CLASSES DE TRANSIÇÃO DO ENSINO SECUNDÁRIO GERAL', 0, 1, 'C');
        }
        $pdf->SetXY(40, 25);
        $pdf->Cell(277, 5, 'ESCOLA: ' . $baseinstituicao->designation, 0, 1, 'C');

        $pdf->Ln(20);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, 'ANO LECTIVO ' . $this->basepauta->cabecalhopauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)[0]->anolectivo, 0, 0);

        $pdf->Cell(50, 5, 'CLASSE: ' . $this->basepauta->cabecalhopauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)[0]->classe, 0, 0);
        if (intval($this->basepauta->cabecalhopauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)[0]->idclasse) > 9) {
            $pdf->Cell(70, 5, 'CURSO: ' . $this->basepauta->cabecalhopauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)[0]->curso, 0, 0);
        }
        $pdf->Cell(30, 5, 'SALA: ' . $this->basepauta->cabecalhopauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)[0]->sala, 0, 0);

        $pdf->Cell(30, 5, 'TURMA: ' . $this->basepauta->cabecalhopauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)[0]->turma, 0, 0);

        $pdf->Cell(40, 5, 'CIRCLO: ' . (($this->basepauta->cabecalhopauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)[0]->classe < 7) ? "PRIMÁRIO" : (($this->basepauta->cabecalhopauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)[0]->classe < 10) ? "I CICLO" : "II CICLO")), 0, 0);

        $pdf->Cell(30, 5, 'PERÍODO: ' . $this->basepauta->cabecalhopauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)[0]->periodo, 0, 1);

        //        $idpauta = intval($this->basepauta->cabecalhominipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso,$idsala, $idturma)[0]->idpauta);
        
                $pdf->Ln(10);
                $pdf->SetFont('Times', 'B', 8);
        
                $pdf->Cell(5, 10, 'Nº', 1, 0);
                $pdf->Cell(50, 10, 'NOME COMPLETO', 1, 0, 'L');
                foreach ($this->basepauta->getDisciplinaPauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo) as $valor) {
                    $pdf->Cell(18, 5, substr($valor->disciplina, 0, 5) . '.', 1, 0, 'C');
                }
                $pdf->Cell(15, 10, 'OBS.', 1, 1, 'C');
        ////
        $pdf->SetXY($pdf->GetX() + 55, $pdf->GetY() - 5);
        $pdf->SetFont('Times', 'B', 7);
        foreach ($this->basepauta->getDisciplinaPauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo) as $valor) {
            $pdf->Cell(6, 5, 'CAP', 1, 0, 'C');
            $pdf->Cell(6, 5, 'CPE', 1, 0, 'C');
            $pdf->Cell(6, 5, 'CF', 1, 0, 'C');
        }
        $pdf->Cell(6, 5, '', 0, 1, 'C');
        $pdf->SetFont('Times', '', 8);
        $cont = 0;
        foreach ($this->basepauta->getNomePauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo) as $item1) {
            $pdf->Cell(5, 5, ($cont++) + 1, 1, 0);
            $pdf->Cell(50, 5, $this->abreviarnome($item1->nome), 1, 0);

            $totalnota = 0;
            foreach ($this->basepauta->getDisciplinaPauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo) as $item2) {

                //CALCULO DOS TRIMESTRE-TRIMESTRE
                $mac = 0;
                $cpp = 0;
                $cap = 0;
                $cpe = 0;
                $ct = 0;
                $cf = 0;
                for ($contfase = 1; $contfase <= 3; $contfase++) {
                    $mac = (isset($this->basepauta->getNotaMiniPauta($idanolectivo, $item2->iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, $contfase)[($cont - 1)]->mac)) ? $this->basepauta->getNotaMiniPauta($idanolectivo, $item2->iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, $contfase)[($cont - 1)]->mac : 0;
                    $cpp = (isset($this->basepauta->getNotaMiniPauta($idanolectivo, $item2->iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, $contfase)[($cont - 1)]->cpp)) ? $this->basepauta->getNotaMiniPauta($idanolectivo, $item2->iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, $contfase)[($cont - 1)]->cpp : 0;
                    $ct += ((floatval($mac) + floatval($cpp)) / 2);
                }

                //FINAL
                $cap = ((floatval($ct)) / 3);
                $cpe = (isset($this->basepauta->getNotaMiniPauta($idanolectivo, $item2->iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 3)[($cont - 1)]->cpe)) ? $this->basepauta->getNotaMiniPauta($idanolectivo, $item2->iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 3)[($cont - 1)]->cpe : 0;

                switch ($this->basepauta->cabecalhopauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)[0]->classe) {
                    case 1:
                        $cf = $cap;
                        break;
                    case 2:
                        $cf = $cap;
                        break;
                    case 3:
                        $cf = $cap;
                        break;
                    case 4:
                        $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));
                        break;
                    case 5:
                        $cf = $cap;
                        break;
                    case 6:
                        $cf = $cap;
                        break;
                    case 7:
                        $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));
                        break;
                    case 8:
                        $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));
                        break;
                    case 9:
                        $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));
                        break;
                    case 10:
                        $cf = $cap;
                        break;
                    case 11:
                        $cf = $cap;
                        break;
                    case 12:
                        $cf = $cap;
                        break;
                    case 13:
                        $cf = $cap;
                        break;
                    default:
                        $cf = $cap;
                        break;
                        //
                }

                if ($cap >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(6, 5, number_format(floatval($cap), 1), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(6, 5, number_format(floatval($cap), 1), 1, 0, 'C');
                }

                if ($cpe >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(6, 5, number_format(floatval($cpe), 1), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(6, 5, number_format(floatval($cpe), 1), 1, 0, 'C');
                }
                if ($cf >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(6, 5, number_format(floatval($cf), 1), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(6, 5, number_format(floatval($cf), 1), 1, 0, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                $totalnota += $cf;
            }
            $totalnota = ($totalnota / count($this->basepauta->getDisciplinaPauta($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo)));
            if ($totalnota >= 10) {
                $pdf->SetTextColor(0, 0, 255);
                $pdf->Cell(15, 5, 'APTO', 1, 1, 'C');
            } else {
                $pdf->SetTextColor(255, 0, 0);
                $pdf->Cell(15, 5, 'N/APTO', 1, 1, 'C');
            }
            $pdf->SetTextColor(0, 0, 0);
        }

        $pdf->Ln((154) - $pdf->GetY());
        $pdf->SetFont('Times', '', 8, '', true);
        $pdf->SetX(10);
        $pdf->Cell(91, 5, 'O CORPO DE JÚRI', 0, 0, 'L');
        $pdf->Cell(91, 5, 'O SUBDIRECTOR PEDAGÓGICO DA ESCOLA', 0, 0, 'C');
        $pdf->Cell(91, 5, 'O DIRECTOR DA ESCOLA', 0, 1, 'C');
        $pdf->Cell(91, 5, '1.______________________________________', 0, 0, 'L');
        $pdf->Cell(91, 5, '', 0, 0, 'C');
        $pdf->Cell(91, 5, '', 0, 1, 'C');
        $pdf->Cell(91, 5, '2.______________________________________', 0, 0, 'L');
        $pdf->Cell(91, 5, '______________________________________', 0, 0, 'C');
        $pdf->Cell(91, 5, '______________________________________', 0, 1, 'C');
        $pdf->Cell(91, 5, '3.______________________________________', 0, 0, 'L');
        $pdf->Cell(91, 5, date('d') . ' / ' . $this->basemes->getId(date('m'))[0]->designacao . ' / ' . date('Y'), 0, 0, 'C');
        $pdf->Cell(91, 5, date('d') . ' / ' . $this->basemes->getId(date('m'))[0]->designacao . ' / ' . date('Y'), 0, 1, 'C');
        //        $pdf->Cell(190, 5, ((isset($this->basehome->getPessoa($this->basepauta->getNomeMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 1)[0]->idprofessor)[0]->nome)) ? $this->basehome->getPessoa($this->basepauta->getNomeMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 1)[0]->idprofessor)[0]->nome : ''), 0, 1, 'C');

        $pdf->SetX(260);
        $pdf->write1DBarcode($idanolectivo . $idperiodo . $idclasse . $idcurso . $idsala . $idturma, 'C128', '', '', '', 10, 0.39, $this->style, 'N');

        $pdf->Line(10, $pdf->getY(), 285, $pdf->getY());
        $pdf->SetX(10);
        $pdf->Cell(30, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->Output();

        $pdf->Output();
    }

    public function minipauta($id)
    {
        $minipauta = MiniScheduleResource::make(MiniSchedule::findOrFail($id));
        $notas = GradeScheduleResource::collection(Grade::where('mini_schedule_id', $id)->get());
        $empresa = CompanyResource::make(Company::findOrFail($minipauta->company_id));
        return view('documents.minischedule', compact('minipauta', 'empresa', 'notas'));
    }
}
