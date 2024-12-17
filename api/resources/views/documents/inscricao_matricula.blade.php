 
 <!DOCTYPE html>
   <html lang="en">
   <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RELATORIO</title>
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
  
  
  
   <div class="painelLayout container">
    <div class="row">
        <div class="col-xs-6 text-left">
            @if ($dados['empresa']->logo)
                <img width="100px" height="100px" style="object-fit: cover;" src="{{public_path('images/company/'.$dados['empresa']->logo)}}" alt="{{ $dados['empresa']->designation }}">
                <div class="p-2"></div>
            @else
                <img width="150px" height="70px" style="object-fit: contain;" src="{{public_path('images/company/logo.png')}}" alt="{{ $dados['empresa']->designation }}">
      
                <div class="p-2"></div>
            @endif
        </div>

        <div class="col-xs-6">
            <h3>{{ $dados['empresa']->designation }}</h3>
            <p>NIF: {{ $dados['empresa']->nif }}</p>
            <p>Telefone: {{ $dados['empresa']->contact ? $dados['empresa']->contact : '' }}</p>
            <p>Email: {{ $dados['empresa']->email ? $dados['empresa']->email : '' }}</p>
            <p>Endereço: {{ $dados['empresa']->address ? $dados['empresa']->address : '' }}</p>
        </div>
    </div>

    <hr>
    <div class="row container">
        <div class="col-12 text-left">
        <h5 class="my-0">RELATÓRIO {{ strtoupper($dados['request']->documento == 'confirmacao' ? 'Confirmação' : 'Matricula' )}}</h5>
        <p>Nº : <strong>{{count($dados['dados'])}} Total</strong></p>
        </div>
        
        <div class="col-12">Data: <span>{{$dados['request']->date_issure}} / {{$dados['request']->date_end}}</span>  <hr></div>
    </div>
     
    <div class="row px-5 w-100" style="padding: 0 1rem">
                      <table class="table border-0">
                          <thead>
                              <tr>
                                  <th>Estudante</th>
                                  <th>Identidade</th>
                                  <th>Classe</th>
                                  <th>Data</th>
                                  <th>Genero</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse   ($dados['dados'] as $i => $item)
                                  <tr>
                                   <td>{{ $item->student->name }}</td>
                                   <td>{{$item->student->identity}}</td>
                                      <td>{{ $item->classe->description ?? '' }}</td>
                                      <td>{{ ($item->student->created_at) }}</td>
                                       <td>{{ ($item->student->gender  == "M" ? "Masculino" : "Femenino") }}</td>
                                  </tr>
                              @empty
                                <tr> 
                                  <td colspan="7" class="text-center">Não há registros.</td>
                                </tr>
                            @endforelse
                          </tbody>
                      </table>
                  </div>
  
          </div>
      </div>
     

    

    <div class="hidden none d-flex justify-content-center bg-black " style="display:flex; padding:2rem 0 0; margin: 0 auto; justify-content: center; margin-bottom:20px;color:rgb(6, 0, 58); width=100%;">
        <div style="text-align:center;float:left;font-size: 12px;color:rgb(6, 0, 58);">
            O DIRECTOR DA TURMA <br>
            _____________________________________<br>
            ___________/__________/___________
        </div>
        <div style="text-align:center;margin-left:500px;font-size: 12px;color:rgb(6, 0, 58);">
            O SUBDIRECTOR PEDAGOGICO <br>
            _________________________________<br>
            ___________/__________/___________
        </div>
        
    </div> 
    <div style="text-align:center;color:rgb(6, 0, 58);font-size:12px;"  class="hidden">Por uma educação de qualidade promovamos a competência e o bem-estar</div>
    <br>
</div>
 </body>
 </html>
 