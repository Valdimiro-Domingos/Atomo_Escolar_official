<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* Autor: Hilquias Chitazo 19/01/2019 17:12
 * Descrição: Construção da controller Sistema
 */

class RelactorioPautaController extends CI_Controller {

//Funcao que instacia a classe
    public function __construct() {
        parent:: __construct();
        $this->verificar_sessao();
        require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
        $this->load->model('Sistema/HomeModel', 'basehome');
        $this->load->model('Sistema/MesModel', 'basemes');
        $this->load->model('Sistema/InstituicaoModel', 'baseinstituicao');
        $this->load->model('Sistema/FuncionarioModel', 'basefuncionario');
        $this->load->model('Sistema/MesModel', 'basemes');
        $this->load->model('Sistema/CursoModel', 'basecurso');
        $this->load->model('Sistema/PeriodoModel', 'baseperiodo');
        $this->load->model('Sistema/FaseModel', 'basefase');
        $this->load->model('Sistema/ClasseModel', 'baseclasse');
        $this->load->model('Sistema/TurmaModel', 'baseturma');
        $this->load->model('Sistema/SalaModel', 'basesala');
        $this->load->model('Sistema/DisciplinaModel', 'basedisciplina');
        $this->load->model('Sistema/PautaModel', 'basepauta');
        $this->load->model('Sistema/NotaModel', 'basenota');
    }

    public function verificar_sessao() {
        if ($this->session->userdata('logado') == false) {
            redirect('login');
        }
    }

    public function mecanografico($valor) {
        if ($valor > 1000) {
            return 'FAC' . $valor;
        } elseif ($valor > 100) {
            return "FAC0" . $valor;
        } elseif ($valor > 10) {
            return "FAC00" . $valor;
        } else {
            return "FAC000" . $valor;
        }
    }

    public function abreviarnome($valor) {
        $auxnome = explode(' ', $valor);
        $nomenovo;
        if (count($auxnome) > 1) {
            for ($i = 0; $i < count($auxnome); $i++) {
                if ($i == 0) {
                    $nomenovo = $auxnome[$i] . ' ';
                } elseif ($i == count($auxnome) - 1) {
                    return $nomenovo .= $auxnome[$i];
                } else {
                    $nomenovo .= substr($auxnome[$i], 0, 1) . '. ';
                }
            }
        } else {
            return $valor;
        }
    }

    private $style = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => false,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false,
        'text' => false,
        'font' => 'Times',
        'fontsize' => 9,
        'stretchtext' => 4
    );

    public function minipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma) {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Mini-Pauta');
        $pdf->SetSubject('Mini-Pauta');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/insignia.jpg", 98, 10, 15, 'JPG');

        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 12, '', 0, 1);
        $pdf->Cell(190, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(190, 5, 'MINISTÉRIO DA EDUCAÇÃO', 0, 1, 'C');
        $pdf->Cell(190, 5, 'MINI-PAUTA', 0, 1, 'C');

        $pdf->Cell(189, 10, '', 0, 1);
        $pdf->SetFont('Times', 'B', 14, '', true);
        $pdf->Cell(189, 5, $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');


        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(189, 12, '', 0, 1);

        $pdf->Cell(55, 5, 'DISCIPLINA: ' . $this->basepauta->cabecalhominipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma)[0]->disciplina, 0, 0);
        $pdf->Cell(40, 5, 'CLASSE: ' . $this->basepauta->cabecalhominipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma)[0]->classe, 0, 0);
        if (intval($this->basepauta->cabecalhominipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma)[0]->idclasse) > 9) {
            $pdf->Cell(40, 5, 'CURSO: ' . $this->basepauta->cabecalhominipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma)[0]->curso, 0, 1);
        }
        $pdf->Cell(29, 5, 'TURMA: ' . $this->basepauta->cabecalhominipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma)[0]->turma, 0, 0);
        //        $pdf->Cell(190, 5, 'SALA: ' . $this->basepauta->cabecalhominipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma)[0]->sala, 0, 1);
        //        $pdf->Cell(190, 5, 'PERÍODO: ' . $this->basepauta->cabecalhominipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma)[0]->periodo, 0, 1);
        $pdf->Cell(35, 5, 'ANO LECTIVO ' . $this->basepauta->cabecalhominipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma)[0]->anolectivo, 0, 1);

        $pdf->SetY($pdf->GetY() - 28);
        $pdf->setX(19.9);
        $pdf->Ln(40);
        $pdf->SetFont('Times', 'B', 10);

        $pdf->Cell(5, 10, 'Nº', 1, 0);
        $pdf->Cell(59, 10, 'Nome Completo', 1, 0, 'C');
        $pdf->Cell(30, 5, 'I TRIMESTRE', 1, 0, 'C');
        $pdf->Cell(30, 5, 'II TRIMESTRE', 1, 0, 'C');
        $pdf->Cell(30, 5, 'III TRIMESTRE', 1, 0, 'C');
        $pdf->Cell(10, 10, 'CAP', 1, 0, 'C');
        $pdf->Cell(15, 10, 'CPE/CE', 1, 0, 'C');
        $pdf->Cell(10, 10, 'CF', 1, 1, 'C');

        $pdf->SetXY($pdf->GetX() + 64, $pdf->GetY() - 5);
        $pdf->Cell(10, 5, 'MAC', 1, 0, 'C');
        $pdf->Cell(10, 5, 'CPP', 1, 0, 'C');
        $pdf->Cell(10, 5, 'CP', 1, 0, 'C');
        $pdf->Cell(10, 5, 'MAC', 1, 0, 'C');
        $pdf->Cell(10, 5, 'CPP', 1, 0, 'C');
        $pdf->Cell(10, 5, 'CP', 1, 0, 'C');
        $pdf->Cell(10, 5, 'MAC', 1, 0, 'C');
        $pdf->Cell(10, 5, 'CPP', 1, 0, 'C');
        $pdf->Cell(10, 5, 'CP', 1, 1, 'C');
        $pdf->SetFont('Times', '', 10);
        $cont = 1;

        if (count($this->basepauta->getNomeMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 1))) {
            for ($i = 0; $i < count($this->basepauta->getNomeMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 1)); $i++) {
                $cap = 0;
                $cpe = 0;
                $cf = 0;
                $pdf->Cell(5, 5, $cont++, 1, 0);
                $pdf->Cell(59, 5, $this->abreviarnome($this->basepauta->getNomeMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 1)[$i]->nome), 1, 0);

                //I-TRIMESTRE
                $mac = (isset($this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 1)[$i]->mac)) ? $this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 1)[$i]->mac : 0;
                $cpp = (isset($this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 1)[$i]->cpp)) ? $this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 1)[$i]->cpp : 0;
                $ct1 = ((floatval($mac) + floatval($cpp)) / 2);

                if ($mac >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($mac), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($mac), 2), 1, 0, 'C');
                }
                if ($cpp >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($cpp), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($cpp), 2), 1, 0, 'C');
                }
                if ($ct1 >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($ct1), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($ct1), 2), 1, 0, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);



                //II-TRIMESTRE
                $mac = (isset($this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 2)[$i]->mac)) ? $this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 2)[$i]->mac : 0;
                $cpp = (isset($this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 2)[$i]->cpp)) ? $this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 2)[$i]->cpp : 0;
                $ct2 = ((floatval($mac) + floatval($cpp)) / 2);

                if ($mac >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($mac), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($mac), 2), 1, 0, 'C');
                }
                if ($cpp >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($cpp), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($cpp), 2), 1, 0, 'C');
                }
                if ($ct2 >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($ct2), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($ct2), 2), 1, 0, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                //III-TRIMESTRE
                $mac = (isset($this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 3)[$i]->mac)) ? $this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 3)[$i]->mac : 0;
                $cpp = (isset($this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 3)[$i]->cpp)) ? $this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 3)[$i]->cpp : 0;
                $ct3 = ((floatval($mac) + floatval($cpp)) / 2);

                if ($mac >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($mac), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($mac), 2), 1, 0, 'C');
                }
                if ($cpp >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($cpp), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($cpp), 2), 1, 0, 'C');
                }
                if ($ct3 >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($ct3), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($ct3), 2), 1, 0, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                //FINAL
                $cap = ((floatval($ct1) + floatval($ct2) + floatval($ct3)) / 3);
                $cpe = (isset($this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 3)[$i]->cpe)) ? $this->basepauta->getNotaMiniPauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma, 3)[$i]->cpe : 0;


                switch ($this->basepauta->cabecalhominipauta($idanolectivo, $iddisciplina, $idperiodo, $idclasse, $idcurso, $idsala, $idturma)[0]->classe) {
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
                    default :
                        $cf = $cap;
                        break;
                    //
                }



                if ($cap >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($cap), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($cap), 2), 1, 0, 'C');
                }

                if ($cpe >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(15, 5, number_format(floatval($cpe), 2), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(15, 5, number_format(floatval($cpe), 2), 1, 0, 'C');
                }
                if ($cf >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, number_format(floatval($cf), 2), 1, 1, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, number_format(floatval($cf), 2), 1, 1, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);
            }
        }
        $pdf->Cell(189, 12, 'CT=(MAC+CPP)/2;      CAP=(CT1+CT2+CT3)/3;     CF=0,4xCAP+0,6xCPE', 0, 1, 'C');


        $pdf->Ln((237) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(190, 5, '', 0, 1, 'C');
        $pdf->Cell(190, 5, '_____________________________', 0, 1, 'C');
        $pdf->Cell(190, 5, 'PROFESSOR', 0, 1, 'C');

        $pdf->SetX(170);
        $pdf->write1DBarcode($idanolectivo . $iddisciplina . $idperiodo . $idclasse . $idcurso . $idsala . $idturma, 'C128', '', '', '', 10, 0.39, $this->style, 'N');

        $pdf->SetFont('Times', '', 10);
        $pdf->Line(19, $pdf->getY(), 193, $pdf->getY());
        $pdf->SetX(18);
        $pdf->Cell(30, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->Output();
    }

    public function pautageral($idanolectivo, $idclasse, $idcurso, $idsala, $idturma, $idperiodo) {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Pauta');
        $pdf->SetSubject('Pauta');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/insignia.jpg", 38, 10, 15, 'JPG');

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
        $pdf->Cell(277, 5, 'ESCOLA: ' . $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');

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
                for ($contfase = 1; $contfase <= 3; $contfase ++) {
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
                    default :
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

}
