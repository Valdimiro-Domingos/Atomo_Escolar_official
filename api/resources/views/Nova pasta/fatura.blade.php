<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title> {{ $dados['dados']->id }}</title>´
    <link rel="stylesheet" href="{{ asset('public/assets/pdf/bootstrap.css') }}">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            counter-reset: section;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight: bold;
        }

        .container {
            margin-top: 20px;
        }

        .table {
            margin-top: 20px;
        }

        .header,
        .footer {
            width: 100%;
            position: fixed;
            padding: 10px 20px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .header {
            top: 0;
        }

        .footer {
            bottom: 0;
        }

        .header p,
        .footer p {
            margin: 0;
        }


        .paginacao::before {
            counter-increment: section;
            content: counter(section);
        }
    </style>
</head>

<body>


    <div class="header">

    </div>
    <div class="footer">
        <p class="text-center"></b>
          <span class="paginacao"></span> / {{ceil(count($dados['item'])/5)}}</p>

    </div>


    <div class="container">


        <div class="row">
            <div class="col-xs-6">
                <img height="100" width="250" src="{{ asset("public/upload/{$dados['empresa']->logo}") }}"
                    alt="">
            </div>
            <div class="col-xs-6">
                <h4>{{ $dados['empresa']->designation }}</h4>
                <p><strong>NIF: {{ $dados['empresa']->nif }}</strong></p>
                <p> {{ $dados['empresa']->contact }}</p>
                <p> {{ $dados['empresa']->email }}</p>
                <p> {{ $dados['empresa']->address }}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            @if (isset($dados['dados']->enrollment_number))
                <div class="col-xs-4">
                    <p><strong>FACTURA {{ $dados['dados']->enrollment_number }}</strong></p>
                    {{--  <p><strong>{{ $dados['doc_via'] }}</strong></p>  --}}
                </div>
                <div class="col-xs-4">
                    <p><strong>Documento de origem: {{ $dados['dados']->enrollment_number }}</strong></p>
                </div>
                <div class="col-xs-4">
                    <p><strong>Data de Emissão: {{ date('d-m-Y', strtotime($dados['dados']->date_of_issue)) }}</strong></p>
                    <p><strong>Operador: {{ ($dados['dados']->name ? $dados['dados']->name : 'Fucionario') }}</strong></p>
                </div>
            @else
                <div class="col-xs-6">
                    <p><strong>FACTURA {{ $dados['dados']->enrollment_number }}</strong></p>
                    {{--  <p><strong>{{ $dados['doc_via'] }}</strong></p>  --}}
                </div>

                <div class="col-xs-6">
                    <p><strong>Data de Emissão: {{ date('d-m-Y', strtotime($dados['dados']->date_of_issue)) }}</strong></p>
                    <p><strong>Operador: {{ $dados['dados']->utilizador_nome }}</strong></p>
                </div>
            @endif
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-6">
                <p><strong>Sr(a).{{ $dados['dados']->student->name }}</strong></p>
                <p><strong>NIF:</strong> {{ $dados['dados']->student->identity  }}</p>
            </div>

            <div class="col-xs-6">
                <p><b>Moeda:</b> {{ \App\Moeda::find($dados['dados']->moeda_id)->designacao }}</p>
                <p><b>Formas de Pagamento:</b>
                    {{ \App\FormasPagamento::find($dados['dados']->formapagamento_id)->designacao }}</p>
            </div>

        </div>
        <hr>

        <div class="row">

            <div>
                <table class="table border-0">
                    <thead>
                        <tr>
                            <th>Artigo</th>
                            <th>Qtd</th>
                            <th>Preço Unitário</th>
                            <th>Desc %</th>
                            <th>Taxa</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php  $valor_transporte = 0;
                        $count = 0;  @endphp 
                        @foreach ($dados['item'] as $key => $item)
                            @php 
                            $valor_transporte += $item->subtotal;  @endphp
                            @if ($key == 16)
                                <tr>
                                    <td colspan="6" style="text-align: right"><b>Valor à transportar:
                                            {{ number_format($valor_transporte, 2, ',', '.') }}<b> </td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="text-align: right"><b>Valor transportado :
                                            {{ number_format($valor_transporte, 2, ',', '.') }}<b> </td>
                                </tr>
                                @php   $count = 1;   @endphp
                            @elseif($count == 29)
                                @php   $count = 1;   @endphp
                                <tr>
                                    <td colspan="6" style="text-align: right"><b>Valor à transportar:
                                            {{ number_format($valor_transporte, 2, ',', '.') }}<b> </td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="text-align: right"><b>Valor transportado :
                                            {{ number_format($valor_transporte, 2, ',', '.') }}<b> </td>
                                </tr>
                            @elseif($key > 16)
                                @php   $count++;   @endphp
                            @endif
                            <tr>
                                <td>{{ $item->designacao }} </td>
                                <td>{{ number_format($item->qtd, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->preco, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->desconto, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->imposto_taxa, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->subtotal, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>


        <div class="row">
            <p>Os bens/serviços, foram colocados a disposição
                do
                adquirente na data:
                {{ date('d-m-Y', strtotime($dados['dados']->data)) }} e local do
                documento.
            </p>
            <hr>
            <div class="col-xs-7">
                <b> RESUMO DE IMPOSTOS</b>
                <hr>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Imposto</th>
                            <th>Taxa %</th>
                            <th>Incidência</th>
                            <th>V. Imposto</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $valor_transporte = 0;
                            $count = 0;
                         @endphp
                        @foreach (\App\Imposto::all() as $imposto)
                            @php 
                                $isvalid = 0;
                                $imposto_tipo = $imposto->tipo;
                                $imposto_taxa = $imposto->taxa;
                                $incidencia = 0;
                                $v_imposto = 0;
                                $motivo = $imposto->codigo . '-' . $imposto->motivo;
                             @endphp
                            @foreach ($dados['item'] as $item)
                                @if ($imposto->id == $item->imposto_id)
                                    @php 
                                        $isvalid = 1;
                                        $incidencia += $item->subtotal;
                                        $v_imposto += ($item->subtotal * $item->imposto_taxa) / 100;
                                        
                                     @endphp
                                @endif
                            @endforeach

                            @if ($isvalid == 1)
                                @php 
                                    $isvalid = 0;
                                 @endphp
                                <tr>
                                    <td>{{ $imposto_tipo }}</td>
                                    <td>{{ number_format($imposto_taxa, 2, ',', '.') }}</td>
                                    <td>{{ number_format($incidencia, 2, ',', '.') }} </td>
                                    <td>{{ number_format($v_imposto, 2, ',', '.') }}
                                    </td>
                                    <td>{{ $motivo }}</td>
                                </tr>
                            @endif
                        @endforeach
                        @if ($dados['dados']->retencao != 0)
                            <tr>
                                <td>IIS</td>>
                                <td>{{ 6.5 }}</td>
                                <td>{{ number_format($item->subtotal, 2, ',', '.') }} </td>
                                <td>{{ number_format($dados['dados']->retencao, 2, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        @endif


                    </tbody>
                </table>

            </div>
            <div class="col-xs-4">
                <b>SUMÁRIO</b>
                <table class="table">
                    <tbody>
                        <tr>
                            <td><b>Total Ilíquido</b></td>
                            <td><b>{{ number_format($dados['dados']->subtotal, 2, ',', '.') }}</b></td>
                        </tr>
                        <tr>
                            <td><b>Total Desconto</b></td>
                            <td><b>{{ number_format($dados['dados']->desconto, 2, ',', '.') }}</b></td>
                        </tr>
                        <tr>
                            <td><b>Total Imposto</b></td>
                            <td><b>{{ number_format($dados['dados']->imposto, 2, ',', '.') }}</b></td>
                        </tr>
                        <tr>
                            <td><b>Impostos Retidos</b></td>
                            <td><b>{{ number_format($dados['dados']->retencao, 2, ',', '.') }}</b></td>
                        </tr>
                        <tr>
                            <td><b>Total a pagar</b></td>
                            <td><b>{{ number_format($dados['dados']->total, 2, ',', '.') }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        @if (count($dados['bancos']))
            <div class="row">
                <div class="col-xs-12">
                    <b> COORDENADAS BANCÁRIAS</b>
                    @foreach ($dados['bancos'] as $item)
                        <p>{{ $item->nome }} , Nº {{ $item->numero }}, IBAN {{ $item->iban }}</p>
                    @endforeach
                </div>

            </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                <b> OBSERVAÇÕES</b>

                <p>{{ $dados['dados']->observacao }}</p>
            </div>

        </div>

    </div>
</body>

</html>
