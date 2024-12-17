<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Matricula</title>
    <link rel="stylesheet" type="text/css" href="{{ public_path('bootstrap.css') }}">
    
    <style>
        .br span{
            display: block;
        }
    </style>
</head>

<body>


    <div class="header">

    </div>

    <div class="footer">
        <p class="text-center"> </p>
    </div>


    <div class="container">
    @for ($count = 0; $count < 2; $count++)
        <div class="row">

                <div class="col-xs-6 text-left">
                    @if ($dados['empresa']->logo)
                        <img width="100px" height="100px" style="object-fit: cover;" src="{{public_path('images/company/'.$dados['empresa']->logo)}}" alt="{{ $dados['empresa']->designation }}">
                        <br>
                        <div class="p-2"></div>
                    @else
                        <img width="150px" height="70px" style="object-fit: contain;" src="{{public_path('images/company/logo.png')}}" alt="{{ $dados['empresa']->designation }}">
                        <br>
                        <div class="p-2"></div>
                    @endif
                </div>

            <div class="col-xs-6">
                <h3>{{ $dados['empresa']->designation }}</h3>
                <p>NIF: {{ $dados['empresa']->nif }}</p>
                <p>Telefone: {{ $dados['empresa']->contact ? $dados['empresa']->contact : '--' }}</p>
                <span>Email: {{ $dados['empresa']->email ? $dados['empresa']->email : '--' }}</span>
                <p>Endereço: {{ $dados['empresa']->address ? $dados['empresa']->address : '--' }}</p>
            </div>
        </div>
        <hr>
            <div class="row">
                <div class="col-xs-6">
                    <h5 style="margin:2px 0; padding:0px; line-height:10px;">RECIBO DE MATRICULA</h5>
                    <span>Recibo Nº: <strong>{{ $dados['dados']->enrollment_number }}</strong></span> </div>
                <div class="col-xs-6">
                    <span>Data : <strong>{{ $dados['dados']->created_at }}</strong></span>
                    
                </div>
            </div>
        <hr>
        <div class="row">
            <div class="col-xs-6 br">
                <span>Nome : <strong>{{ $dados['student']->name }}</strong></span>
                <span>NIF: <strong>{{ $dados['student']->identity }}</strong></span>
                <span>Curso: <strong>{{ $dados['dados']->course->designation }}</strong></span>
                <span>Classe: <strong>{{ $dados['dados']->classe->designation  }}</strong></span>
            </div>
            <div class="col-xs-6 br">
                <span>Ano Lectivo: <strong>{{ $dados['dados']->school_year->designation }}</strong></span>
                <span class='py-0 my-0'>Turma: <strong>{{ $dados['dados']->turma->designation  }}</strong></span>
                <span>Sala: <strong>{{ $dados['dados']->class_room->designation  }}</strong></span>
            </div>
        </div>

         <div style="padding: 30px 0 10px;" class="row px-0 mx-0">
            <table style="border-collapse: collapse; width: 100%; padding-bottom: 20px;" border="0">
                    <tr style="text-align:center;">
                        <td>Assinatura Secretaria</td>
                        <td>Assinatura Responsável</td>

                    </tr>
                   <tr style="text-align:center;">
                       <td>___________________________________</td>
                         <td>___________________________________</td>
                    </tr>
            </table>
         </div>


                <hr>
        @endfor

    </div>
</body>

</html>



