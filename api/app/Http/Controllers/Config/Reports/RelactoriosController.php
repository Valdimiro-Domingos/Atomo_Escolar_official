<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* Autor: Hilquias Chitazo 19/01/2019 17:12
 * Descrição: Construção da controller Sistema
 */

class RelactorioController extends CI_Controller
{
    
    //Funcao que instacia a classe
    public function __construct()
    {
        parent::__construct();
        $this->verificar_sessao();
        require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
        $this->load->model('Sistema/FacturaModel', 'basefactura');
        $this->load->model('Sistema/HomeModel', 'basehome');
        $this->load->model('Sistema/ProdutoServicoModel', 'baseservico');
        $this->load->model('Sistema/PessoaModel', 'basepessoa');
        $this->load->model('Sistema/ClienteModel', 'basecliente');
        $this->load->model('Sistema/InstituicaoModel', 'baseinstituicao');
        $this->load->model('Sistema/FornecedorModel', 'basefornecedor');
        $this->load->model('Sistema/VendaModel', 'basevenda');
        $this->load->model('Sistema/CompraModel', 'basecompra');
        $this->load->model('Sistema/SalarioModel', 'basesalario');
        $this->load->model('Sistema/InscricaoModel', 'baseinscricao');
        $this->load->model('Sistema/BancoModel', 'basebanco');
        $this->load->model('Sistema/SalarioSubDescModel', 'basesalario_sub_desc');
        $this->load->model('Sistema/FuncionarioModel', 'basefuncionario');
        $this->load->model('Sistema/Sub_DescModel', 'basesub_desc');
        $this->load->model('Sistema/MesModel', 'basemes');
        $this->load->model('Sistema/MatriculaModel', 'basematricula');
        $this->load->model('Sistema/RepresentanteModel', 'baserepresentante');
        $this->load->model('Sistema/AnoLectivoModel', 'baseanolectivo');
        $this->load->model('Sistema/PautaModel', 'basepauta');
        $this->load->model('Sistema/NotaModel', 'basenota');
        $this->load->model('Sistema/ClasseModel', 'baseclasse');
        $this->load->model('Sistema/SalaModel', 'basesala');
        $this->load->model('Sistema/CursoModel', 'basecurso');
        $this->load->model('Sistema/DisciplinaModel', 'basedisciplina');
        $this->load->model('Sistema/FaseModel', 'basefase');
        $this->load->model('Sistema/PeriodoModel', 'baseperiodo');
        $this->load->model('Sistema/TurmaModel', 'baseturma');
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

    public function mecanograficobanco($valor)
    {
        if ($valor > 1000) {
            return $valor;
        } elseif ($valor > 100) {
            return "0" . $valor;
        } elseif ($valor > 10) {
            return "00" . $valor;
        } else {
            return "000" . $valor;
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

    public function inscricao_matricula()
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetAuthor('MeuGenio');
        $pdf->SetTitle('Relatório');
        $pdf->SetSubject('Relatório');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 20, 8, 22, 'JPG');

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(205, 5, '', 0, 0);
        $pdf->Cell(59, 7, 'RELATÓRIO', 0, 1);
        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0);
        $pdf->Cell(15, 5, 'Data:', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, date('d/m/Y'), 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->municipio . ', ANGOLA', 0, 0);
        $pdf->Cell(25, 5, '', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, 'Telefone: ' . $this->baseinstituicao->getAll()[0]->telefone, 0, 1);
        $pdf->Cell(130, 5, 'Email: ' . ($this->baseinstituicao->getAll()[0]->email), 0, 1);
        $pdf->Cell(130, 5, 'C. Postal: ' . $this->baseinstituicao->getAll()[0]->cpostal, 0, 1);
        $pdf->Cell(130, 5, 'Contribuinte Nº ' . $this->baseinstituicao->getAll()[0]->nif, 0, 1);

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(277, 20, '', 0, 1);
        $pdf->Cell(277, 5, 'RELATÓRIO DE PAGAMENTO DE ' . $this->input->post("tipo") . ' DE ' . trim(explode('-', $this->input->post("data"))[0]) . ' Á ' .
            trim(explode('-', $this->input->post("data"))[1]), 0, 1, 'C');
        $pdf->SetFillColor(222, 222, 222);
        // End Title
        $pdf->SetY($pdf->GetY() - 30);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 14);
        $pdf->Ln(40);
        $pdf->Cell(277, 1, ' DADOS DO PAGAMENTO', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 12);
        $pdf->Ln(9);
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(5, 5, '#', 1, 0);
        $pdf->Cell(120, 5, 'Aluno', 1, 0);
        $pdf->Cell(50, 5, 'Identificação', 1, 0);
        $pdf->Cell(47, 5, 'Classe', 1, 0);
        $pdf->Cell(25, 5, 'Data', 1, 0);
        $pdf->Cell(30, 5, 'Total', 1, 1);

        $pdf->SetFont('Times', '', 10);
        $cont = 1;

        //Variaveis 
        $subtotal = 0;
        $imposto = 0;
        $desconto = 0;
        $total = 0;

        $datainicio = explode('-', $this->input->post("data"))[0];
        $datafim = explode('-', $this->input->post("data"))[1];
        if ($this->input->post("tipo") == 'MATRICULA') {
            foreach ($this->basematricula->getPeriodo(
                (trim(explode('/', $datainicio)[2]) . '-' . trim(explode('/', $datainicio)[1]) . '-' . trim(explode('/', $datainicio)[0])),
                (trim(explode('/', $datafim)[2]) . '-' . trim(explode('/', $datafim)[1]) . '-' . trim(explode('/', $datafim)[0]))
            )
                as $item) {
                $pdf->Cell(5, 5, $cont++, 1, 0);
                $pdf->Cell(120, 5, $item->nome, 1, 0);
                $pdf->Cell(50, 5, $item->ndi, 1, 0);
                $pdf->Cell(47, 5, $item->classe, 1, 0);
                $pdf->Cell(25, 5, date('d/m/Y', strtotime($item->datamatricula)), 1, 0, 'C');
                $pdf->Cell(30, 5, number_format($item->valor, 2, ',', '.') . '00 (AKZ)', 1, 1, 'R');

                $subtotal += $item->valor;
                $imposto += 0;
                $desconto += 0;
                $total += $item->valor;
            }
        } else {
            foreach ($this->baseinscricao->getPeriodo(
                (trim(explode('/', $datainicio)[2]) . '-' . trim(explode('/', $datainicio)[1]) . '-' . trim(explode('/', $datainicio)[0])),
                (trim(explode('/', $datafim)[2]) . '-' . trim(explode('/', $datafim)[1]) . '-' . trim(explode('/', $datafim)[0]))
            )
                as $item) {
                $pdf->Cell(5, 5, $cont++, 1, 0);
                $pdf->Cell(120, 5, $item->nome, 1, 0);
                $pdf->Cell(50, 5, $item->ndi, 1, 0);
                $pdf->Cell(47, 5, $item->classe, 1, 0);
                $pdf->Cell(25, 5, date('d/m/Y', strtotime($item->data1)), 1, 0, 'C');
                $pdf->Cell(30, 5, number_format($item->valor, 2, ',', '.') . '00 (AKZ)', 1, 1, 'R');

                $subtotal += $item->valor;
                $imposto += 0;
                $desconto += 0;
                $total += $item->valor;
            }
        }

        //Corpo-Total
        $pdf->Cell(277, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->Cell(30, 5, 'Subtotal', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $subtotal . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Imposto', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $imposto . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Desconto', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $desconto . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $total . '.00(AKZ)', 1, 1, 'R');

        $pdf->Ln((183) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(10, $pdf->getY(), 287, $pdf->getY());
        $pdf->Cell(170, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->Cell(33, 5, "Mais informações:", 0, 0, 'L');
        $pdf->SetTextColor(50, 150, 255);
        $pdf->Cell(100, 5, "atomotecnologia.com", 0, 1, 'L');
        $pdf->Output();
    }

    public function relactorioaluno()
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Aluno');
        $pdf->SetSubject('Aluno');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 95, 15, 20, 'JPG');

        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 12, '', 0, 1);
        $pdf->Cell(190, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(190, 5, 'MINISTÉRIO DA EDUCAÇÃO', 0, 1, 'C');
        $pdf->Cell(190, 5, $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');

        $pdf->Cell(189, 20, '', 0, 1);
        $pdf->SetFont('Times', 'B', 14, '', true);
        $pdf->Cell(189, 5, 'LISTA NOMINAL', 0, 1, 'C');

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 12, '', 0, 1);
        if (intval($this->input->post("classe")) > 9) {
            $pdf->Cell(190, 5, 'CURSO: ' . $this->basecurso->getId($this->input->post("curso"))[0]->designacao, 0, 1);
        }
        $pdf->Cell(190, 5, 'CLASSE: ' . $this->baseclasse->getId($this->input->post("classe"))[0]->designacao, 0, 1);
        $pdf->Cell(190, 5, 'TURMA: ' . $this->baseturma->getId($this->input->post("turma"))[0]->designacao, 0, 1);
        $pdf->Cell(190, 5, 'SALA: ' . $this->basesala->getId($this->input->post("sala"))[0]->designacao, 0, 1);
        $pdf->Cell(190, 5, 'PERÍODO: ' . $this->baseperiodo->getId($this->input->post("periodo"))[0]->designacao, 0, 1);
        $pdf->Cell(190, 5, 'ANO LECTIVO ' . $this->baseanolectivo->getId($this->input->post("idanolectivo"))[0]->designacao, 0, 1);


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
        foreach ($this->basematricula->getestudantes($this->input->post("idanolectivo"), $this->input->post("classe"), $this->input->post("curso"), $this->input->post("turma"), $this->input->post("sala"), $this->input->post("periodo")) as $item) {
            $pdf->Cell(5, 5, $cont++, 1, 0);
            $pdf->Cell(80, 5, $item->nome, 1, 0);
            $pdf->Cell(55, 5, strtoupper($item->ndi), 1, 0);
            $pdf->Cell(25, 5, strtoupper($item->genero), 1, 0);
            $pdf->Cell(24, 5, strtoupper($item->estado), 1, 1);
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
        $pdf->Output();
        $pdf->Output();
    }

    public function relactoriodadospropina()
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('Aluno');
        $pdf->SetSubject('Aluno');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 95, 15, 20, 'JPG');

        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 12, '', 0, 1);
        $pdf->Cell(190, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(190, 5, 'MINISTÉRIO DA EDUCAÇÃO', 0, 1, 'C');
        $pdf->Cell(190, 5, $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');

        $pdf->Cell(189, 20, '', 0, 1);
        $pdf->SetFont('Times', 'B', 14, '', true);
        $pdf->Cell(189, 5, 'RELATÓRIO DE PROPINA', 0, 1, 'C');

        $pdf->SetFont('Times', 'B', 10, '', true);
        $pdf->Cell(190, 12, '', 0, 1);
        if (intval($this->input->post("classe")) > 9) {
            $pdf->Cell(190, 5, 'CURSO: ' . $this->basecurso->getId($this->input->post("curso"))[0]->designacao, 0, 1);
        }
        $pdf->Cell(190, 5, 'CLASSE: ' . $this->baseclasse->getId($this->input->post("classe"))[0]->designacao, 0, 1);
        $pdf->Cell(190, 5, 'TURMA: ' . $this->baseturma->getId($this->input->post("turma"))[0]->designacao, 0, 1);
        $pdf->Cell(190, 5, 'SALA: ' . $this->basesala->getId($this->input->post("sala"))[0]->designacao, 0, 1);
        $pdf->Cell(190, 5, 'PERÍODO: ' . $this->baseperiodo->getId($this->input->post("periodo"))[0]->designacao, 0, 1);
        $pdf->Cell(190, 5, 'ANO LECTIVO ' . $this->baseanolectivo->getId($this->input->post("idanolectivo"))[0]->designacao, 0, 1);


        $pdf->SetFillColor(222, 222, 222);

        // End Title

        $pdf->SetY($pdf->GetY() - 28);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Ln(40);
        $pdf->Cell(189, 1, 'DADOS DOS ESTUDANTES', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 10);
        $pdf->Ln(9);
        $pdf->SetFont('Times', 'B', 8);

        $pdf->Cell(5, 5, '#', 1, 0);
        $pdf->Cell(64, 5, 'NOME COMPLETO', 1, 0);
        $pdf->Cell(10, 5, 'JAN', 1, 0);
        $pdf->Cell(10, 5, 'FEV', 1, 0);
        $pdf->Cell(10, 5, 'MAR', 1, 0);
        $pdf->Cell(10, 5, 'ABR', 1, 0);
        $pdf->Cell(10, 5, 'MAI', 1, 0);
        $pdf->Cell(10, 5, 'JUN', 1, 0);
        $pdf->Cell(10, 5, 'JUL', 1, 0);
        $pdf->Cell(10, 5, 'AGO', 1, 0);
        $pdf->Cell(10, 5, 'SET', 1, 0);
        $pdf->Cell(10, 5, 'OUT', 1, 0);
        $pdf->Cell(10, 5, 'NOV', 1, 0);
        $pdf->Cell(10, 5, 'DEZ', 1, 1);

        $pdf->SetFont('Times', '', 8);
        $cont = 1;
        foreach ($this->basematricula->getestudantes($this->input->post("idanolectivo"), $this->input->post("classe"), $this->input->post("curso"), $this->input->post("turma"), $this->input->post("sala"), $this->input->post("periodo")) as $item) {
            $pdf->Cell(5, 5, $cont++, 1, 0);
            $pdf->Cell(64, 5, $item->nome, 1, 0);

            foreach ($this->basematricula->relactorioPropina($item->id) as $valor1) {
                if ($valor1->status == 'PAGO') {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(10, 5, strtoupper('PAGO'), 1, 0, 'C');
                } else {
                    $pdf->Cell(10, 5, strtoupper('-'), 1, 0, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);
            }
            $pdf->Cell(0, 5, '', 0, 1);
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
        $pdf->Output();
    }

    public function relactoriopassealuno($valor)
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('PASSE');
        $pdf->SetSubject('PASSE');
        $pdf->AddPage();

        $pdf->Ln(10);
        $pdf->SetFont('Times', 'B', 25);
        //$pdf->Cell(277, 5, '', 1, 1);
        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, ((277 / 2) - 13), 15, 50, 'JPG');
        $pdf->Image(base_url() . "assets/media/imagem/" . $this->basematricula->getOneId($valor)[0]->foto, 227, 90, 50, 'JPG');

        $pdf->Cell(35, 0, '', 0, 1);
        $pdf->Ln(30);
        $pdf->Cell(277, 5, 'REPÚBLICA DE ANGOLA', 0, 1, 'C');
        $pdf->Cell(277, 8, $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');
        $pdf->SetFillColor(222, 222, 222);
        $pdf->Cell(277, 5, ' CARTÃO DE ESTUDANTE ', 0, 1, 'C', true);
        $pdf->Ln(10);
        $pdf->Cell(50, 5, ' NOME:', 0, 0, 'L');
        $pdf->Cell(100, 5, $this->basematricula->getOneId($valor)[0]->nome, 0, 1, 'L');

        $pdf->Cell(50, 5, ' Nº:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basematricula->getOneId($valor)[0]->idestudante, 0, 1, 'L');

        $pdf->Cell(50, 5, ' ANO:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basematricula->getOneId($valor)[0]->anolectivo, 0, 1, 'L');

        $pdf->Cell(50, 5, ' CLASSE:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basematricula->getOneId($valor)[0]->classe, 0, 1, 'L');

        $pdf->Cell(50, 5, ' SALA:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basematricula->getOneId($valor)[0]->sala, 0, 1, 'L');

        $pdf->Cell(50, 5, ' TURMA:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basematricula->getOneId($valor)[0]->turma, 0, 1, 'L');

        $pdf->Cell(50, 5, ' PERIODO:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basematricula->getOneId($valor)[0]->periodo, 0, 1, 'L');
        $pdf->SetFont('Times', 'B', 15);


        $pdf->SetY(140);
        $pdf->SetX(220);
        $pdf->write1DBarcode($valor, 'C128', '', '', '', 20, 0.73, $this->style, 'N');
        $pdf->Cell(205, 5, "", 0, 1, 'C');
        $pdf->Cell(205, 5, "", 0, 0, 'C');
        $pdf->Cell(77, 5, "O (A) DIRECTOR", 0, 1, 'C');
        //$pdf->Cell(277, 5, '______________________________________', 0, 1, 'C');
        $pdf->Cell(205, 5, "", 0, 0, 'C');
        $pdf->Cell(77, 5, $this->baserepresentante->listar()[0]->rep1, 0, 1, 'C');
        $pdf->Output();
    }

    public function relactoriopassefuncionario($valor)
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetAuthor('Hilquias');
        $pdf->SetTitle('PASSE');
        $pdf->SetSubject('PASSE');
        $pdf->AddPage();

        $pdf->Ln(10);
        $pdf->SetFont('Times', 'B', 25);
        //$pdf->Cell(277, 5, '', 1, 1);
        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, ((277 / 2) - 13), 15, 50, 'JPG');
        $pdf->Image(base_url() . "assets/media/imagem/" . $this->basefuncionario->getId($valor)[0]->foto, 227, 90, 50, 'JPG');

        $pdf->Cell(35, 0, '', 0, 1);
        $pdf->Ln(30);
        $pdf->Cell(277, 5, $this->baseinstituicao->listar()[0]->nome, 0, 1, 'C');
        $pdf->Cell(277, 8, '', 0, 1, 'C');
        $pdf->SetFillColor(222, 222, 222);
        $pdf->Cell(277, 5, 'PASSE DE FUNCIONÁRIO', 0, 1, 'C', true);
        $pdf->Ln(10);
        $pdf->Cell(70, 5, ' NOME:', 0, 0, 'L');
        $pdf->Cell(100, 5, $this->basefuncionario->getId($valor)[0]->nome, 0, 1, 'L');

        $pdf->Cell(70, 5, ' BIº:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basefuncionario->getId($valor)[0]->ndi, 0, 1, 'L');

        $pdf->Cell(70, 5, ' GÉNERO:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basefuncionario->getId($valor)[0]->genero, 0, 1, 'L');

        $pdf->Cell(70, 5, ' CARGO:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basefuncionario->getId($valor)[0]->cargo, 0, 1, 'L');

        $pdf->Cell(70, 5, ' RESIDÊNCIA:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basefuncionario->getId($valor)[0]->bairro, 0, 1, 'L');

        $pdf->Cell(70, 5, ' TELEFONE:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basefuncionario->getId($valor)[0]->telefone, 0, 1, 'L');

        $pdf->Cell(70, 5, ' EMAIL:', 0, 0, 'L');

        $pdf->Cell(100, 5, $this->basefuncionario->getId($valor)[0]->email, 0, 1, 'L');
        $pdf->SetFont('Times', 'B', 15);

        $pdf->SetY(140);
        $pdf->SetX(220);
        $pdf->write1DBarcode($valor, 'C128', '', '', '', 20, 0.73, $this->style, 'N');
        $pdf->Cell(205, 5, "", 0, 1, 'C');
        $pdf->Cell(205, 5, "", 0, 0, 'C');
        $pdf->Cell(77, 5, "O (A) DIRECTOR", 0, 1, 'C');
        //$pdf->Cell(277, 5, '______________________________________', 0, 1, 'C');
        $pdf->Cell(205, 5, "", 0, 0, 'C');
        $pdf->Cell(77, 5, $this->baserepresentante->listar()[0]->rep1, 0, 1, 'C');
        $pdf->Output();
    }

    public function relactoriopautaexcel($valor)
    {
        $dados = array(
            "idclasse" => $this->basepauta->getID($valor)[0]->idclasse,
            "idcurso" => $this->basepauta->getID($valor)[0]->idcurso,
            "idturma" => $this->basepauta->getID($valor)[0]->idturma,
            "idperiodo" => $this->basepauta->getID($valor)[0]->idperiodo,
            "iddisciplina" => $this->basepauta->getID($valor)[0]->iddisciplina,
            "idfase" => $this->basepauta->getID($valor)[0]->idfase,
        );

        // Definimos o nome do arquivo que será exportado
        $arquivo = 'ficheiro.xls';
        // Criamos uma tabela HTML com o formato da planilha
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td colspan="5">PAUTA DE ' . $this->basedisciplina->getID($dados["iddisciplina"])[0]->designacao . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td><b>ID</b></td>';
        $html .= '<td><b>NOME COMPLETO</b></td>';
        $html .= '<td><b>MAC</b></td>';
        $html .= '<td><b>CPP</b></td>';
        $html .= '<td><b>CT</b></td>';
        $html .= '<td><b>ESTADO</b></td>';
        $html .= '</tr>';

        $cont = 1;

        foreach ($this->basenota->getnotas($valor) as $item) {
            if ($item->nome != null) {
                $html .= '<tr>';
                $html .= '<td>' . $cont++ . '</td>';
                $html .= '<td>' . $item->nome . '</td>';
                $html .= '<td>' . $item->mac . '</td>';
                $html .= '<td>' . $item->cpp . '</td>';
                $html .= '<td>' . ((floatval($item->mac) + floatval($item->cpp)) / 2) . '</td>';
                if (floatval(((floatval($item->mac) + floatval($item->cpp)) / 2)) >= 10.0)
                    $html .= '<td>APTO</td>';
                else
                    $html .= '<td>NAO APTO</td>';

                $html .= '</tr>';
            }
        }

        $html .= '</table>';
        $html = $html;

        // Configurações header para forçar o download
        header("Expires: Mon, 07 Jul 2016 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
        header("Content-Description: PHP Generated Data");
        // Envia o conteúdo do arquivo
        echo $html;
        exit;
    }

    //Passe
    public function relactoriopautapdf($valor)
    {
        $dados = array(
            "idclasse" => $this->basepauta->getID($valor)[0]->idclasse,
            "idcurso" => $this->basepauta->getID($valor)[0]->idcurso,
            "idturma" => $this->basepauta->getID($valor)[0]->idturma,
            "idperiodo" => $this->basepauta->getID($valor)[0]->idperiodo,
            "iddisciplina" => $this->basepauta->getID($valor)[0]->iddisciplina,
            "idfase" => $this->basepauta->getID($valor)[0]->idfase,
        );

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetAuthor('MeuGenio');
        $pdf->SetTitle('Pauta');
        $pdf->SetSubject('Pauta');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 135, 3, 22, 'JPG');
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(277, 5, '', 0, 1);
        $pdf->Cell(277, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0, 'C');

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(277, 20, '', 0, 1);
        $pdf->Cell(277, 5, 'PAUTA DE ' . $this->basedisciplina->getID($dados["iddisciplina"])[0]->designacao, 0, 1, 'C');
        $pdf->SetFillColor(222, 222, 222);
        // End Title
        $pdf->SetY($pdf->GetY() - 30);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 14);
        $pdf->Ln(40);
        $pdf->Cell(
            277,
            1,
            $this->basefase->getID($dados["idfase"])[0]->designacao
                . " - " . $dados["idclasse"] . " CLASSE",
            1,
            0,
            'L',
            true
        );
        $pdf->SetFont('Times', '', 12);
        $pdf->Ln(9);
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(5, 5, '#', 1, 0);
        $pdf->Cell(97, 5, 'NOME COMPLETO', 1, 0);
        $pdf->Cell(45, 5, 'MAC', 1, 0, 'C');
        $pdf->Cell(45, 5, 'CPP', 1, 0, 'C');
        $pdf->Cell(45, 5, 'CT', 1, 0, 'C');
        $pdf->Cell(40, 5, 'ESTADO', 1, 1, 'C');

        $pdf->SetFont('Times', '', 10);
        $cont = 1;

        foreach ($this->basenota->getnotas($valor) as $item) {
            if ($item->nome != null) {
                $pdf->Cell(5, 5, $cont++, 1, 0);
                $pdf->Cell(97, 5, $item->nome, 1, 0);
                $pdf->Cell(45, 5, $item->mac, 1, 0, 'C');
                $pdf->Cell(45, 5, $item->cpp, 1, 0, 'C');
                $pdf->Cell(45, 5, ((floatval($item->mac) + floatval($item->cpp)) / 2), 1, 0, 'C');
                if (floatval(((floatval($item->mac) + floatval($item->cpp)) / 2)) >= 10.0) {
                    $pdf->SetTextColor(0, 0, 255);
                    $pdf->Cell(40, 5, 'APTO', 1, 1, 'C');
                } else {
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->Cell(40, 5, 'NÃO APTO', 1, 1, 'C');
                }
                $pdf->SetTextColor(0, 0, 0);
            }
        }
        $pdf->Ln((183) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(10, $pdf->getY(), 287, $pdf->getY());
        $pdf->Cell(170, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->Cell(33, 5, "Mais informações:", 0, 0, 'L');
        $pdf->SetTextColor(50, 150, 255);
        $pdf->Cell(100, 5, "atomotecnologia.com", 0, 1, 'L');
        $pdf->Output();
    }

    //Banco
    public function relactoriobanco()
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetAuthor('MeuGenio');
        $pdf->SetTitle('Relatório de Bancos');
        $pdf->SetSubject('Relatório de Bancos');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 20, 8, 22, 'JPG');

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(205, 5, '', 0, 0);
        $pdf->Cell(59, 7, 'RELATÓRIO BANCÁRIO', 0, 1);
        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0);
        $pdf->Cell(15, 5, 'Data:', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, date('d/m/Y'), 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->municipio . ', ANGOLA', 0, 0);
        $pdf->Cell(25, 5, '', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, 'Telefone: ' . $this->baseinstituicao->getAll()[0]->telefone, 0, 1);
        $pdf->Cell(130, 5, 'Email: ' . ($this->baseinstituicao->getAll()[0]->email), 0, 1);
        $pdf->Cell(130, 5, 'C. Postal: ' . $this->baseinstituicao->getAll()[0]->cpostal, 0, 1);
        $pdf->Cell(130, 5, 'Contribuinte Nº ' . $this->baseinstituicao->getAll()[0]->nif, 0, 1);

        /* $pdf->SetTextColor(255, 0, 0);
          $pdf->Cell(277, 10, "DOCUMENTO EMITIDO PARA FINS DE FORMAÇÃO", 0, 1, 'C');
          $pdf->SetTextColor(0, 0, 0); */

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(277, 20, '', 0, 1);
        $pdf->Cell(277, 5, 'RELATÓRIO DE BANCÁRIO DE ' . trim(explode('-', $this->input->post("data"))[0]) . ' Á ' .
            trim(explode('-', $this->input->post("data"))[1]), 0, 1, 'C');
        $pdf->SetFillColor(222, 222, 222);
        // End Title
        $pdf->SetY($pdf->GetY() - 30);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 14);
        $pdf->Ln(40);
        $pdf->Cell(277, 1, ' DADOS BANCÁRIO - ' . $this->basebanco->getId($this->input->post("banco"))[0]->designacao, 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 12);
        $pdf->Ln(9);
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(5, 5, '#', 1, 0);
        $pdf->Cell(30, 5, 'Nº Ordem', 1, 0);
        $pdf->Cell(80, 5, 'Operador', 1, 0);
        $pdf->Cell(62, 5, 'Movimento', 1, 0);
        $pdf->Cell(50, 5, 'Data', 1, 0);
        $pdf->Cell(50, 5, 'Valor', 1, 1);

        $pdf->SetFont('Times', '', 10);
        $cont = 1;

        //Variaveis 
        $credito = 0;
        $debito = 0;
        $total = 0;

        $datainicio = explode('-', $this->input->post("data"))[0];
        $datafim = explode('-', $this->input->post("data"))[1];
        foreach ($this->basefactura->getBancoPeriodo($this->input->post("banco"), (trim(explode('/', $datainicio)[2]) . '-' . trim(explode('/', $datainicio)[1]) . '-' . trim(explode('/', $datainicio)[0])), (trim(explode('/', $datafim)[2]) . '-' . trim(explode('/', $datafim)[1]) . '-' . trim(explode('/', $datafim)[0])))
            as $item) {
            $pdf->Cell(5, 5, $cont++, 1, 0);
            $pdf->Cell(30, 5, $this->mecanograficobanco($item->id), 1, 0);
            $pdf->Cell(80, 5, $this->basepessoa->getId($item->idfuncionario)[0]->nome, 1, 0, 'L');
            $pdf->Cell(62, 5, ($item->idcliente != null) ? 'CRÉDITO' : 'DÉBITO', 1, 0);
            $pdf->Cell(50, 5, date('d/m/Y', strtotime($item->data1)), 1, 0, 'L');
            $pdf->Cell(50, 5, $item->total, 1, 1);
            ($item->idcliente != null) ? ($credito += $item->total) : ($debito += $item->total);
        }
        $total += ($credito - $debito);
        //Corpo-Total


        $pdf->Cell(177, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(50, 5, 'Total', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(50, 5, $total . '.00(AKZ)', 1, 1, 'R');

        $pdf->Ln((183) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(10, $pdf->getY(), 287, $pdf->getY());
        $pdf->Cell(170, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->Cell(33, 5, "Mais informações:", 0, 0, 'L');
        $pdf->SetTextColor(50, 150, 255);
        $pdf->Cell(100, 5, "atomotecnologia.com", 0, 1, 'L');
        $pdf->Output();
    }

    public function relactoriosalario()
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetAuthor('MeuGenio');
        $pdf->SetTitle('Relatório Salarial');
        $pdf->SetSubject('Relatório Salarial');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 20, 8, 22, 'JPG');

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(205, 5, '', 0, 0);
        $pdf->Cell(59, 7, 'RELATÓRIO SALARIAL', 0, 1);
        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0);
        $pdf->Cell(15, 5, 'Data:', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, date('d/m/Y'), 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->municipio . ', ANGOLA', 0, 0);
        $pdf->Cell(25, 5, '', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, 'Telefone: ' . $this->baseinstituicao->getAll()[0]->telefone, 0, 1);
        $pdf->Cell(130, 5, 'Email: ' . ($this->baseinstituicao->getAll()[0]->email), 0, 1);
        $pdf->Cell(130, 5, 'C. Postal: ' . $this->baseinstituicao->getAll()[0]->cpostal, 0, 1);
        $pdf->Cell(130, 5, 'Contribuinte Nº ' . $this->baseinstituicao->getAll()[0]->nif, 0, 1);

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(277, 20, '', 0, 1);
        $pdf->Cell(277, 5, 'RELATÓRIO SALARIAL DE ' . trim(explode('-', $this->input->post("data"))[0]) . ' Á ' .
            trim(explode('-', $this->input->post("data"))[1]), 0, 1, 'C');
        $pdf->SetFillColor(222, 222, 222);
        // End Title
        $pdf->SetY($pdf->GetY() - 30);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 14);
        $pdf->Ln(40);
        $pdf->Cell(277, 1, ' DADOS SALARIAL', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 12);
        $pdf->Ln(9);
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(5, 5, '#', 1, 0);
        $pdf->Cell(67, 5, 'Funcionário', 1, 0);
        $pdf->Cell(30, 5, 'Salario', 1, 0);
        $pdf->Cell(25, 5, 'Referênçia', 1, 0);
        $pdf->Cell(30, 5, 'IRT', 1, 0);
        $pdf->Cell(30, 5, 'S. Social', 1, 0);
        $pdf->Cell(30, 5, 'Subsídio', 1, 0);
        $pdf->Cell(30, 5, 'Desconto', 1, 0);
        $pdf->Cell(30, 5, 'Total', 1, 1);

        $pdf->SetFont('Times', '', 10);
        $cont = 1;

        //Variaveis 
        $subtotal = 0;
        $salario = 0;
        $irt = 0;
        $ssocial = 0;
        $imposto = 0;
        $subcidio = 0;
        $desconto = 0;
        $total = 0;

        $datainicio = explode('-', $this->input->post("data"))[0];
        $datafim = explode('-', $this->input->post("data"))[1];
        foreach ($this->basesalario->getSalarioPeriodo(
            (trim(explode('/', $datainicio)[2]) . '-' . trim(explode('/', $datainicio)[1]) . '-' . trim(explode('/', $datainicio)[0])),
            (trim(explode('/', $datafim)[2]) . '-' . trim(explode('/', $datafim)[1]) . '-' . trim(explode('/', $datafim)[0]))
        )
            as $item) {
            $pdf->Cell(5, 5, $cont++, 1, 0);
            $pdf->Cell(67, 5, $item->funcionario, 1, 0);
            $pdf->Cell(30, 5, $item->salario . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(25, 5, $item->mes, 1, 0, 'C');
            $pdf->Cell(30, 5, $item->irt . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(30, 5, $item->ssocial . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(30, 5, $item->subcidio . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(30, 5, $item->desconto . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(30, 5, $item->total . '.00 (AKZ)', 1, 1, 'R');

            //            $subtotal += $item->total;
            $salario += $item->salario;
            $irt += $item->irt;
            $ssocial += $item->ssocial;
            $imposto += ($item->irt + $item->ssocial);
            $subcidio += $item->subcidio;
            $desconto += $item->desconto;
            $total += $item->total;
        }

        //Corpo-Total
        $pdf->Cell(277, 5, '', 0, 1);
        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total Salário', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $salario . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total IRT', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $irt . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total S.Social', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $ssocial . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total Imposto', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $imposto . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total Subsídio', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $subcidio . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total Desconto', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $desconto . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $total . '.00(AKZ)', 1, 1, 'R');

        $pdf->Ln((183) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(10, $pdf->getY(), 287, $pdf->getY());
        $pdf->Cell(170, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->Cell(33, 5, "Mais informações:", 0, 0, 'L');
        $pdf->SetTextColor(50, 150, 255);
        $pdf->Cell(100, 5, "atomotecnologia.com", 0, 1, 'L');
        $pdf->Output();
    }

    //Compra
    public function relactoriocompra()
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetAuthor('MeuGenio');
        $pdf->SetTitle('Relatório de Compra');
        $pdf->SetSubject('Relatório de Compra');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 20, 8, 22, 'JPG');

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(205, 5, '', 0, 0);
        $pdf->Cell(59, 7, 'RELATÓRIO DE COMPRA', 0, 1);
        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0);
        $pdf->Cell(15, 5, 'Data:', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, date('d/m/Y'), 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->municipio . ', ANGOLA', 0, 0);
        $pdf->Cell(25, 5, '', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, 'Telefone: ' . $this->baseinstituicao->getAll()[0]->telefone, 0, 1);
        $pdf->Cell(130, 5, 'Email: ' . ($this->baseinstituicao->getAll()[0]->email), 0, 1);
        $pdf->Cell(130, 5, 'C. Postal: ' . $this->baseinstituicao->getAll()[0]->cpostal, 0, 1);
        $pdf->Cell(130, 5, 'Contribuinte Nº ' . $this->baseinstituicao->getAll()[0]->nif, 0, 1);

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(277, 20, '', 0, 1);
        $pdf->Cell(277, 5, 'RELATÓRIO DE COMPRA DE ' . trim(explode('-', $this->input->post("data"))[0]) . ' Á ' .
            trim(explode('-', $this->input->post("data"))[1]), 0, 1, 'C');
        $pdf->SetFillColor(222, 222, 222);
        // End Title
        $pdf->SetY($pdf->GetY() - 30);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 14);
        $pdf->Ln(40);
        $pdf->Cell(277, 1, ' DADOS DA COMPRA', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 12);
        $pdf->Ln(9);
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(5, 5, '#', 1, 0);
        $pdf->Cell(30, 5, 'Nº Factura', 1, 0);
        $pdf->Cell(50, 5, 'Fornecedor', 1, 0);
        $pdf->Cell(47, 5, 'Operador', 1, 0);
        $pdf->Cell(25, 5, 'Data', 1, 0);
        $pdf->Cell(30, 5, 'Subtotal', 1, 0);
        $pdf->Cell(30, 5, 'Imposto', 1, 0);
        $pdf->Cell(30, 5, 'Desconto', 1, 0);
        $pdf->Cell(30, 5, 'Total', 1, 1);

        $pdf->SetFont('Times', '', 10);
        $cont = 1;

        //Variaveis 
        $subtotal = 0;
        $imposto = 0;
        $desconto = 0;
        $total = 0;

        $datainicio = explode('-', $this->input->post("data"))[0];
        $datafim = explode('-', $this->input->post("data"))[1];
        foreach ($this->basefactura->getCompraPeriodo(
            (trim(explode('/', $datainicio)[2]) . '-' . trim(explode('/', $datainicio)[1]) . '-' . trim(explode('/', $datainicio)[0])),
            (trim(explode('/', $datafim)[2]) . '-' . trim(explode('/', $datafim)[1]) . '-' . trim(explode('/', $datafim)[0]))
        )
            as $item) {
            $pdf->Cell(5, 5, $cont++, 1, 0);
            $pdf->Cell(30, 5, $this->mecanografico($item->idfactura), 1, 0);
            $pdf->Cell(50, 5, $item->fornecedor, 1, 0);
            $pdf->Cell(47, 5, $item->funcionario, 1, 0);
            $pdf->Cell(25, 5, date('d/m/Y', strtotime($item->datafactura)), 1, 0, 'C');
            $pdf->Cell(30, 5, $item->subtotal . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(30, 5, $item->imposto . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(30, 5, $item->desconto . ' %', 1, 0, 'C');
            $pdf->Cell(30, 5, $item->total . '.00 (AKZ)', 1, 1, 'R');

            $subtotal += $item->subtotal;
            $imposto += $item->imposto;
            $desconto += (($item->subtotal * $item->desconto) / 100);
            $total += $item->total;
        }

        //Corpo-Total
        $pdf->Cell(277, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->Cell(30, 5, 'Subtotal', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $subtotal . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Imposto', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $imposto . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Desconto', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $desconto . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $total . '.00(AKZ)', 1, 1, 'R');

        $pdf->Ln((183) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(10, $pdf->getY(), 287, $pdf->getY());
        $pdf->Cell(170, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->Cell(33, 5, "Mais informações:", 0, 0, 'L');
        $pdf->SetTextColor(50, 150, 255);
        $pdf->Cell(100, 5, "atomotecnologia.com", 0, 1, 'L');
        $pdf->Output();
    }

    //Vendas
    public function relactoriovenda()
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetAuthor('MeuGenio');
        $pdf->SetTitle('Relatório de Venda');
        $pdf->SetSubject('Relatório de Venda');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 20, 8, 22, 'JPG');

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(205, 5, '', 0, 0);
        $pdf->Cell(59, 7, 'RELATÓRIO DE VENDA', 0, 1);
        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0);
        $pdf->Cell(15, 5, 'Data:', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, date('d/m/Y'), 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->municipio . ', ANGOLA', 0, 0);
        $pdf->Cell(25, 5, '', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, 'Telefone: ' . $this->baseinstituicao->getAll()[0]->telefone, 0, 1);
        $pdf->Cell(130, 5, 'Email: ' . ($this->baseinstituicao->getAll()[0]->email), 0, 1);
        $pdf->Cell(130, 5, 'C. Postal: ' . $this->baseinstituicao->getAll()[0]->cpostal, 0, 1);
        $pdf->Cell(130, 5, 'Contribuinte Nº ' . $this->baseinstituicao->getAll()[0]->nif, 0, 1);

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(277, 20, '', 0, 1);
        $pdf->Cell(277, 5, 'RELATÓRIO DE VENDA DE ' . trim(explode('-', $this->input->post("data"))[0]) . ' Á ' .
            trim(explode('-', $this->input->post("data"))[1]), 0, 1, 'C');
        $pdf->SetFillColor(222, 222, 222);
        // End Title
        $pdf->SetY($pdf->GetY() - 30);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 14);
        $pdf->Ln(40);
        $pdf->Cell(277, 1, ' DADOS DA VENDA', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 12);
        $pdf->Ln(9);
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(5, 5, '#', 1, 0);
        $pdf->Cell(30, 5, 'Nº Factura', 1, 0);
        $pdf->Cell(50, 5, 'Cliente', 1, 0);
        $pdf->Cell(47, 5, 'Operador', 1, 0);
        $pdf->Cell(25, 5, 'Data', 1, 0);
        $pdf->Cell(30, 5, 'Subtotal', 1, 0);
        $pdf->Cell(30, 5, 'Imposto', 1, 0);
        $pdf->Cell(30, 5, 'Desconto', 1, 0);
        $pdf->Cell(30, 5, 'Total', 1, 1);

        $pdf->SetFont('Times', '', 10);
        $cont = 1;

        //Variaveis 
        $subtotal = 0;
        $imposto = 0;
        $desconto = 0;
        $total = 0;

        $datainicio = explode('-', $this->input->post("data"))[0];
        $datafim = explode('-', $this->input->post("data"))[1];
        foreach ($this->basefactura->getVendaPeriodo(
            (trim(explode('/', $datainicio)[2]) . '-' . trim(explode('/', $datainicio)[1]) . '-' . trim(explode('/', $datainicio)[0])),
            (trim(explode('/', $datafim)[2]) . '-' . trim(explode('/', $datafim)[1]) . '-' . trim(explode('/', $datafim)[0]))
        )
            as $item) {
            $pdf->Cell(5, 5, $cont++, 1, 0);
            $pdf->Cell(30, 5, $this->mecanografico($item->idfactura), 1, 0);
            $pdf->Cell(50, 5, $item->cliente, 1, 0);
            $pdf->Cell(47, 5, $item->funcionario, 1, 0);
            $pdf->Cell(25, 5, date('d/m/Y', strtotime($item->datafactura)), 1, 0, 'C');
            $pdf->Cell(30, 5, $item->subtotal . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(30, 5, $item->imposto . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(30, 5, $item->desconto . ' %', 1, 0, 'C');
            $pdf->Cell(30, 5, $item->total . '.00 (AKZ)', 1, 1, 'R');

            $subtotal += $item->subtotal;
            $imposto += $item->imposto;
            $desconto += (($item->subtotal * $item->desconto) / 100);
            $total += $item->total;
        }

        //Corpo-Total
        $pdf->Cell(277, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->Cell(30, 5, 'Subtotal', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $subtotal . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Imposto', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $imposto . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Desconto', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $desconto . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $total . '.00(AKZ)', 1, 1, 'R');

        $pdf->Ln((183) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(10, $pdf->getY(), 287, $pdf->getY());
        $pdf->Cell(170, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->Cell(33, 5, "Mais informações:", 0, 0, 'L');
        $pdf->SetTextColor(50, 150, 255);
        $pdf->Cell(100, 5, "atomotecnologia.com", 0, 1, 'L');
        $pdf->Output();
    }

    //Vendas
    public function relactoriopropina()
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetAuthor('MeuGenio');
        $pdf->SetTitle('Relatório de Propina');
        $pdf->SetSubject('Relatório de Propina');
        $pdf->AddPage();

        $pdf->Image(base_url() . "assets/media/imagem/" . $this->baseinstituicao->getAll()[0]->logotipo, 20, 8, 22, 'JPG');

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(205, 5, '', 0, 0);
        $pdf->Cell(59, 7, 'RELATÓRIO DE PROPINA', 0, 1);
        $pdf->Ln(7);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->nome, 0, 0);
        $pdf->Cell(15, 5, 'Data:', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, date('d/m/Y'), 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, $this->baseinstituicao->getAll()[0]->municipio . ', ANGOLA', 0, 0);
        $pdf->Cell(25, 5, '', 0, 0);
        $pdf->SetFont('Times', '', 12, '', true);
        $pdf->Cell(34, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 12, '', true);
        $pdf->Cell(205, 5, 'Telefone: ' . $this->baseinstituicao->getAll()[0]->telefone, 0, 1);
        $pdf->Cell(130, 5, 'Email: ' . ($this->baseinstituicao->getAll()[0]->email), 0, 1);
        $pdf->Cell(130, 5, 'C. Postal: ' . $this->baseinstituicao->getAll()[0]->cpostal, 0, 1);
        $pdf->Cell(130, 5, 'Contribuinte Nº ' . $this->baseinstituicao->getAll()[0]->nif, 0, 1);

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(277, 20, '', 0, 1);
        $pdf->Cell(277, 5, 'RELATÓRIO DE PAGAMENTO DE ' . trim(explode('-', $this->input->post("data"))[0]) . ' Á ' .
            trim(explode('-', $this->input->post("data"))[1]), 0, 1, 'C');
        $pdf->SetFillColor(222, 222, 222);
        // End Title
        $pdf->SetY($pdf->GetY() - 30);
        $pdf->setX(19.9);
        $pdf->SetFont('Times', 'B', 14);
        $pdf->Ln(40);
        $pdf->Cell(277, 1, ' DADOS DA PAGAMENTO', 1, 0, 'L', true);
        $pdf->SetFont('Times', '', 12);
        $pdf->Ln(9);
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(5, 5, '#', 1, 0);
        $pdf->Cell(30, 5, 'Nº Factura', 1, 0);
        $pdf->Cell(97, 5, 'Aluno', 1, 0);
        $pdf->Cell(25, 5, 'Data', 1, 0);
        $pdf->Cell(30, 5, 'Subtotal', 1, 0);
        $pdf->Cell(30, 5, 'Imposto', 1, 0);
        $pdf->Cell(30, 5, 'Desconto', 1, 0);
        $pdf->Cell(30, 5, 'Total', 1, 1);

        $pdf->SetFont('Times', '', 10);
        $cont = 1;

        //Variaveis 
        $subtotal = 0;
        $imposto = 0;
        $desconto = 0;
        $total = 0;

        $datainicio = explode('-', $this->input->post("data"))[0];
        $datafim = explode('-', $this->input->post("data"))[1];
        foreach ($this->basefactura->getPagamentoPeriodo(
            (trim(explode('/', $datainicio)[2]) . '-' . trim(explode('/', $datainicio)[1]) . '-' . trim(explode('/', $datainicio)[0])),
            (trim(explode('/', $datafim)[2]) . '-' . trim(explode('/', $datafim)[1]) . '-' . trim(explode('/', $datafim)[0]))
        )
            as $item) {
            $pdf->Cell(5, 5, $cont++, 1, 0);
            $pdf->Cell(30, 5, $this->mecanografico($item->idfactura), 1, 0);
            $pdf->Cell(97, 5, $item->estudante, 1, 0);
            $pdf->Cell(25, 5, date('d/m/Y', strtotime($item->datafactura)), 1, 0, 'C');
            $pdf->Cell(30, 5, $item->subtotal . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(30, 5, $item->imposto . '.00 (AKZ)', 1, 0, 'R');
            $pdf->Cell(30, 5, $item->desconto . ' %', 1, 0, 'C');
            $pdf->Cell(30, 5, $item->total . '.00 (AKZ)', 1, 1, 'R');

            $subtotal += $item->subtotal;
            $imposto += $item->imposto;
            $desconto += (($item->subtotal * $item->desconto) / 100);
            $total += $item->total;
        }

        //Corpo-Total
        $pdf->Cell(277, 5, '', 0, 1);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->Cell(30, 5, 'Subtotal', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $subtotal . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Imposto', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $imposto . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Desconto', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $desconto . '.00(AKZ)', 1, 1, 'R');

        $pdf->Cell(217, 5, '', 0, 0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(30, 5, 'Total', 1, 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(30, 5, $total . '.00(AKZ)', 1, 1, 'R');

        $pdf->Ln((183) - $pdf->GetY());
        $pdf->SetFont('Times', '', 10);
        $pdf->Line(10, $pdf->getY(), 287, $pdf->getY());
        $pdf->Cell(170, 5, utf8_decode("Processado por computador"), 0, 0, 'L');
        $pdf->Cell(33, 5, "Mais informações:", 0, 0, 'L');
        $pdf->SetTextColor(50, 150, 255);
        $pdf->Cell(100, 5, "atomotecnologia.com", 0, 1, 'L');
        $pdf->Output();
    }
}
