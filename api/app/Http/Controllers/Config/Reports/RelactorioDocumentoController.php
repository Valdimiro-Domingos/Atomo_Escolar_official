<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* Autor: Hilquias Chitazo 19/01/2019 17:12
 * Descrição: Construção da controller Sistema
 */

class RelactorioDocumentoController extends CI_Controller
{

    //Funcao que instacia a classe
    public function __construct()
    {
        parent::__construct();
        $this->verificar_sessao();
        require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
        $this->load->model('Sistema/HomeModel', 'basehome');
        $this->load->model('Sistema/MesModel', 'basemes');
        $this->load->model('Sistema/InscricaoModel', 'baseinscricao');
        $this->load->model('Sistema/MatriculaModel', 'basematricula');
        $this->load->model('Sistema/InstituicaoModel', 'baseinstituicao');
        $this->load->model('Sistema/RepresentanteModel', 'baserepresentante');
        $this->load->model('Sistema/PessoaModel', 'basepessoa');
        $this->load->model('Sistema/FuncionarioModel', 'basefuncionario');
        $this->load->model('Sistema/MesModel', 'basemes');
        $this->load->model('Sistema/EstudanteModel', 'baseestudante');
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

    public function verificar_sessao()
    {
        if ($this->session->userdata('logado') == false) {
            redirect('login');
        }
    }

    public function mecanografico($valor)
    {
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

    public function abreviarnome($valor)
    {
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

    public function transferencia($idestudante)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Documento');
        $pdf->SetSubject('Documento');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/fundocert1.jpg", 07, 10, 'JPG');
        $pdf->Image(base_url() . "assets/media/imagem/insignia.jpg", 98, 25, 15, 'JPG');

        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 27, '', 0, 1);
        $pdf->Cell(190, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(190, 5, 'MINISTÉRIO DA EDUCAÇÃO', 0, 1, 'C');
        $pdf->Cell(190, 5, $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');
        $pdf->Cell(189, 20, '', 0, 1);
        if ((count($this->baseestudante->getestudantetransferidos($idestudante)) != 0)) {


            $pdf->SetFont('Times', 'B', 20, '', true);
            $pdf->SetTextColor(0, 0, 255);
            $pdf->Cell(189, 5, 'GUIA  DE TRANSFERÊNCIA', 0, 1, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Ln(20);
            $pdf->SetFont('Times', '', 12);
            //        $pdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth)
            $pdf->SetX(25);
            $pdf->MultiCell(160, 30, $this->baserepresentante->listar()[0]->rep1 . " DIRECTOR DO " . $this->baseinstituicao->listar()[0]->nome . ", certifica  que " . $this->baseestudante->getestudantetransferidos($idestudante)[0]->nome . " nascido(a) "
                . "aos " . date(date('d', strtotime($this->baseestudante->getestudantetransferidos($idestudante)[0]->data))) . " de " . ($this->basemes->getId(date(date('m', strtotime($this->baseestudante->getestudantetransferidos($idestudante)[0]->data))))[0]->designacao) . " "
                . "de " . date(date('Y', strtotime($this->baseestudante->getestudantetransferidos($idestudante)[0]->data))) . " "
                . "natural de " . $this->baseestudante->getestudantetransferidos($idestudante)[0]->municipio . " "
                . "província de  " . $this->baseestudante->getestudantetransferidos($idestudante)[0]->provincia . " "
                . "filho de " . $this->baseestudante->getestudantetransferidos($idestudante)[0]->pai . " e de " . $this->baseestudante->getestudantetransferidos($idestudante)[0]->mae . " Portador(a) da cédula pessoal/bilhete de identidade nº. " . $this->baseestudante->getestudantetransferidos($idestudante)[0]->ndi . ". "
                . "Vai Transferido do Ensino " . (($this->baseestudante->getestudantetransferidos($idestudante)[0]->classe < 7) ? "PRIMÁRIO" : (($this->baseestudante->getestudantetransferidos($idestudante)[0]->classe < 10) ? "I CICLO" : "II CICLO")) . " (" . $this->baseestudante->getestudantetransferidos($idestudante)[0]->classe . ((strlen($this->baseestudante->getestudantetransferidos($idestudante)[0]->classe) > 3) ? "" : "ª Classe") . ")"
                . (($this->baseestudante->getestudantetransferidos($idestudante)[0]->classe > 9) ? " no curso " . $this->baseestudante->getestudantetransferidos($idestudante)[0]->curso : "") . ", para a instituição " . $this->baseestudante->getestudantetransferidos($idestudante)[0]->instituicao
                . " com as seguintes Classificações:", 1, 'J');
            $pdf->SetFont('Times', '', 10, '', true);
            $pdf->Ln(10);
            $pdf->SetX(25);
            $pdf->Cell(80, 5, 'Disciplinas', 1, 0, 'C');
            $pdf->Cell(80, 5, $this->baseestudante->getestudantetransferidos($idestudante)[0]->classe . 'ª Classe', 1, 1, 'C');



            $totalnota = 0;
            foreach ($this->basepauta->getDisciplinaPauta($this->baseestudante->getestudantetransferidos($idestudante)[0]->idanolectivo, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idclasse, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idcurso, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idsala, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idturma, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idperiodo) as $item1) {

                $pdf->SetX(25);
                $pdf->Cell(80, 5, $item1->disciplina, 1, 0, 'L');
                //CALCULO DOS TRIMESTRE-TRIMESTRE
                $mac = 0;
                $cpp = 0;
                $cap = 0;
                $cpe = 0;
                $ct = 0;
                $cf = 0;
                for ($contfase = 1; $contfase <= 3; $contfase++) {
                    $mac = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idperiodo, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idclasse, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idcurso, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idsala, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idturma, $contfase)[0]->mac)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idperiodo, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idclasse, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idcurso, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idsala, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idturma, $contfase)[0]->mac : 0;
                    $cpp = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idperiodo, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idclasse, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idcurso, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idsala, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idturma, $contfase)[0]->cpp)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idperiodo, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idclasse, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idcurso, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idsala, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idturma, $contfase)[0]->cpp : 0;
                    $ct += ((floatval($mac) + floatval($cpp)) / 2);
                }

                //FINAL
                $cap = ((floatval($ct)) / 3);
                $cpe = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idperiodo, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idclasse, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idcurso, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idsala, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idturma, 3)[0]->cpe)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idperiodo, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idclasse, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idcurso, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idsala, $this->baseestudante->getestudantetransferidos($idestudante)[0]->idturma, 3)[0]->cpe : 0;
                $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));

                if ($cf >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(80 / 2, 5, number_format(floatval($cf), 1), 1, 1, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(80, 5, number_format(floatval($cf), 1), 1, 1, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);


                $totalnota += $cf;
            }



            $pdf->Ln((200) - $pdf->GetY());
            $pdf->SetFont('Times', '', 12, '', true);
            $pdf->SetX(25);
            $pdf->MultiCell(160, 10, "Por ser verdade, passou-se o presente Certificado que vai assinado pelo Director(a) e autenticado com o carimbo em uso neste estabelecimento de ensino.", 1, 'J');
            $pdf->Cell(190, 10, '', 0, 1, 'C');
            $pdf->Cell(190, 5, $this->baseinstituicao->getAll()[0]->municipio . ", aos " . date('d') . ' / ' . $this->basemes->getId(date('m'))[0]->designacao . ' / ' . date('Y'), 0, 1, 'C');
            $pdf->Cell(190, 20, '', 0, 1, 'C');
            $pdf->Cell(((190) / 2), 5, "Director (a) do Complexo Escolar	", 0, 0, 'C');
            $pdf->Cell(((190) / 2), 5, "Director (a)", 0, 1, 'C');
            $pdf->Cell(((190) / 2), 5, "______________________________________", 0, 0, 'C');
            $pdf->Cell(((190) / 2), 5, "______________________________________", 0, 1, 'C');
            $pdf->Cell(((190) / 2), 5, $this->baserepresentante->listar()[0]->rep1, 0, 0, 'C');
            $pdf->Cell(((190) / 2), 5, $this->baserepresentante->listar()[0]->rep2, 0, 1, 'C');
        }
        $pdf->Output();
    }


    public function reciboinscricaotermica($valor)
    {
        $dados['dados'] = array(
            "instituicao" => $this->baseinstituicao->getAll(),
            "dados" => $this->baseestudante->getEstudanteInscricao($valor),
        );
        $this->load->view('Sistema/Factura/ImpressaoTermicaInscricao', $dados);
    }


    public function reciboinscricao($valor)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('MeuGenio');
        $pdf->SetTitle('Recibo');
        $pdf->SetSubject('Recibo');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 20, 8, 22, 'JPG');

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(120, 5, '', 0, 0);
        $pdf->Cell(59, 7, 'RECIBO DE INSCRICÃO ', 0, 1);
        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(130, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0);
        $pdf->Cell(25, 5, 'Data:', 0, 0);
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(34, 5, date('d/m/Y', strtotime($this->baseestudante->getEstudanteInscricao($valor)[0]->datainscricao)), 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);


        $pdf->Cell(130, 5, $this->baseinstituicao->getAll()[0]->municipio . ', ANGOLA', 0, 0);
        $pdf->Cell(25, 5, 'Recibo Nº', 0, 0);
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(34, 5, $valor, 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(130, 5, 'Telefone: ' . $this->baseinstituicao->getAll()[0]->telefone, 0, 1);
        $pdf->Cell(130, 5, 'Email: ' . ($this->baseinstituicao->getAll()[0]->email), 0, 1);
        $pdf->Cell(130, 5, '', 0, 1);
        $pdf->Cell(130, 5, 'Identificação Nº' . $this->baseinstituicao->getAll()[0]->nif, 0, 1);

        $pdf->Image(base_url() . "assets/media/imagem/avatar.jpg", 15, 61, 30, 'JPG');
        $pdf->Cell(189, 10, '', 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'NOME:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, $this->baseestudante->getEstudanteInscricao($valor)[0]->nome, 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'IDENTIFICAÇÃO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, $this->baseestudante->getEstudanteInscricao($valor)[0]->ndi, 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'GÉNERO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, strtoupper($this->baseestudante->getEstudanteInscricao($valor)[0]->genero), 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'DATA DE NASCIMENTO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(90, 5, date('d/m/Y', strtotime($this->baseestudante->getEstudanteInscricao($valor)[0]->data)), 0, 1);



        $pdf->Cell(90, 5, '', 0, 1);



        $pdf->SetFillColor(222, 222, 222);
        $pdf->SetY($pdf->GetY() - 40);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Ln(40);
        $pdf->Cell(189, 1, ' DADOS DA INSCRICÃO', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 10);
        $pdf->Ln(9);

        $html = "";
        $html .= "<table border='1' >";
        $html .= "<tr>";
        /*   $html .= "<th><b>#</b></th>"; */
        $html .= "<th style='width: 5px;'><b>#</b></th>";
        $html .= "<th><b>Classe</b></th>";
        $html .= "<th><b>Curso</b></th>";
        $html .= "<th><b>Ano Lectivo</b></th>";
        $html .= "<th><b>Valor</b></th>";
        $html .= "</tr>";
        $cont = 1;

        $pdf->SetFont('Times', '', 8);
        foreach ($this->baseestudante->getEstudanteInscricao($valor) as $item) {
            $html .= "<tr>";
            /* $html .= "<td>" . ($cont++) . "</td>"; */
            $html .= "<td style='text-align:right; width: 10px;'>" . $cont++ . "</td>";
            $html .= "<td style='text-align:right;'>" .  $item->classe . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->curso . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->anolectivo . "</td>";
            $html .= "<td style='text-align:right;'>" . number_format(($item->valor), 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }

        $html .= "  </table>";


        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->SetFont('Times', '', 10);
        $pdf->Ln((115) - $pdf->GetY());

        $pdf->Cell(((190)), 5, "O (A) REPRESENTANTE", 0, 1, 'C');
        $pdf->Cell(((190)), 5, '______________________________________', 0, 1, 'C');

        $pdf->write1DBarcode($valor, 'C128', '', '', '', 8, 0.39, $this->style, 'N');
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(19, $pdf->getY(), 193, $pdf->getY());
        $pdf->SetX(18);
        $pdf->Cell(30, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->SetX(84);
        $pdf->Cell(33, 5, "", 0, 0, 'L');
        $pdf->Cell(100, 5, "Operador: " . $this->basehome->getOperador($this->baseestudante->getEstudanteInscricao($valor)[0]->idoperador)[0]->operador, 0, 0, 'L');


        ////////////////////////////////////////////////////
        $pdf->Ln((140) - $pdf->GetY());
        $pdf->Line(0, $pdf->getY(), 220, $pdf->getY());
        $pdf->Ln((150) - $pdf->GetY());
        ////////////////////////////////////////////////////

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 20, 148, 22, 'JPG');

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(120, 5, '', 0, 0);
        $pdf->Cell(59, 7, 'RECIBO DE INSCRICÃO ', 0, 1);
        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(130, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0);
        $pdf->Cell(25, 5, 'Data:', 0, 0);
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(34, 5, date('d/m/Y', strtotime($this->baseestudante->getEstudanteInscricao($valor)[0]->datainscricao)), 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);


        $pdf->Cell(130, 5, $this->baseinstituicao->getAll()[0]->municipio . ', ANGOLA', 0, 0);
        $pdf->Cell(25, 5, 'Recibo Nº', 0, 0);
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(34, 5, $valor, 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(130, 5, 'Telefone: ' . $this->baseinstituicao->getAll()[0]->telefone, 0, 1);
        $pdf->Cell(130, 5, 'Email: ' . ($this->baseinstituicao->getAll()[0]->email), 0, 1);
        $pdf->Cell(130, 5, '', 0, 1);
        $pdf->Cell(130, 5, 'Identificação Nº' . $this->baseinstituicao->getAll()[0]->nif, 0, 1);

        $pdf->Image(base_url() . "assets/media/imagem/avatar.jpg", 15, 202, 30, 'JPG');
        $pdf->Cell(189, 10, '', 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'NOME:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, $this->baseestudante->getEstudanteInscricao($valor)[0]->nome, 0, 1);

        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'IDENTIFICAÇÃO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, $this->baseestudante->getEstudanteInscricao($valor)[0]->ndi, 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'GÉNERO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(90, 5, strtoupper($this->baseestudante->getEstudanteInscricao($valor)[0]->genero), 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'DATA DE NASCIMENTO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(90, 5, date('d/m/Y', strtotime($this->baseestudante->getEstudanteInscricao($valor)[0]->data)), 0, 1);



        $pdf->Cell(90, 5, '', 0, 1);



        $pdf->SetFillColor(222, 222, 222);
        $pdf->SetY($pdf->GetY() - 40);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Ln(40);
        $pdf->Cell(189, 1, ' DADOS DA INSCRICÃO', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 10);
        $pdf->Ln(9);

        $html = "";
        $html .= "<table border='1' >";
        $html .= "<tr>";
        /*   $html .= "<th><b>#</b></th>"; */
        $html .= "<th style='width: 5px;'><b>#</b></th>";
        $html .= "<th><b>Classe</b></th>";
        $html .= "<th><b>Curso</b></th>";
        $html .= "<th><b>Ano Lectivo</b></th>";
        $html .= "<th><b>Valor</b></th>";
        $html .= "</tr>";
        $cont = 1;

        $pdf->SetFont('Times', '', 8);
        foreach ($this->baseestudante->getEstudanteInscricao($valor) as $item) {
            $html .= "<tr>";
            /* $html .= "<td>" . ($cont++) . "</td>"; */
            $html .= "<td style='text-align:right; width: 10px;'>" . $cont++ . "</td>";
            $html .= "<td style='text-align:right;'>" .  $item->classe . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->curso . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->anolectivo . "</td>";
            $html .= "<td style='text-align:right;'>" . number_format(($item->valor), 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }

        $html .= "  </table>";


        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Ln((183) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10);


        $pdf->Ln((253) - $pdf->GetY());
        $pdf->Cell(((190)), 5, "O (A) REPRESENTANTE", 0, 1, 'C');
        $pdf->Cell(((190)), 5, '______________________________________', 0, 1, 'C');
        $pdf->SetX(15);
        $pdf->write1DBarcode($valor, 'C128', '', '', '', 8, 0.39, $this->style, 'N');
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(19, $pdf->getY(), 193, $pdf->getY());
        $pdf->SetX(18);
        $pdf->Cell(30, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->SetX(84);
        $pdf->Cell(33, 5, "", 0, 0, 'L');
        $pdf->Cell(100, 5, "Operador: " . $this->basehome->getOperador($this->baseestudante->getEstudanteInscricao($valor)[0]->idoperador)[0]->operador, 0, 0, 'L');

        $pdf->Output();
    }


    public function recibomatriculatermica($valor)
    {
        $dados['dados'] = array(
            "instituicao" => $this->baseinstituicao->getAll(),
            "dados" => $this->baseestudante->getEstudanteMatricula($valor),
        );
        $this->load->view('Sistema/Factura/ImpressaoTermicaMatricula', $dados);
    }


    public function recibomatricula($valor)
    {


        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Sublime');
        $pdf->SetTitle('Recibo');
        $pdf->SetSubject('Recibo');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 20, 8, 22, 'JPG');

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(120, 5, '', 0, 0);
        $pdf->Cell(59, 7, 'RECIBO DE MATRICULA ', 0, 1);
        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(130, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0);
        $pdf->Cell(25, 5, 'Data:', 0, 0);
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(34, 5, date('d/m/Y', strtotime($this->baseestudante->getEstudanteMatricula($valor)[0]->datamatricula)), 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);


        $pdf->Cell(130, 5, $this->baseinstituicao->getAll()[0]->municipio . ', ANGOLA', 0, 0);
        $pdf->Cell(25, 5, 'Recibo Nº', 0, 0);
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(34, 5, $valor, 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(130, 5, 'Telefone: ' . $this->baseinstituicao->getAll()[0]->telefone, 0, 1);
        $pdf->Cell(130, 5, 'Email: ' . ($this->baseinstituicao->getAll()[0]->email), 0, 1);
        $pdf->Cell(130, 5, 'C. Postal: ' . $this->baseinstituicao->getAll()[0]->cpostal, 0, 1);
        $pdf->Cell(130, 5, 'Contribuinte Nº ' . $this->baseinstituicao->getAll()[0]->nif, 0, 1);

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseestudante->getEstudanteMatricula($valor)[0]->foto, 15, 61, 30, 'JPG');
        $pdf->Cell(189, 10, '', 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'NOME:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, $this->baseestudante->getEstudanteMatricula($valor)[0]->nome, 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'IDENTIFICAÇÃO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, $this->baseestudante->getEstudanteMatricula($valor)[0]->ndi, 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'GÉNERO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, strtoupper($this->baseestudante->getEstudanteMatricula($valor)[0]->genero), 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'DATA DE NASCIMENTO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, date('d/m/Y', strtotime($this->baseestudante->getEstudanteMatricula($valor)[0]->datanascimento)), 0, 1);



        $pdf->Cell(90, 5, '', 0, 1);




        $pdf->SetFillColor(222, 222, 222);
        $pdf->SetY($pdf->GetY() - 40);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Ln(40);
        $pdf->Cell(189, 1, ' DADOS DA MATRÍCULA', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 10);
        $pdf->Ln(9);

        $html = "";
        $html .= "<table border='1' >";
        $html .= "<tr>";
        /*   $html .= "<th><b>#</b></th>"; */
        $html .= "<th style='width: 5px;'><b>#</b></th>";
        $html .= "<th><b>Classe</b></th>";
        $html .= "<th><b>Curso</b></th>";
        $html .= "<th><b>Sala</b></th>";
        $html .= "<th><b>Turma</b></th>";
        $html .= "<th><b>Periodo</b></th>";
        $html .= "<th><b>Ano Lectivo</b></th>";
        $html .= "<th><b>Valor</b></th>";
        $html .= "</tr>";
        $cont = 1;


        $pdf->SetFont('Times', '', 8);
        foreach ($this->baseestudante->getEstudanteMatricula($valor) as $item) {
            $html .= "<tr>";
            /* $html .= "<td>" . ($cont++) . "</td>"; */
            $html .= "<td style='text-align:right; width: 10px;'>" . $cont++ . "</td>";
            $html .= "<td style='text-align:right;'>" .  $item->classe . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->curso . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->sala . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->turma . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->periodo . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->anolectivo . "</td>";
            $html .= "<td style='text-align:right;'>" . number_format(($item->valor), 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }

        $html .= "  </table>";



        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->SetFont('Times', '', 10);

        $pdf->Ln((115) - $pdf->GetY());
        $pdf->Cell(((190)), 5, "O (A) REPRESENTANTE", 0, 1, 'C');
        $pdf->Cell(((190)), 5, '______________________________________', 0, 1, 'C');
        $pdf->SetX(15);
        $pdf->write1DBarcode($valor, 'C128', '', '', '', 8, 0.39, $this->style, 'N');
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(19, $pdf->getY(), 193, $pdf->getY());
        $pdf->SetX(18);
        $pdf->Cell(30, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->SetX(84);
        $pdf->Cell(33, 5, "", 0, 0, 'L');
        $pdf->Cell(100, 5, "Operador: " . $this->basehome->getOperador($this->baseestudante->getEstudanteMatricula($valor)[0]->idoperador)[0]->operador, 0, 0, 'L');


        ////////////////////////////////////////////////////
        $pdf->Ln((140) - $pdf->GetY());
        $pdf->Line(0, $pdf->getY(), 220, $pdf->getY());
        $pdf->Ln((150) - $pdf->GetY());
        ////////////////////////////////////////////////////

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 20, 148, 22, 'JPG');

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(120, 5, '', 0, 0);
        $pdf->Cell(59, 7, 'RECIBO DE MATRÍCULA ', 0, 1);
        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(130, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0);
        $pdf->Cell(25, 5, 'Data:', 0, 0);
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(34, 5, date('d/m/Y', strtotime($this->baseestudante->getEstudanteMatricula($valor)[0]->datamatricula)), 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);


        $pdf->Cell(130, 5, $this->baseinstituicao->getAll()[0]->municipio . ', ANGOLA', 0, 0);
        $pdf->Cell(25, 5, 'Recibo Nº', 0, 0);
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(34, 5, $valor, 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(130, 5, 'Telefone: ' . $this->baseinstituicao->getAll()[0]->telefone, 0, 1);
        $pdf->Cell(130, 5, 'Email: ' . ($this->baseinstituicao->getAll()[0]->email), 0, 1);
        $pdf->Cell(130, 5, 'C. Postal: ' . $this->baseinstituicao->getAll()[0]->cpostal, 0, 1);
        $pdf->Cell(130, 5, 'Contribuinte Nº ' . $this->baseinstituicao->getAll()[0]->nif, 0, 1);

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseestudante->getEstudanteMatricula($valor)[0]->foto, 15, 202, 30, 'JPG');
        $pdf->Cell(189, 10, '', 0, 1);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'NOME:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, $this->baseestudante->getEstudanteMatricula($valor)[0]->nome, 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'IDENTIFICAÇÃO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, $this->baseestudante->getEstudanteMatricula($valor)[0]->ndi, 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'GÉNERO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, strtoupper($this->baseestudante->getEstudanteMatricula($valor)[0]->genero), 0, 1);

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(40, 5, '', 0, 0, 'L');
        $pdf->Cell(53, 5, 'DATA DE NASCIMENTO:  ', 0, 0, 'L');
        $pdf->SetFont('Times', '', 10, '', true);
        $pdf->Cell(90, 5, date('d/m/Y', strtotime($this->baseestudante->getEstudanteMatricula($valor)[0]->datanascimento)), 0, 1);



        $pdf->Cell(90, 5, '', 0, 1);




        $pdf->SetFillColor(222, 222, 222);
        $pdf->SetY($pdf->GetY() - 40);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Ln(40);
        $pdf->Cell(189, 1, ' DADOS DA MATRÍCULA', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 10);
        $pdf->Ln(9);

        $html = "";
        $html .= "<table border='1' >";
        $html .= "<tr>";
        /*   $html .= "<th><b>#</b></th>"; */
        $html .= "<th style='width: 5px;'><b>#</b></th>";
        $html .= "<th><b>Classe</b></th>";
        $html .= "<th><b>Curso</b></th>";
        $html .= "<th><b>Sala</b></th>";
        $html .= "<th><b>Turma</b></th>";
        $html .= "<th><b>Periodo</b></th>";
        $html .= "<th><b>Ano Lectivo</b></th>";
        $html .= "<th><b>Valor</b></th>";
        $html .= "</tr>";
        $cont = 1;


        $pdf->SetFont('Times', '', 8);
        foreach ($this->baseestudante->getEstudanteMatricula($valor) as $item) {
            $html .= "<tr>";
            /* $html .= "<td>" . ($cont++) . "</td>"; */
            $html .= "<td style='text-align:right; width: 10px;'>" . $cont++ . "</td>";
            $html .= "<td style='text-align:right;'>" .  $item->classe . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->curso . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->sala . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->turma . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->periodo . "</td>";
            $html .= "<td style='text-align:right;'>" . $item->anolectivo . "</td>";
            $html .= "<td style='text-align:right;'>" . number_format(($item->valor), 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }

        $html .= "  </table>";



        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Ln((183) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10);

        $pdf->Ln((253) - $pdf->GetY());
        $pdf->Cell(((190)), 5, "O (A) REPRESENTANTE", 0, 1, 'C');
        $pdf->Cell(((190)), 5, '______________________________________', 0, 1, 'C');
        $pdf->SetX(15);
        $pdf->write1DBarcode($valor, 'C128', '', '', '', 8, 0.39, $this->style, 'N');
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(19, $pdf->getY(), 193, $pdf->getY());
        $pdf->SetX(18);
        $pdf->Cell(30, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->SetX(84);
        $pdf->Cell(33, 5, "", 0, 0, 'L');
        $pdf->Cell(100, 5, "Operador: " . $this->basehome->getOperador($this->baseestudante->getEstudanteMatricula($valor)[0]->idoperador)[0]->operador, 0, 0, 'L');

        $pdf->Output();
    }

    //declaraco
    public function relactoriocertificado($idestudante)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Documento');
        $pdf->SetSubject('Documento');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/fundocert.jpg", 07, 10, 'JPG');
        $pdf->Image(base_url() . "assets/media/imagem/insignia.jpg", 98, 25, 15, 'JPG');

        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 27, '', 0, 1);
        $pdf->Cell(190, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(190, 5, 'MINISTÉRIO DA EDUCAÇÃO', 0, 1, 'C');
        $pdf->Cell(190, 5, $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');
        $pdf->Cell(189, 20, '', 0, 1);
        if ((count($this->baseestudante->getestudante($idestudante)) != 0)) {


            $pdf->SetFont('Times', 'B', 20, '', true);
            $pdf->SetTextColor(0, 0, 255);
            $pdf->Cell(189, 5, 'CERTIFICADO DE HABILITAÇÕES', 0, 1, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Ln(20);
            $pdf->SetFont('Times', '', 12);
            //        $pdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth)
            $pdf->SetX(25);
            $pdf->MultiCell(160, 30, $this->baserepresentante->listar()[0]->rep1 . " DIRECTOR DO " . $this->baseinstituicao->listar()[0]->nome . ", certifica  que " . $this->baseestudante->getestudante($idestudante)[0]->nome . " nascido(a) "
                . "aos " . date(date('d', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))) . " de " . ($this->basemes->getId(date(date('m', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))))[0]->designacao) . " "
                . "de " . date(date('Y', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))) . " "
                . "natural de " . $this->baseestudante->getestudante($idestudante)[0]->municipio . " "
                . "província de  " . $this->baseestudante->getestudante($idestudante)[0]->provincia . " "
                . "filho de " . $this->baseestudante->getestudante($idestudante)[0]->pai . " e de " . $this->baseestudante->getestudante($idestudante)[0]->mae . " Portador(a) da cédula pessoal/bilhete de identidade nº. " . $this->baseestudante->getestudante($idestudante)[0]->ndi . " . "
                . "Frequenta o Ensino " . (($this->baseestudante->getestudante($idestudante)[0]->classe < 7) ? "PRIMÁRIO" : (($this->baseestudante->getestudante($idestudante)[0]->classe < 10) ? "I CICLO" : "II CICLO")) . " (" . $this->baseestudante->getestudante($idestudante)[0]->classe . "ª Classe) "
                . (($this->baseestudante->getestudante($idestudante)[0]->classe > 9) ? "no curso " . $this->baseestudante->getestudante($idestudante)[0]->curso : "") . ","
                . "com o resultado final de Apto, no termo e pauta, arquivada neste liceu, com as seguintes Classificações:", 1, 'J');
            $pdf->SetFont('Times', '', 10, '', true);
            $pdf->Ln(10);
            $pdf->SetX(25);
            $pdf->Cell(80, 5, 'Disciplinas', 1, 0, 'C');
            $pdf->Cell(80, 5, $this->baseestudante->getestudante($idestudante)[0]->classe . 'ª Classe', 1, 1, 'C');



            $totalnota = 0;
            foreach ($this->basepauta->getDisciplinaPauta($this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $this->baseestudante->getestudante($idestudante)[0]->idperiodo) as $item1) {

                $pdf->SetX(25);
                $pdf->Cell(80, 5, $item1->disciplina, 1, 0, 'L');
                //CALCULO DOS TRIMESTRE-TRIMESTRE
                $mac = 0;
                $cpp = 0;
                $cap = 0;
                $cpe = 0;
                $ct = 0;
                $cf = 0;
                for ($contfase = 1; $contfase <= 3; $contfase++) {
                    $mac = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->mac)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->mac : 0;
                    $cpp = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->cpp)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->cpp : 0;
                    $ct += ((floatval($mac) + floatval($cpp)) / 2);
                }

                //FINAL
                $cap = ((floatval($ct)) / 3);
                $cpe = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, 3)[0]->cpe)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, 3)[0]->cpe : 0;
                $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));

                if ($cf >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(80 / 2, 5, number_format(floatval($cf), 1), 1, 1, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(80, 5, number_format(floatval($cf), 1), 1, 1, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);


                $totalnota += $cf;
            }



            $pdf->Ln((200) - $pdf->GetY());
            $pdf->SetFont('Times', '', 10, '', true);
            $pdf->SetX(25);
            $pdf->MultiCell(160, 10, "Por ser verdade, passou-se o presente Certificado que vai assinado pelo Director(a) e autenticado com o carimbo em uso neste estabelecimento de ensino.", 1, 'J');
            $pdf->Cell(190, 10, '', 0, 1, 'C');
            $pdf->Cell(190, 5, $this->baseinstituicao->getAll()[0]->municipio . ", aos " . date('d') . ' / ' . $this->basemes->getId(date('m'))[0]->designacao . ' / ' . date('Y'), 0, 1, 'C');
            $pdf->Cell(190, 20, '', 0, 1, 'C');
            $pdf->Cell(((190) / 2), 5, "Director (a) do Complexo Escolar	", 0, 0, 'C');
            $pdf->Cell(((190) / 2), 5, "Director (a)", 0, 1, 'C');
            $pdf->Cell(((190) / 2), 5, "______________________________________", 0, 0, 'C');
            $pdf->Cell(((190) / 2), 5, "______________________________________", 0, 1, 'C');
            $pdf->Cell(((190) / 2), 5, $this->baserepresentante->listar()[0]->rep1, 0, 0, 'C');
            $pdf->Cell(((190) / 2), 5, $this->baserepresentante->listar()[0]->rep2, 0, 1, 'C');
        }
        $pdf->Output();
    }

    //declaraco
    public function relactorioboletim($idestudante, $idfase)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Documento');
        $pdf->SetSubject('Documento');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/insignia.jpg", 98, 15, 15, 'JPG');

        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 16, '', 0, 1);
        $pdf->Cell(190, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(190, 5, 'MINISTÉRIO DA EDUCAÇÃO', 0, 1, 'C');
        $pdf->Cell(190, 5, $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');
        $pdf->Cell(189, 20, '', 0, 1);
        if ((count($this->baseestudante->getestudante($idestudante)) != 0)) {


            $pdf->SetFont('Times', 'B', 14, '', true);
            $pdf->Cell(189, 5, 'BOLETIM DE NOTA DO ' . (($idfase == 1) ? 'Iº' : (($idfase == 2) ? 'IIº' : 'IIIº')) . ' TRIMESTRE', 0, 1, 'C');

            $pdf->Ln(20);
            $pdf->SetFont('Times', '', 12);
            //        $pdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth)
            $pdf->MultiCell(190, 30, "           O  Complexo Escolar " . $this->baseinstituicao->getAll()[0]->nome . ", situado no município do " . $this->baseinstituicao->getAll()[0]->municipio . " da província de " . $this->baseinstituicao->getAll()[0]->provincia
                . " Declara que " . $this->baseestudante->getestudante($idestudante)[0]->nome . " nascido(a) "
                . "aos " . date(date('d', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))) . " de " . ($this->basemes->getId(date(date('m', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))))[0]->designacao) . " "
                . "de " . date(date('Y', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))) . " "
                . "natural de " . $this->baseestudante->getestudante($idestudante)[0]->municipio . " "
                . "província de  " . $this->baseestudante->getestudante($idestudante)[0]->provincia . " "
                . "filho de " . $this->baseestudante->getestudante($idestudante)[0]->pai . " e de " . $this->baseestudante->getestudante($idestudante)[0]->mae . " Portador(a) da cédula pessoal/bilhete de identidade nº. " . $this->baseestudante->getestudante($idestudante)[0]->ndi . " . "
                . "Frequenta o Ensino " . (($this->baseestudante->getestudante($idestudante)[0]->classe < 7) ? "PRIMÁRIO" : (($this->baseestudante->getestudante($idestudante)[0]->classe < 10) ? "I CICLO" : "II CICLO")) . " (" . $this->baseestudante->getestudante($idestudante)[0]->classe . "ª Classe) "
                . (($this->baseestudante->getestudante($idestudante)[0]->classe > 9) ? "no curso " . $this->baseestudante->getestudante($idestudante)[0]->curso : "") . ", tendo obtido os seguintes resultados:", 0, 'J');
            $pdf->SetFont('Times', '', 12, '', true);
            $pdf->Cell(189 / 2, 5, 'DISCIPLINA', 1, 0, 'C');
            $pdf->Cell(31, 5, 'MAC', 1, 0, 'C');
            $pdf->Cell(31, 5, 'CPP', 1, 0, 'C');
            $pdf->Cell(31, 5, 'CT', 1, 1, 'C');



            $totalnota = 0;
            foreach ($this->basepauta->getDisciplinaPauta($this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $this->baseestudante->getestudante($idestudante)[0]->idperiodo) as $item1) {

                $pdf->Cell(189 / 2, 5, $item1->disciplina, 1, 0, 'L');
                //CALCULO DOS TRIMESTRE-TRIMESTRE
                $mac = 0;
                $cpp = 0;
                $cap = 0;
                $cpe = 0;
                $ct = 0;
                $cf = 0;
                $auxcontfase = 0;
                for ($contfase = $idfase; $contfase <= $idfase; $contfase++) {
                    $mac = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->mac)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->mac : 0;
                    $cpp = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->cpp)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->cpp : 0;
                    $ct += ((floatval($mac) + floatval($cpp)) / 2);
                    ($mac > 0) ? $auxcontfase++ : $auxcontfase;
                }


                //FINAL
                if ($auxcontfase != 0)
                    $cap = ((floatval($ct)) / $auxcontfase);
                else
                    $cap = 0;

                //                $cpe = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, 3)[0]->cpe)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, 3)[0]->cpe : 0;
                $cf = (floatval($cap));

                if ($mac >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(31, 5, number_format(floatval($mac), 1), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(31, 5, number_format(floatval($mac), 1), 1, 0, 'C');
                }
                if ($cpp >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(31, 5, number_format(floatval($cpp), 1), 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(31, 5, number_format(floatval($cpp), 1), 1, 0, 'C');
                }
                if ($cf >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(31, 5, number_format(floatval($cf), 1), 1, 1, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(31, 5, number_format(floatval($cf), 1), 1, 1, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);


                //                $totalnota += $cf;
            }
            //            $totalnota = ($totalnota / count($this->basepauta->getDisciplinaPauta($this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $this->baseestudante->getestudante($idestudante)[0]->idperiodo)));
            //            if ($totalnota >= 10) {
            //                $pdf->SetTextColor(0, 0, 255);
            //                $pdf->Cell(15, 5, 'APTO', 1, 1, 'C');
            //            } else {
            //                $pdf->SetTextColor(255, 0, 0);
            //                $pdf->Cell(15, 5, 'N/APTO', 1, 1, 'C');
            //            }
            //            $pdf->SetTextColor(0, 0, 0);


            $pdf->Ln((240) - $pdf->GetY());
            $pdf->SetFont('Times', '', 8, '', true);
            $pdf->SetX(10);
            $pdf->Cell(((190)), 5, "O (A) Director (a) da Escola", 0, 1, 'C');
            $pdf->Cell(((190)), 5, '______________________________________', 0, 1, 'C');
            $pdf->Cell(((190)), 5, $this->baserepresentante->listar()[0]->rep1, 0, 1, 'C');
            $pdf->Cell(190, 15, '', 0, 1, 'C');
            $pdf->Cell(190, 5, date('d') . ' / ' . $this->basemes->getId(date('m'))[0]->designacao . ' / ' . date('Y'), 0, 1, 'C');
        }
        $pdf->Output();
    }

    //declaraco
    public function relactoriodeclaracaonota($idestudante)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Documento');
        $pdf->SetSubject('Documento');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/insignia.jpg", 98, 15, 15, 'JPG');

        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 16, '', 0, 1);
        $pdf->Cell(190, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(190, 5, 'MINISTÉRIO DA EDUCAÇÃO', 0, 1, 'C');
        $pdf->Cell(190, 5, $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');
        $pdf->Cell(189, 20, '', 0, 1);
        if ((count($this->baseestudante->getestudante($idestudante)) != 0)) {


            $pdf->SetFont('Times', 'B', 14, '', true);
            $pdf->Cell(189, 5, 'DECLARAÇÃO COM NOTA', 0, 1, 'C');

            $pdf->Ln(20);
            $pdf->SetFont('Times', '', 12);
            //        $pdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth)
            $pdf->MultiCell(190, 30, "           O  Complexo Escolar " . $this->baseinstituicao->getAll()[0]->nome . ", situado no município do " . $this->baseinstituicao->getAll()[0]->municipio . " da província de " . $this->baseinstituicao->getAll()[0]->provincia
                . " Declara que " . $this->baseestudante->getestudante($idestudante)[0]->nome . " nascido(a) "
                . "aos " . date(date('d', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))) . " de " . ($this->basemes->getId(date(date('m', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))))[0]->designacao) . " "
                . "de " . date(date('Y', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))) . " "
                . "natural de " . $this->baseestudante->getestudante($idestudante)[0]->municipio . " "
                . "província de  " . $this->baseestudante->getestudante($idestudante)[0]->provincia . " "
                . "filho de " . $this->baseestudante->getestudante($idestudante)[0]->pai . " e de " . $this->baseestudante->getestudante($idestudante)[0]->mae . " Portador(a) da cédula pessoal/bilhete de identidade nº. " . $this->baseestudante->getestudante($idestudante)[0]->ndi . " . "
                . "Frequenta o Ensino " . (($this->baseestudante->getestudante($idestudante)[0]->classe < 7) ? "PRIMÁRIO" : (($this->baseestudante->getestudante($idestudante)[0]->classe < 10) ? "I CICLO" : "II CICLO")) . " (" . $this->baseestudante->getestudante($idestudante)[0]->classe . "ª Classe) "
                . (($this->baseestudante->getestudante($idestudante)[0]->classe > 9) ? "no curso " . $this->baseestudante->getestudante($idestudante)[0]->curso : "") . ", tendo obtido os seguintes resultados:", 0, 'J');
            $pdf->SetFont('Times', '', 12, '', true);
            $pdf->Cell(189 / 2, 5, 'Disciplinas', 1, 0, 'C');
            $pdf->Cell(189 / 2, 5, 'Classificações', 1, 1, 'C');



            $totalnota = 0;
            foreach ($this->basepauta->getDisciplinaPauta($this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $this->baseestudante->getestudante($idestudante)[0]->idperiodo) as $item1) {

                $pdf->Cell(189 / 2, 5, $item1->disciplina, 1, 0, 'L');
                //CALCULO DOS TRIMESTRE-TRIMESTRE
                $mac = 0;
                $cpp = 0;
                $cap = 0;
                $cpe = 0;
                $ct = 0;
                $cf = 0;
                $auxcontfase = 1;
                for ($contfase = 1; $contfase <= 3; $contfase++) {
                    $mac = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->mac)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->mac : 0;
                    $cpp = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->cpp)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $contfase)[0]->cpp : 0;
                    $ct += ((floatval($mac) + floatval($cpp)) / 2);
                    ($mac > 0) ? $auxcontfase++ : $auxcontfase;
                }

                //FINAL
                if ($auxcontfase != 0)
                    $cap = ((floatval($ct)) / $auxcontfase);
                else
                    $cap = 0;
                //                $cpe = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, 3)[0]->cpe)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $item1->iddisciplina, $this->baseestudante->getestudante($idestudante)[0]->idperiodo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, 3)[0]->cpe : 0;
                $cf = (floatval($cap));

                if ($cf >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(189 / 2, 5, number_format(floatval($cf), 1), 1, 1, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(189 / 2, 5, number_format(floatval($cf), 1), 1, 1, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);


                //                $totalnota += $cf;
            }
            //            $totalnota = ($totalnota / count($this->basepauta->getDisciplinaPauta($this->baseestudante->getestudante($idestudante)[0]->idanolectivo, $this->baseestudante->getestudante($idestudante)[0]->idclasse, $this->baseestudante->getestudante($idestudante)[0]->idcurso, $this->baseestudante->getestudante($idestudante)[0]->idsala, $this->baseestudante->getestudante($idestudante)[0]->idturma, $this->baseestudante->getestudante($idestudante)[0]->idperiodo)));
            //            if ($totalnota >= 10) {
            //                $pdf->SetTextColor(0, 0, 255);
            //                $pdf->Cell(15, 5, 'APTO', 1, 1, 'C');
            //            } else {
            //                $pdf->SetTextColor(255, 0, 0);
            //                $pdf->Cell(15, 5, 'N/APTO', 1, 1, 'C');
            //            }
            //            $pdf->SetTextColor(0, 0, 0);


            $pdf->Ln((240) - $pdf->GetY());
            $pdf->SetFont('Times', '', 8, '', true);
            $pdf->SetX(10);
            $pdf->Cell(((190)), 5, "O (A) Director (a) da Escola", 0, 1, 'C');
            $pdf->Cell(((190)), 5, '______________________________________', 0, 1, 'C');
            $pdf->Cell(((190)), 5, $this->baserepresentante->listar()[0]->rep1, 0, 1, 'C');
            $pdf->Cell(190, 15, '', 0, 1, 'C');
            $pdf->Cell(190, 5, date('d') . ' / ' . $this->basemes->getId(date('m'))[0]->designacao . ' / ' . date('Y'), 0, 1, 'C');
        }
        $pdf->Output();
    }

    //declaraco
    public function relactoriodeclaracao($idestudante)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Documento');
        $pdf->SetSubject('Documento');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/insignia.jpg", 98, 15, 15, 'JPG');

        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 16, '', 0, 1);
        $pdf->Cell(190, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(190, 5, 'MINISTÉRIO DA EDUCAÇÃO', 0, 1, 'C');
        $pdf->Cell(190, 5, $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');
        $pdf->Cell(189, 20, '', 0, 1);
        if ((count($this->baseestudante->getestudante($idestudante)) != 0)) {


            $pdf->SetFont('Times', 'B', 14, '', true);
            $pdf->Cell(189, 5, 'DECLARAÇÂO SEM NOTA', 0, 1, 'C');

            $pdf->Ln(20);
            $pdf->SetFont('Times', '', 12);
            //        $pdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth)
            $pdf->MultiCell(190, 30, "           O  Complexo Escolar " . $this->baseinstituicao->getAll()[0]->nome . ", situado no município do " . $this->baseinstituicao->getAll()[0]->municipio . " da província de " . $this->baseinstituicao->getAll()[0]->provincia
                . " Declara que " . $this->baseestudante->getestudante($idestudante)[0]->nome . " nascido(a) "
                . "aos " . date(date('d', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))) . " de " . ($this->basemes->getId(date(date('m', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))))[0]->designacao) . " "
                . "de " . date(date('Y', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))) . " "
                . "natural de " . $this->baseestudante->getestudante($idestudante)[0]->municipio . " "
                . "província de  " . $this->baseestudante->getestudante($idestudante)[0]->provincia . " "
                . "filho de " . $this->baseestudante->getestudante($idestudante)[0]->pai . " e de " . $this->baseestudante->getestudante($idestudante)[0]->mae . " Portador(a) da cédula pessoal/bilhete de identidade nº. " . $this->baseestudante->getestudante($idestudante)[0]->ndi . " . "
                . "Frequenta o Ensino " . (($this->baseestudante->getestudante($idestudante)[0]->classe < 7) ? "PRIMÁRIO" : (($this->baseestudante->getestudante($idestudante)[0]->classe < 10) ? "I CICLO" : "II CICLO")) . " (" . $this->baseestudante->getestudante($idestudante)[0]->classe . "ª Classe) "
                . (($this->baseestudante->getestudante($idestudante)[0]->classe > 9) ? "no curso " . $this->baseestudante->getestudante($idestudante)[0]->curso : "") . ".", 0, 'J');
            $pdf->Ln((240) - $pdf->GetY());
            $pdf->SetFont('Times', '', 8, '', true);
            $pdf->SetX(10);
            $pdf->Cell(((190)), 5, "O (A) Director (a) da Escola", 0, 1, 'C');
            $pdf->Cell(((190)), 5, '______________________________________', 0, 1, 'C');
            $pdf->Cell(((190)), 5, $this->baserepresentante->listar()[0]->rep1, 0, 1, 'C');
            $pdf->Cell(190, 15, '', 0, 1, 'C');
            $pdf->Cell(190, 5, date('d') . ' / ' . $this->basemes->getId(date('m'))[0]->designacao . ' / ' . date('Y'), 0, 1, 'C');
        }
        $pdf->Output();
    }

    //Termo
    public function relactoriotermo($idestudante)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Documento');
        $pdf->SetSubject('Documento');
        $pdf->AddPage();

        //        $pdf->Image(base_url() . "assets/media/imagem/logo1.jpg", 78, 15, 50, 'JPG');
        $pdf->Image(base_url() . "assets/media/imagem/insignia.jpg", 98, 15, 15, 'JPG');

        $pdf->Ln(30);
        if ((count($this->baseestudante->getestudante($idestudante)) != 0)) {


            $pdf->SetFont('Times', 'B', 14, '', true);
            $pdf->Cell(189, 5, 'TERMO DE FREQUÊNCIA E EXAME', 0, 1, 'C');

            $pdf->Ln(20);
            $pdf->SetFont('Times', '', 12);
            //        $pdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth)

            $pdf->MultiCell(190, 30, 'Do Aluno ' . $this->baseestudante->getestudante($idestudante)[0]->nome . " "
                . "natural de " . $this->baseestudante->getestudante($idestudante)[0]->bairro . " "
                . "municipio de " . $this->baseestudante->getestudante($idestudante)[0]->municipio . " "
                . "província de  " . $this->baseestudante->getestudante($idestudante)[0]->provincia . " "
                . " nascido(a) aos " . date(date('d', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))) . " de " . ($this->basemes->getId(date(date('m', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))))[0]->designacao) . " "
                . "de " . date(date('Y', strtotime($this->baseestudante->getestudante($idestudante)[0]->data))) . ". "
                . "Filho de " . $this->baseestudante->getestudante($idestudante)[0]->pai . " e de " . $this->baseestudante->getestudante($idestudante)[0]->mae . " Portador(a) da cédula pessoal/bilhete de identidade nº. " . $this->baseestudante->getestudante($idestudante)[0]->ndi . ". "
                . "Ano Lectivo " . $this->baseestudante->getestudante($idestudante)[0]->anolectivo . " Ensino " . (($this->baseestudante->getestudante($idestudante)[0]->classe < 7) ? "PRIMÁRIO" : (($this->baseestudante->getestudante($idestudante)[0]->classe < 10) ? "I CICLO" : "II CICLO")) . " "
                . "Classe " . $this->baseestudante->getestudante($idestudante)[0]->classe . " Turma " . $this->baseestudante->getestudante($idestudante)[0]->turma . " "
                . "Nº " . $this->baseestudante->getestudante($idestudante)[0]->nprocesso, 0, 'J');

            $pdf->SetFont('Times', 'B', 10);

            $pdf->Cell(64, 10, 'DISCIPLINA', 1, 0, 'C');
            $pdf->Cell(30, 5, 'I TRIMESTRE', 1, 0, 'C');
            $pdf->Cell(30, 5, 'II TRIMESTRE', 1, 0, 'C');
            $pdf->Cell(30, 5, 'III TRIMESTRE', 1, 0, 'C');
            $pdf->Cell(10, 10, 'CAP', 1, 0, 'C');
            $pdf->Cell(15, 10, 'CPE/CE', 1, 0, 'C');
            $pdf->Cell(10, 10, 'CF', 1, 1, 'C');

            $pdf->SetXY($pdf->GetX() + 64, $pdf->GetY() - 5);
            $pdf->Cell(10, 5, 'CT', 1, 0, 'C');
            $pdf->Cell(10, 5, 'F.J', 1, 0, 'C');
            $pdf->Cell(10, 5, 'F .inj', 1, 0, 'C');
            $pdf->Cell(10, 5, 'CT', 1, 0, 'C');
            $pdf->Cell(10, 5, 'F.J', 1, 0, 'C');
            $pdf->Cell(10, 5, 'F .inj', 1, 0, 'C');
            $pdf->Cell(10, 5, 'CT', 1, 0, 'C');
            $pdf->Cell(10, 5, 'F.J', 1, 0, 'C');
            $pdf->Cell(10, 5, 'F. inj', 1, 1, 'C');
            $pdf->SetFont('Times', '', 10);



            $totalnota = 0;

            //Variaveis
            $anolectivoaux = $this->baseestudante->getestudante($idestudante)[0]->idanolectivo;
            $classeaux = $this->baseestudante->getestudante($idestudante)[0]->idclasse;
            $cursoaux = $this->baseestudante->getestudante($idestudante)[0]->idcurso;
            $salaaux = $this->baseestudante->getestudante($idestudante)[0]->idsala;
            $turmaaux = $this->baseestudante->getestudante($idestudante)[0]->idturma;
            $periodoaux = $this->baseestudante->getestudante($idestudante)[0]->idperiodo;
            foreach ($this->basepauta->getDisciplinaPauta($anolectivoaux, $classeaux, $cursoaux, $salaaux, $turmaaux, $periodoaux) as $item1) {

                $pdf->Cell(64, 5, $item1->disciplina, 1, 0, 'L');
                //CALCULO DOS TRIMESTRE-TRIMESTRE
                $mac = 0;
                $cpp = 0;
                $cap = 0;
                $cpe = 0;
                $ct = 0;
                $cf = 0;
                for ($contfase = 1; $contfase <= 3; $contfase++) {
                    $mac = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $anolectivoaux, $item1->iddisciplina, $periodoaux, $classeaux, $cursoaux, $salaaux, $turmaaux, $contfase)[0]->mac)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $anolectivoaux, $item1->iddisciplina, $periodoaux, $classeaux, $cursoaux, $salaaux, $turmaaux, $contfase)[0]->mac : 0;
                    $cpp = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $anolectivoaux, $item1->iddisciplina, $periodoaux, $classeaux, $cursoaux, $salaaux, $turmaaux, $contfase)[0]->cpp)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $anolectivoaux, $item1->iddisciplina, $periodoaux, $classeaux, $cursoaux, $salaaux, $turmaaux, $contfase)[0]->cpp : 0;
                    $ct = ((floatval($mac) + floatval($cpp)) / 2);

                    if ($ct >= 10) {
                        $pdf->SetTextColor(0, 0, 255);
                        $pdf->Cell(10, 5, $ct, 1, 0, 'C');
                    } else {
                        $pdf->SetTextColor(255, 0, 0);
                        $pdf->Cell(10, 5, $ct, 1, 0, 'C');
                    }

                    if ($mac >= 10) {
                        $pdf->SetTextColor(0, 0, 255);
                        $pdf->Cell(10, 5, '', 1, 0, 'C');
                    } else {
                        $pdf->SetTextColor(255, 0, 0);
                        $pdf->Cell(10, 5, '', 1, 0, 'C');
                    }

                    if ($cpp >= 10) {
                        $pdf->SetTextColor(0, 0, 255);
                        $pdf->Cell(10, 5, '', 1, 0, 'C');
                    } else {
                        $pdf->SetTextColor(255, 0, 0);
                        $pdf->Cell(10, 5, '', 1, 0, 'C');
                    }

                    $pdf->SetTextColor(0, 0, 0);
                }

                //FINAL
                $cap = ((floatval($ct)) / 3);
                $cpe = (isset($this->basepauta->getNotaMiniPautaIdestudante($idestudante, $anolectivoaux, $item1->iddisciplina, $periodoaux, $classeaux, $cursoaux, $salaaux, $turmaaux, 3)[0]->cpe)) ? $this->basepauta->getNotaMiniPautaIdestudante($idestudante, $anolectivoaux, $item1->iddisciplina, $periodoaux, $classeaux, $cursoaux, $salaaux, $turmaaux, 3)[0]->cpe : 0;

                switch ($this->baseestudante->getestudante($idestudante)[0]->classe) {
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
                        $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));
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
                        $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));
                        break;
                    case 11:
                        $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));
                        break;
                    case 12:
                        $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));
                        break;
                    case 13:
                        $cf = ((floatval($cap) * 0.4) + (floatval($cpe) * 0.6));
                        break;
                    default:
                        $cf = $cap;
                        break;
                        //
                }

                if ($cap >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, $cap, 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, $cap, 1, 0, 'C');
                }

                if ($cpe >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(15, 5, $cpe, 1, 0, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(15, 5, $cpe, 1, 0, 'C');
                }

                if ($cf >= 10) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, $cf, 1, 1, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(10, 5, $cf, 1, 1, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);

                $totalnota += $cf;
            }

            $pdf->Cell(64, 5, 'COMPORTAMENTO', 1, 0, 'L');
            $pdf->Cell(30, 5, '', 1, 0, 'C');
            $pdf->Cell(30, 5, '', 1, 0, 'C');
            $pdf->Cell(30, 5, '', 1, 0, 'C');
            $pdf->Cell(35, 5, '', 1, 1, 'C');

            //            $totalnota = ($totalnota / count($this->basepauta->getDisciplinaPauta($anolectivoaux, $classeaux, $cursoaux, $salaaux, $turmaaux, $periodoaux)));
            //            if ($totalnota >= 10) {
            //                $pdf->SetTextColor(0, 0, 255);
            //                $pdf->Cell(15, 5, 'APTO', 1, 1, 'C');
            //            } else {
            //                $pdf->SetTextColor(255, 0, 0);
            //                $pdf->Cell(15, 5, 'N/APTO', 1, 1, 'C');
            //            }
            //            $pdf->SetTextColor(0, 0, 0);


            $pdf->Ln((210) - $pdf->GetY());
            $pdf->Cell(190, 5, 'OBS:', 0, 1, 'L');

            $pdf->Ln((240) - $pdf->GetY());
            $pdf->SetFont('Times', '', 8, '', true);
            $pdf->SetX(10);
            $pdf->Cell(((190) / 2), 5, 'O CONSELHO DE NOTAS,', 0, 0, 'L');
            $pdf->Cell(((190) / 2), 5, "O (A) Director (a) da Escola", 0, 0, 'C');
            $pdf->Cell(((190) / 2), 5, '', 0, 1, 'C');
            $pdf->Cell(((190) / 2), 5, '1.______________________________________', 0, 0, 'L');
            $pdf->Cell(((190) / 2), 5, '', 0, 0, 'C');
            $pdf->Cell(((190) / 2), 5, '', 0, 1, 'C');
            $pdf->Cell(((190) / 2), 5, '2.______________________________________', 0, 0, 'L');
            $pdf->Cell(((190) / 2), 5, '______________________________________', 0, 0, 'C');
            $pdf->Cell(((190) / 2), 5, '______________________________________', 0, 1, 'C');
            $pdf->Cell(((190) / 2), 5, '3.______________________________________', 0, 0, 'L');
            $pdf->Cell(((190) / 2), 5, $this->baserepresentante->listar()[0]->rep1, 0, 1, 'C');
            $pdf->Cell(190, 10, '', 0, 1, 'C');
            $pdf->Cell(190, 5, date('d') . ' / ' . $this->basemes->getId(date('m'))[0]->designacao . ' / ' . date('Y'), 0, 1, 'C');
        }
        $pdf->Output();
    }
}
