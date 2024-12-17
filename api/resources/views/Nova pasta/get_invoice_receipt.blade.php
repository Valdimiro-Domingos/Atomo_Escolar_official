<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Meu PDF</title>
    <style>
        .page {
            width: 180mm; /* Ajuste devido às margens de 15mm em cada lado */
            height: 297mm;
            margin: 20mm auto; /* Margem de 20mm acima e abaixo, 0 nas laterais */
            background-color: white; /* Cor de fundo para simular a folha */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra para destacar a folha */
            padding: 10mm; /* Espaçamento interno para manter o conteúdo longe das bordas */
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
    <div class="page">
        <div class="flex-container">
            <div class="item item1">
                <div class="item-part1">Item 1 - Parte 1</div>
                <div class="item-part2">{{ $invoice_receipt->company->designation}}</div>
            </div>
            <div class="item">Item 2</div>
            <div class="item">Item 3</div>
            <!-- Adicione quantos itens quiser -->
        </div>
    </div>


</body>

</html>
 