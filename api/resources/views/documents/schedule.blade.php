<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Mini-Pauta</title>
    <link rel="stylesheet" type="text/css" href="{{ public_path('bootstrap.css') }}">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        img {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <img src="{{public_path('images/logo.png')}}" alt="Insignia">
        <br><br>
        <strong>REPÚBLICA DE ANGOLA</strong><br>
        <strong>MINISTÉRIO DA EDUCAÇÃO</strong><br>
        <strong>MINI-PAUTA</strong><br><br>
        <strong>{{$empresa->designation}}</strong><br><br>
        <table>
            <tr>
                <th colspan="8">Informações da Mini-Pauta</th>
            </tr>
            <tr>
                <td colspan="2">DISCIPLINA:</td>
                <td colspan="2">Nome da Disciplina</td>
                <td colspan="2">CLASSE:</td>
                <td colspan="2">Nome da Classe</td>
            </tr>
            <tr>
                <td colspan="2">TURMA:</td>
                <td colspan="2">Nome da Turma</td>
                <td colspan="2">ANO LECTIVO:</td>
                <td colspan="2">Ano Lectivo</td>
            </tr>
        </table>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Nome Completo</th>
                    <th colspan="3">I TRIMESTRE</th>
                    <th>CAP</th>
                    <th>CPE/CE</th>
                    <th>CF</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Nome do Aluno</td>
                    <td>Nota I-1</td>
                    <td>Nota I-2</td>
                    <td>Nota I-3</td>
                    <td>CAP</td>
                    <td>CPE/CE</td>
                    <td>CF</td>
                </tr>
                <!-- Repetir as linhas para cada aluno -->
            </tbody>
        </table>
        <br>
        <p>CT=(MAC+CPP)/2; CAP=(CT1+CT2+CT3)/3; CF=0,4xCAP+0,6xCPE</p>
        <br>
        <p>_____________________________</p>
        <p>PROFESSOR</p>
        <br>
        <!-- Código de barras (se aplicável) -->
        <img src="caminho_para_codigo_de_barras" alt="Código de Barras">
        <br><br>
        <p>Processado por computador</p>
    </div>
</body>
</html>
