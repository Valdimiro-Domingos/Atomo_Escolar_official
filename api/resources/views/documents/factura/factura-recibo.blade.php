    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> {{ $dados['arquivo'] }} </title>
          <link rel="stylesheet" type="text/css" href="{{ public_path('bootstrap.css') }}">
            <style>
                .br span{
                    display: block;
                }
            </style>
    </head>

    <body>

        @php
            $matriculaExisti = false;
        @endphp
        <!-- analisar se existe categoria matricula esta fatura -->
        @foreach ($dados['item'] as $key => $item)
            @if( $item->article->article_category->designation == 'Matrículas')
                @php
                    $matriculaExisti = true;
                @endphp
              @for ($i = 0; $i < 2; $i++)
                <div class="container">

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
                                <p><strong>{{ $dados['dados']['invoice_number'] }}</strong></p> </div>
                            <div class="col-xs-6">
                                <p>Data : <strong>{{ $dados['dados']->created_at }}</strong></p>
                                <p>Operador: <strong>{{ ($dados['dados']->user->name )}}</strong></p>
                            </div>
                        </div>
                    <hr>

                    <div class="row">
                        <div class="col-xs-6">
                            <p>Estudante: <strong>{{ $dados['dados']->student->name }}</strong></p>
                            <p>Identidadede: <strong>{{ $dados['dados']->student->identity }}</strong> </p>
                            <p>Ano lectivo: <strong>{{ $dados['enrollement']->school_year->designation }}</strong></p>
                        </div>

                        <div class="col-xs-6 br">
                            <p>Classe: <strong>{{ $dados['enrollement']->classe->designation  }}</strong></span>
                            @if($dados['enrollement']->course->designation != 'Sem Curso')
                            <p>Curso: <strong>{{ $dados['enrollement']->course->designation }}</strong></p>
                            @endif
                            <p>Turma: <strong>{{ $dados['enrollement']->turma->designation }}</strong></span>
                            <p>Sala: <strong>{{ $dados['enrollement']->class_room->designation }}</strong> </p>
                        </div>
                    </div>
                    <hr>
                    <!-- -->
                    <div class="row">

                        <div class="col-xs-6">
                            <p>Moeda: <b>{{ $dados['dados']->coin }}</b></p>

                            <p>Formas de Pagamento:
                                <b>{{ \App\Models\FormOfPayment::find($dados['dados']->form_of_payment_id)->designation }}</b>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div>
                            <table class="table border-0">
                                <tr>
                                    <th>Categoria</th>
                                    <th>Artigo</th>
                                    <th>Qtd</th>
                                    <th>Preo Unitário</th>
                                    <th>Desc %</th>
                                    <th>Multa</th>
                                    <th>Total</th>
                                </tr>

                                @php
                                $valor_transporte = 0;
                                $valor_desconto = 0;
                                $valor_imposto = 0;
                                $valor_transporte = 0;
                                $count = 0;
                                @endphp
                                @foreach ($dados['item'] as $key => $item)
                                @php
                                $valor_desconto += $item->discount;
                                $valor_transporte += $item->paid;
                                $valor_imposto += $item->rate;
                                @endphp
                                <tr>
                                    <td>{{ $item->article->article_category->designation }} </td>
                                    <td>{{ $item->article->designation }} </td>
                                    <td>{{ number_format($item->qtd, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->article->price, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->discount, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->rate, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->paid, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </table>

                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-xs-7"> </div>
                        <div class="col-xs-4">
                        <b>SUMÁRIO</b>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><b>Total Ilíquido</b></td>
                                    <td><b>{{ number_format($valor_transporte, 2, ',', '.') }}</b></td>
                                </tr>
                                <tr>
                                    <td><b>Total Desconto</b></td>
                                    <td><b>{{ number_format($valor_desconto, 2, ',', '.') }}</b></td>
                                </tr>
                                <tr>
                                    <td><b>Total Multa</b></td>
                                    <td><b>{{ number_format($valor_desconto, 2, ',', '.') }}</b></td>
                                </tr>
                                <tr>
                                    <td><b>Total Imposto</b></td>
                                    <td><b>{{ number_format($valor_imposto, 2, ',', '.') }}</b></td>
                                </tr>
                                <tr>
                                    <td><b>Total a pagar</b></td>
                                    <td><b>{{ number_format($valor_transporte, 2, ',', '.') }}</b></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>

                    </div>



                <div style="padding: 20px 0 10px;" class="row px-0 mx-0">
                    <table style="border-collapse: collapse; width: 100%; " border="0">
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
                <div class="row p-2" style="text-align: center;padding: 10px 0;">
                    <p class="text-center" style="font-size: 8pt!important;with:100%;">{{  'Processado por programa validado 474/AGT/' . date('Y') . ' ' . config('app.name'); }}</p>
                </div>
             @endfor
           @endif
        @endforeach


        <!-- fatura recibo normal -->
        @if(!$matriculaExisti)
            <div class="container">
                <div class="row">
                        <div class="col-xs-6 text-left">
                    @if ($dados['empresa']->logo)
                        <img width="120px" height="80px" style="object-fit: cover;" src="{{public_path('images/company/'.$dados['empresa']->logo)}}" alt="{{ $dados['empresa']->designation }}">
                        <br>
                        <div class="p-2"></div>
                    @else
                        <img width="150px" height="60px" style="object-fit: contain;" src="{{public_path('images/company/logo.png')}}" alt="{{ $dados['empresa']->designation }}">
                        <br>
                        <div class="p-2"></div>
                    @endif
                </div>

                    <div class="col-xs-6">
                        <h4>{{ $dados['empresa']->designation }}</h4>
                        <p>NIF: {{ $dados['empresa']->nif }}</p>
                        <p>Telefone: {{ $dados['empresa']->contact ? $dados['empresa']->contact : '--' }}</p>
                        <p>Email: {{ $dados['empresa']->email ? $dados['empresa']->email : '--' }}</p>
                        <p>Endereço: {{ $dados['empresa']->address ? $dados['empresa']->address : '--' }}</p>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <p class="text-sm">FACTURA RECIBO -  <strong> {{ $dados['dados']['invoice_number'] }}</strong></p>
                    </div>
                    <div class="col-xs-6">
                        <p><strong>Data de Emissão:</strong> {{ date('d-m-Y', strtotime($dados['dados']->date_of_issue)) }}</p>
                        <p><strong>Operador:</strong> {{ ($dados['dados']->user->name )}}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <p>Estudante: <strong>Sr(a). {{ $dados['dados']->student->name }}</strong></p>
                        <p>Identidadede: <strong>{{ $dados['dados']->student->identity }}</strong> </p>
                    </div>

                    <div class="col-xs-6">
                        <p><strong>Ano lectivo:</strong> {{ $dados['enrollement']->school_year->designation }}</p>
                        <p><strong>Classe:</strong> {{ $dados['enrollement']->classe->designation  }}</p>
                        @if($dados['enrollement']->course->designation != 'Sem Curso')
                        <p>Curso: <strong>{{ $dados['enrollement']->course->designation }}</strong></p>
                        @endif
                        <p><strong>Turma:</strong> {{ $dados['enrollement']->turma->designation }}</p>
                        <p><strong>Sala:</strong> {{ $dados['enrollement']->class_room->designation }}</p>
                    </div>
                </div>
            </div>
            <hr>
            <!-- -->
            <div class="row">

                <div class="col-md-6">
                    <p><b>Moeda:</b> {{ $dados['dados']->coin }}</p>
                    <p><b>Formas de Pagamento:</b>
                        {{ \App\Models\FormOfPayment::find($dados['dados']->form_of_payment_id)->designation }}
                    </p>
                </div>
            </div>

            <div class="row">
                <div>
                    <table class="table border-0">
                        <tr>
                            <th>Categoria</th>
                            <th>Artigo</th>
                            <th>Qtd</th>
                            <th>Preo Unitário</th>
                            <th>Desc %</th>
                            <th>Multa</th>
                            <th>Total</th>
                        </tr>

                        @php
                        $valor_transporte = 0;
                        $valor_desconto = 0;
                        $valor_imposto = 0;
                        $valor_transporte = 0;
                        $count = 0;
                        @endphp
                        @foreach ($dados['item'] as $key => $item)
                        @php
                        $valor_desconto += $item->discount;
                        $valor_transporte += $item->paid;
                        $valor_imposto += $item->rate;
                        @endphp
                        <tr>
                            <td>{{ $item->article->article_category->designation }} </td>
                            <td>{{ $item->article->designation }} </td>
                            <td>{{ number_format($item->qtd, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->article->price, 2, ',', '.') }}</td>
                            <td>{{ number_format($item->discount, 2, ',', '.') }}</td>
                            <td>{{ number_format($item->rate, 2, ',', '.') }}</td>
                            <td>{{ number_format($item->paid, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </table>

                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-xs-7"> </div>
                <div class="col-xs-4">
                <b>SUMÁRIO</b>
                <table class="table">
                    <tbody>
                        <tr>
                            <td><b>Total Ilíquido</b></td>
                            <td><b>{{ number_format($valor_transporte, 2, ',', '.') }}</b></td>
                        </tr>
                        <tr>
                            <td><b>Total Desconto</b></td>
                            <td><b>{{ number_format($valor_desconto, 2, ',', '.') }}</b></td>
                        </tr>
                        <tr>
                            <td><b>Total Multa</b></td>
                            <td><b>{{ number_format($valor_desconto, 2, ',', '.') }}</b></td>
                        </tr>
                        <tr>
                            <td><b>Total Imposto</b></td>
                            <td><b>{{ number_format($valor_imposto, 2, ',', '.') }}</b></td>
                        </tr>
                        <tr>
                            <td><b>Total a pagar</b></td>
                            <td><b>{{ number_format($valor_transporte, 2, ',', '.') }}</b></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="footer p-2 col-12" style="text-align: center;padding: 20px 0 0;">
            <p class="text-center" style="font-size: 8pt!important;with:100%;">{{  'Processado por programa validado 474/AGT/' . date('Y') . ' ' . config('app.name'); }}</p>
        </div>
        @endif


    </body>

    </html>
