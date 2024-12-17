 
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
    <!-- logotipo -->
     <div style="text-align:center;">
       <img src="{{ public_path('images/logo.png')}}" width="50" height="50">
       <br>
       <!-- <img src="../../../public/images/logo.png" width="50" height="50"> -->
     </div>
     
     <!-- header -->
     <div class="header">
        <p >Republica de Angola</p>
        <p>Governo Provincial de Luanda</p>
        <p>Direcção Municipal de Educação</p>
        <p class="font-escola">{{$company->designation}}</p>
     </div>
     
     <!-- informacao -->
     <div class="row" style="padding-top: 30px;">
         <div class="col-xs-12 py-5">
           <p>DOCUMENTO: {{ strtoupper($documento)}}</p>
          </div>    
        </div>

            <hr>
        <div class="row" style="padding-top: 0px;padding-bottom: 10px;">
          <div class="col-xs-6">
           <p>TOTAL: <strong> {{count($enrollment)}}</strong></p>
           <p>TURMA: <strong>{{$turma->designation}}</strong></p>
          </div>

           <div class="col-xs-6">
            <p>CLASSE: <strong>{{$classe->designation}}</strong></p>
            <span>PERIODO: <strong>{{$periodo->designation}}</strong></span> 
         </div>    
     </div>
     
     
     @if($documento == 'encarregados')
            <div class="row px-5 w-100" style="padding: 0 1rem">
                      <table class="table border-0">
                          <thead>
                              <tr>
                                 <th>#</th>
                                  <th>Nº Processo</th>
                                  <th>Nome</th>
                                  <th>Nome do Pai</th>
                                  <th>Nome da Mãe</th>
                                  <th>Genero</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse   ($enrollment as $i => $item)
                                  <tr>
                                   <td>{{$i+1}}</td>
                                      <td>{{$item->enrollment_number}}</td>
                                      <td>{{ $item->student->name }}</td>
                                      <td>{{ $item->student->father_name }}</td>
                                      <td>{{ ($item->student->mother_name) }}</td>
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
     
     @else
       
       <div class="row px-5 w-100" style="padding: 0 1rem">
                      <table class="table border-0">
                          <thead>
                              <tr>
                                 <th>#</th>
                                  <th>Nº Processo</th>
                                  <th>Nome</th>
                                  <th>Identidade</th>
                                  <th>Genero</th>
                                  <th>Endereço</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse   ($enrollment as $i => $item)
                                  <tr>
                                   <td>{{$i+1}}</td>
                                      <td>{{$item->enrollment_number}}</td>
                                      <td>{{ $item->student->name }}</td>
                                      <td>{{ $item->student->identity }}</td>
                                       <td>{{ ($item->student->gender  == "M" ? "Masculino" : "Femenino") }}</td>
                                        <td>{{ ($item->student->address) }}</td>
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
    @endif
    

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
   
 </body>
 </html>
 