 
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
            .painelLayout{
              display: block;
              padding: 1rem 0;
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
        <div class="row px-5">
            <div class="col-12 text-center d-flex flex-column align-items-center justify-content-center">
                    
               @if ($dados['empresa']->logo)
                   <img width="50" height="50" style="object-fit: cover;" src="{{public_path('images/company/'.$dados['empresa']->logo)}}" alt="{{ $dados['empresa']->designation }}">
                   <div class="p-2"></div>
               @else
                   <img width="150px" height="70px" style="object-fit: contain;" src="{{public_path('images/company/logo.png')}}" alt="{{ $dados['empresa']->designation }}">
               @endif
               <br>
            <span  style="font-weight: bolder">Republica de Angola</span><br>
            <span style="font-weight: bolder">Governo Provincial de Luanda</span> <br>
            <span style="font-weight: bolder">Direcção Municipal de Educação</span>
            <h4 style="font-weight: bolder; font-size: 13pt;">{{ $dados['empresa']->designation }}</h4>
        </div>
    </div>

    <div class="row container py-5" style="margin-top: 2rem;">
        <div class="col-12 text-center">
            <h5 class="my-0">RELATÓRIO DE PAGAMENTO DE {{ strtoupper($dados['request']->dateOf )}} Á {{ strtoupper($dados['request']->dateTo )}}</h5>
        </div>
    </div>
    <hr>
    
      
    <div class="row px-5 w-100" style="padding: 0 1rem">
        <table class="table border-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nº Factura</th>
                    <th>Aluno</th>
                    <th>Data</th>
                    <th>Subtotal</th>
                    <th>Imposto</th>
                    <th>Desconto</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            
                @php 
                    $totalImposto = 0;
                    $totalDiscunt = 0;
                    $totalSubtotal = 0;
                    $totalTotal = 0;
                @endphp
                    
                @forelse($dados['dados'] as $i => $item)
                    @php
                        $totalTotal += $item->total;
                        $totalImposto += $item->tax;
                        $totalDiscunt += $item->discount;
                        $totalSubtotal += $item->total - $item->tax;
                    @endphp

                    
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $item->invoice_number }}</td>
                        <td>{{$item->student->name}}</td>
                        <td>{{ $item->date_of_issue }}</td>
                        <td>{{ number_format($item->total - $item->tax, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->tax, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->discount, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->total, 2, ',', '.') }}</td>
                    </tr>
                @empty
                  <tr> 
                    <td colspan="8" class="text-center">Não há registros.</td>
                  </tr>
              @endforelse
            </tbody>
        </table>
    </div>
    
    
    <div class="row">
        <div class="col-xs-8"></div>
        <div class="col-xs-4">
    <b>SUMÁRIO</b>
    <table class="table">
        <tbody>
            <tr>
                <td><b>Subtotal</b></td>
                <td><b>{{ number_format($totalSubtotal, 2, ',', '.') }}</b></td>
            </tr>
            <tr>
                <td><b>Imposto</b></td>
                <td><b>{{ number_format($totalImposto, 2, ',', '.') }}</b></td>
            </tr>
            <tr>
                <td><b>Desconto</b></td>
                <td><b>{{ number_format($totalDiscunt, 2, ',', '.') }}</b></td>
            </tr>
            <tr>
                <td><b>Total</b></td>
                <td><b>{{ number_format($totalTotal, 2, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>
</div>
    </div>
     

    
    <div class="row">
        <div class="col-6">
            <div style="text-align:left;color:rgb(6, 0, 58);font-size:12px;">Processado por computador</div>
        </div>
        <div class="col-6">
            <div style="text-align:right;color:rgb(6, 0, 58);font-size:12px;">Mais informações: <a href="http://atomo.co.ao">atomo.co.ao</a></div>
        </div>
    </div>
 </body>
 </html>
 