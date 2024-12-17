<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> {{ $dados['dados']->enrollment_number }} </title>
  <link rel="stylesheet" href="./bootstrap.css">
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

    <body>


        <div class="header">
    
        </div>
        
        <div class="footer">
            <p class="text-center"> </p>
        </div>
    
    
        <div class="container">
            <div class="row">
                <div class="col-xs-6"> 
                    <img class="lazy" width="150" src="./logo.png" data-src="logo.png" alt="Imagem">
                </div>

                <div class="col-xs-6">
                    <h2>{{ $dados['empresa']->designation }}</h2>
                    <p>NIF: {{ $dados['empresa']->nif }}</p>
                    <p>Telefone: {{ $dados['empresa']->contact ? $dados['empresa']->contact : '--' }}</p>
                    <p>Email: {{ $dados['empresa']->email ? $dados['empresa']->email : '--' }}</p>
                    <p>Endereço: {{ $dados['empresa']->address ? $dados['empresa']->address : '--' }}</p>
                </div>

                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <p><strong>FACTURA/RECIBO {{ $dados['dados']['invoice_receipt']['invoice_number'] }}</strong></p>
                        {{-- <p><strong>{{ $dados['doc_via'] }}</strong></p> --}}
                    </div>
                    <div class="col-xs-6">
                        <p><strong>Data de Emissão: {{ date('d-m-Y', strtotime($dados['dados']->date_of_issue)) }}</strong></p>
                        <p><strong>Operador:</strong> {{ $dados['dados']->name }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <p><strong>Sr(a). {{ $dados['dados']->student->name }}</strong></p>
                        <p><strong>NIF:</strong> {{ $dados['dados']->student->identity }}</p>
                    </div>
        
                    <div class="col-xs-6">
                        <p><b>Moeda:</b> {{ $dados['dados']->coi }}</p>
                        <p><b>Formas de Pagamento:</b>
                            {{ \App\Models\FormOfPayment::find($dados['dados']['invoice_receipt']['form_of_payment_id'])->designation }}</p>
                    </div>
        
                </div>
                <hr>
            </div>
        </div>
</body>
</html>
