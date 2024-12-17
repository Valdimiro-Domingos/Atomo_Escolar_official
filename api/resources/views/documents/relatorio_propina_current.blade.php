 
 <!DOCTYPE html>
   <html lang="en">
   <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELATORIO DE PROPINAS</title>
       <link rel="stylesheet" type="text/css" href="{{ public_path('bootstrap.css') }}">
   </head>
  <body>
  
  <style>
            *{
                margin: 0px;
                padding: 0px;
                box-sizing: border-box;
                
            }
            .d-flex{
                display: flex;
            }
            .justify-content-center{
                justify-content: center;
            }
            .align-items-center{
                align-items: center;
            }
            .flex-column{
                flex-flow: column;
            }
            .container{
                padding-top: 2rem
            }
            .header p{
                  padding: 1px;
                  margin: 0px;
                  font-size: 12px;
                  text-align: center;
                  text-transform: uppercase;
                }
              
                .header p:first-child{
                padding: .5rem 0 0
              }
              
              .header p:last-child{
                padding: .2rem 0 1rem;
              }
              
              .informacao{
                width: 100%;
                display: block;
                padding: 1rem;
                border: 1px solid #777;
              }
              .informacao p{
                padding: .2rem 0;
                font-size: 10pt;
              }
              .informacao p:last-child span{
                font-weight: 600;
                padding-left: 3.5rem;
              }
              
              .grid-count{
                display: grid;
                text-align: center;
                grid-template-columns: repeat(3, 1fr);
              }
              
              .grid-count span{
                 font-size: 11pt;
              }
              
              .grid-count span:first-child{
                text-align: left;
              }
              
              .items-container{
                padding: 1rem .0rem;
              }
              
              
              table{
                tr{
                    td{
                        padding: .2rem .8rem;
                    }
                }
              }
              
              .font-escola{
                font-size: 13pt;
              }
              
        
  </style>
  
  
  
   <div class=" container mx-auto">
    <div class="row px-5 pt-5">
        <div class="col-12 text-center d-flex flex-column align-items-center justify-content-center">
                
               @if ($dados['empresa']->logo)
                   <img width="50" height="50" style="object-fit: cover;" src="{{public_path('images/company/'.$dados['empresa']->logo)}}" alt="{{ $dados['empresa']->designation }}">
                   <div class="p-2"></div>
               @else
                   <img width="150px" height="70px" style="object-fit: contain;" src="{{public_path('images/company/logo.png')}}" alt="{{ $dados['empresa']->designation }}">
               @endif
               <br>
       {{--     <span  style="font-weight: bolder">Republica de Angola</span><br>
            <span style="font-weight: bolder">Governo Provincial de Luanda</span> <br>
            <span style="font-weight: bolder">Direcção Municipal de Educação</span>--}}
            <h4 style="font-weight: bolder; margin:0px!omportant;padding:0!important;">{{ $dados['empresa']->designation }}</h4>
        </div>
    </div>

    <div class="row container py-0" style="margin-top: 0px;">
        <div class="col-12 text-center">
            <h5 class="my-0" style="font-weight: bolder; margin:0px!omportant;padding:0!important;">RELATÓRIO DE PROPINAS E DIVIDAS</h5>
        </div>
    </div>
    
    {{--  <div class="row" style=" padding-top: 10px;padding-bottom: 10px;">
       <div class="col-xs-6">
              <span style="font-size: 12px;">SALA: <strong>{{$sala->designation}}</strong></span><br>
              <span style="font-size: 12px;">CLASSE: <strong>{{$classe->designation}}</strong></span><br>
              <span style="font-size: 12px;">PERIODO: <strong>{{$periodo->designation}}</strong></span> <br>
              <span style="font-size: 12px;">ANO LECTIVO: <strong> {{$anoLetivo->designation}}</strong></span><br>
         </div>    
     </div>--}}
    <hr>
    
      
    <div class="row px-5 w-100" style="padding: 0 1rem">
        <table class="table border-0">
            <thead>
                <tr>
                    <th style="font-size: 12px;">#</th>
                    <th style="font-size: 12px;">NOME COMPLETO</th>
                    <th style="font-size: 12px;">JAN</th>
                    <th style="font-size: 12px;">FEV</th>
                    <th style="font-size: 12px;">MAR</th>
                    <th style="font-size: 12px;">ABR</th>
                    <th style="font-size: 12px;">MAI</th>
                    <th style="font-size: 12px;">JUN</th>
                    <th style="font-size: 12px;">JUL</th>
                    <th style="font-size: 12px;">AGO</th>
                    <th style="font-size: 12px;">SET</th>
                    <th style="font-size: 12px;">OUT</th>
                    <th style="font-size: 12px;">NOV</th>
                    <th style="font-size: 12px;">DEZ</th>
                </tr>
            </thead>
            <tbody>
            @forelse($dados['dados'] as $i => $item)
            <tr>
                <td style="font-size: 12px;">{{ $i + 1 }}</td>
                <td style="font-size: 12px;">{{ $item['student']->name }}</td>
                
                @foreach (['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'] as $mes)
                    @php
                        // Verifica se o mês atual está entre os meses pagos
                        $mesPago = in_array($mes, $item['meses']['paid']);
                    @endphp
                    <td style="font-size: 12px;">{{ $mesPago ? 'X' : '--' }}</td>
                @endforeach
            
            </tr>
            @empty
            <tr> 
                <td colspan="14" class="text-center">Não há registros.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="row">
        <div class="col-12">
               <div style="text-align:center;font-size: 12px;color:rgb(6, 0, 58);">
                ASSINATURA<br>
                _____________________________________<br>
            </div>
        </div>
        <div class="col-12 my-5" style="margin-top: 2rem; border-top: 1px solid #000;">
            <div style="text-align:left;color:rgb(6, 0, 58);font-size:12px;">Processado por computador</div>
        </div>
    </div>
    </div>
 </body>
 </html>
 