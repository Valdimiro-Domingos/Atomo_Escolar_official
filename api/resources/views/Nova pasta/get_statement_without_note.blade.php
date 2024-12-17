<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Mini Pauta Escolar</title>
    <link rel="stylesheet" href="{{ asset('assets/pdf/bootstrap.css') }}">
    <style>
        /* Estilos CSS (para exemplo) */
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }

        .container {
            margin-top: 20px;
        }

        .table {
            margin-top: 20px;
        }

        .page {
            width: 297mm; /* Ajuste devido às margens de 15mm em cada lado */
            height: 180mm;
            margin: 20mm auto; /* Margem de 20mm acima e abaixo, 0 nas laterais */
            background-color: white; /* Cor de fundo para simular a folha */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra para destacar a folha */
            padding: 10mm; /* Espaçamento interno para manter o conteúdo longe das bordas */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-md-12 text-center">
                <img height="50" width="70" src="https://www.ciam.gov.ao/images/angola/ensignia.png"
                    border="0" />
                <h3>REPUBLICA DE ANGOLA</h3>
                <h4>MINISTERIO DA EDUCAÇÃO</h4>
                <h4>{{ strtoupper($dados['empresa']->designation) }}</h4>
                <H6><strong>MINI-PAUTA DE {{ strtoupper($dados['dados']->discipline->designation) }}</strong></H6>

            </div>
            <div class="col-md-12 mx-auto mt-5">
            </div>
            <div class="col-md-12 mx-auto mt-5 text-center">

                <h5>Ano Lectivo {{ strtoupper($dados['dados']->schedule->school_year->designation) }}
                    {{ strtoupper($dados['dados']->schedule->classe->designation) }} º Classe
                    {{ strtoupper($dados['dados']->schedule->course->designation) }} Sala:
                    {{ strtoupper($dados['dados']->schedule->class_room->designation) }} Turma:
                    {{ strtoupper($dados['dados']->schedule->turma->designation) }}</h5>
            </div>
        </div>
        <div class="col-md-12">

        </div>

        <div class="col-md-6 mx-auto mt-5">

            <div class="row">
                <div class="col-md-6 text-center">
                    <h5>O professor</h5>
                    <hr>
                </div>
            </div>
        </div>
        <div class="col-md-6 mx-auto mt-5">

            <div class="row">
                <div class="col-md-6 text-center">
                    <h5>O professor</h5>
                    <hr>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
