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
    @php 
        $trimestre = '';
if ($minipauta->trimestre->designation == '1') {
    $trimestre = "I";
} else if ($minipauta->trimestre->designation == '2') {
    $trimestre = "II";
} else {
    $trimestre = "III";
}                
@endphp 



    <div style="text-align: center;">
        <img src="{{public_path('images/logo.png')}}" alt="Insignia">
        <br><br>
        <strong>REPÚBLICA DE ANGOLA</strong><br>
        <strong>MINISTÉRIO DA EDUCAÇÃO</strong><br>
        <strong>MINI-PAUTA</strong><br>
        <strong>{{$empresa->designation}}</strong><br><br><br>
        <table>
            <tr>
                <th colspan="8">{{$minipauta->designation}}</th>
            </tr>
            <tr>
                <td colspan="2">DISCIPLINA:</td>
                <td colspan="2">{{$minipauta->discipline->designation}}</td>
                <td colspan="2">CLASSE:</td>
                <td colspan="2">{{$minipauta->schedule->classe->description}}</td>
            </tr>
            <tr>
                <td colspan="2">TURMA:</td>
                <td colspan="2">{{$minipauta->schedule->turma->designation}}</td>
                <td colspan="2">ANO LECTIVO:</td>
                <td colspan="2">{{$minipauta->schedule->school_year->designation}}</td>
            </tr>
        </table>
        <br>
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Nº</th>
                    <th rowspan="2">Nome Completo</th>
                  
                    <th colspan="4">{{$trimestre}} TRIMESTRE</th>
                </tr>
                    <tr>
                        <th>MAC</th>
                        <th>PP</th>
                        <th>PT</th>
                        <th>MT</th>
                    </tr>
            </thead>
            <tbody>
                @forelse   ($notas as $i => $estudante)
                    <tr>
                        <td>1</td>
                        <td>{{$estudante->student->name}}</td>
                        <td>{{$estudante->continuous_evaluation_average}}</td>
                        <td>{{$estudante->teachers_test_score}}</td>
                        <td>{{$estudante->quarterly_test_score}}</td>
                        <td>{{$estudante->quarterly_average}}</td>
                    </tr>
                @empty
                    <tr> 
                      <td colspan="6" class="text-center">Não há registros.</td>
                    </tr>
                @endforelse
                <!-- Repetir as linhas para cada aluno -->
            </tbody>
        </table>
        <br>
        <div style="text-align: left; padding-top: 2rem;"> 
            COMPORTAMENTO: <strong>Bom (   ), Razoável(  ), Mau(    )</strong>

        </div>

        <div style="text-align: left;"> 
            <p><strong>MAC -</strong> Média das Avaliações</p>
            <p><strong>PP -</strong> Nota da Prova de Professor</p>
            <p><strong>PT -</strong> Nota da Prova Trimestral</p>
            <p><strong>MT -</strong> Média Trimestral</p>
        </div>
        <br>

        <b>{{$minipauta->profeessor->name}}</b>
        <p>_____________________________</p>
        <p>O/A PROFESSOR</p>
        <!-- Código de barras (se aplicável) -->
        {{--<img src="caminho_para_codigo_de_barras" alt="Código de Barras">--}}
        <br>
        <p>Processado por computador</p>
    </div>
</body>
</html>
