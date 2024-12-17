function addItemStock() {

    if ((parseInt($('[name=item-artigo_id]').val()))) {

        var id = $('[name=item-artigo_id]').val();
        var codigo = $('[name=item-codigo]').val();
        var designacao = $('[name=item-artigo_designacao').val();
        var qtd = $('[name=item-artigo_qtd]').val();
        var html = '<tr>\n\
            <td><input type="hidden" class="hidden item-artigo_id" value="' + id + '">\n\
            <input type="text" class="form-control input-sm item-codigo" value="' + codigo + '" readonly></td>\n\
            <td><input type="text" class="form-control input-sm item-artigo_designacao" value="' + designacao + '" readonly></td>\n\
            <td><input type="number" step="any" style="text-align:center;" class="form-control input-sm item-artigo_qtd" value="' + qtd + '" readonly></td>\n\
            <td><button class="btn btn-danger btn-block" onClick="removerItemArtigo(this);">\n\
            <i class="fa fa-trash"></i></button></td></tr>';
        $('#table-item tbody').prepend(html);
        $('[name=item-artigo_id]').val(0);
        $('[name=item-artigo_designacao]').val('').focus();
        $('[name=item-artigo_qtd]').val('0');
    } else {
        alert("Dados Inválidos")
    }
    return false;
}


function finalizarStock() {

    var item_id = new Array();
    $("input[class*='item-artigo_id']").each(function() {
        item_id.push($(this).val());
    });

    var item_codigo = new Array();
    $("input[class*='item-codigo']").each(function() {
        item_codigo.push($(this).val());
    });


    var item_designacao = new Array();
    $("input[class*='item-artigo_designacao']").each(function() {
        item_designacao.push($(this).val());
    });

    var item_qtd = new Array();
    $("input[class*='item-artigo_qtd']").each(function() {
        item_qtd.push($(this).val());
    });



    if (item_id.length) {
        $.ajax({
            url: base_url + '/stock/store',
            type: 'POST',
            dataType: 'html', //JSON
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                //Cabecalho
                _token: $('meta[name="csrf-token"]').attr('content'),
                data: $('[name=data]').val(),
                serie: $('[name=serie]').val(),
                numero: $('[name=numero]').val(),
                ref_doc: $('[name=ref_doc]').val(),
                armazem: $('[name=armazem]').val(),
                fornecedor_id: $('[name=fornecedor_id]').val(),
                fornecedor_nome: $('[name=fornecedor_nome]').val(),
                endereco: $('[name=endereco]').val(),
                descricao: $('[name=descricao]').val(),
                //Artigo
                item: {
                    item_id: item_id,
                    item_codigo: item_codigo,
                    item_designacao: item_designacao,
                    item_qtd: item_qtd,
                }
            },
            beforeSend: function(data) {},
            success: function(data) {
                var data_json = JSON.parse(data);
                if (data_json['status'] == 200) {
                    location.href = base_url + '/stock';
                }
            }
        });
    } else {
        alert("Operação Inválida");
    }
    return false;

}