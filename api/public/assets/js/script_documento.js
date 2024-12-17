function getFornecedor() {
    var dados = {
        valor: $('[name=fornecedor_id]').val()
    }
    $.get(base_url + '/api/fornecedor/' + dados.valor,
        function(data) {
            if (data) {
                data_json = JSON.parse(data);
                if (data_json.length) {
                    $('[name=fornecedor_nome]').val(data_json[0]['designacao']);
                    $('[name=contribuinte]').val(data_json[0]['contribuinte']);
                    $('[name=endereco]').val(data_json[0]['endereco']);
                }

            }
        });

}

function getCliente() {
    var dados = {
        valor: $('[name=cliente_id]').val()
    }
    $.get(base_url + '/api/cliente/' + dados.valor,
        function(data) {
            if (data) {
                data_json = JSON.parse(data);
                if (data_json.length) {
                    $('[name=cliente_nome]').val(data_json[0]['designacao']);
                    $('[name=contribuinte]').val(data_json[0]['contribuinte']);
                    $('[name=endereco]').val(data_json[0]['endereco']);
                } else {
                    $('[name=cliente_nome]').val('Consumidor Final');
                    $('[name=contribuinte]').val('999999999');
                    $('[name=endereco]').val('Consumidor Final');
                }

            }
        });

}

//***POS***/
function getArtigos() {
    var dados = {
        valor: $('[name=artigo-chave]').val()
    }
    $.get(base_url + '/api/artigos/' + dados.valor,
        function(data) {
            if (data) {
                data_json = JSON.parse(data);
                $('#card-artigo').html('');
                for (var i = 0; i < data_json.length; i++) {
                    var artigo_id = data_json[i]['artigo'].id;
                    var artigo_codigo = data_json[i]['artigo'].codigo;
                    var artigo_designacao = data_json[i]['artigo'].designacao;
                    var artigo_imagem_1 = data_json[i]['artigo'].imagem_1;
                    var artigo_preco = parseFloat(data_json[i]['artigo'].preco);
                    var artigo_codigo_barra = data_json[i]['artigo'].codigo_barra;
                    var artigo_qtd = data_json[i]['artigo'].qtd;
                    var artigo_unidade = data_json[i]['artigo'].unidade;
                    var artigo_desconto = data_json[i]['artigo'].desconto;

                    var retencao_id = data_json[i]['retencao'].id;
                    var retencao_designacao = data_json[i]['retencao'].designacao;
                    var retencao_taxa = data_json[i]['retencao'].taxa;

                    var imposto_id = data_json[i]['imposto'].id;
                    var imposto_designacao = data_json[i]['imposto'].designacao;
                    var imposto_tipo = data_json[i]['imposto'].tipo;
                    var imposto_codigo = data_json[i]['imposto'].codigo;
                    var imposto_taxa = data_json[i]['imposto'].taxa;
                    var imposto_motivo = data_json[i]['imposto'].motivo;


                    var html = '<div class="col-xl-4 col-sm-6">\n\
                                      <div class="card">\n\
                                          <div class="card-body">\n\
                                              <div class="product-img position-relative">\n\
                                              <a onclick="setArtigo(' + artigo_id + ');" href="#"> <img src="' + base_url + '/public/upload/' + artigo_imagem_1 + '" alt="" class="img-fluid mx-auto d-block"> </a>\n\
                                              </div>\n\
                                              <div class="mt-4 text-center">\n\
                                                  <h5 class="mb-3 text-truncate"><a onclick="setArtigo(' + artigo_id + ');" href="#" class="text-dark">' + artigo_designacao + '</a>   </h5>\n\
                                                  <p class="text-muted">' + artigo_codigo_barra + '</p>\n\
                                                  <h5 class="my-0"><b>' + artigo_preco.toFixed(2) + '</b>\n\
                                                  </h5>\n\
                                              </div>\n\
                                          </div>\n\
                                      </div>\n\
                                  </div>';
                    $('#card-artigo').prepend(html);
                }

                if (data_json.length == 1) {
                    setArtigo(data_json[0]['artigo'].id)
                }
            }
        });
}


function setArtigo(valor) {
    validar = 1;
    if ($("input[class*='item-artigo_id']").length) {
        $("input[class*='item-artigo_id']").each(function() {

            if (parseInt($(this).val()) == valor) {
                validar = 0;

            }
        });
    }


    if (validar) {
        $.get(base_url + '/api/artigo/' + valor,
            function(data) {
                if (data) {
                    data_json = JSON.parse(data);

                    var artigo_id = data_json['artigo'].id;
                    var artigo_codigo = data_json['artigo'].codigo;
                    var artigo_designacao = data_json['artigo'].designacao;
                    var artigo_imagem_1 = data_json['artigo'].imagem_1;
                    var artigo_preco = parseFloat(data_json['artigo'].preco);
                    var artigo_codigo_barra = data_json['artigo'].codigo_barra;
                    var artigo_qtd = data_json['artigo'].qtd;
                    var artigo_unidade = data_json['artigo'].unidade;
                    var artigo_desconto = data_json['artigo'].desconto;

                    var retencao_id = data_json['retencao'].id;
                    var retencao_designacao = data_json['retencao'].designacao;
                    var retencao_taxa = data_json['retencao'].taxa;

                    var imposto_id = data_json['imposto'].id;
                    var imposto_designacao = data_json['imposto'].designacao;
                    var imposto_tipo = data_json['imposto'].tipo;
                    var imposto_codigo = data_json['imposto'].codigo;
                    var imposto_taxa = data_json['imposto'].taxa;
                    var imposto_motivo = data_json['imposto'].motivo;

                    var html = '<tr>\n\
            <td>' + artigo_designacao + '</td>\n\
            <td>' + artigo_preco.toFixed(2) + '</td>\n\
            <td>\n\
            <input onkeyup="calcularItemArtigoPos(this);" type="text" style="text-align:center; width: 60px" class="form-control mask-money item-artigo_qtd" value="1">\n\
            </td>\n\
            <td>\n\
            <input  onchange="calcularItemArtigoPos(this);" onkeyup="calcularItemArtigoPos(this);" type="number" min="0" max="100" style="text-align:center; width: 60px" class="form-control mask-money item-artigo_desconto" value="0">\n\
            </td>\n\
            <td class="item-total_linha">' + artigo_preco.toFixed(2) + '</td>\n\
            <td><a class="action-icon text-danger" onclick="removerItemArtigo(this);"> <i class="fa fa-trash"></i></a>\n\
            <input type="hidden" class="hidden item-artigo_id" value="' + artigo_id + '">\n\
            <input type="hidden" class="hidden item-artigo_designacao" value="' + artigo_designacao + '" readonly>\n\
            <input type="hidden" style="text-align:center;" step="any" class="hidden item-artigo_preco" value="' + artigo_preco + '" readonly>\n\
            <input type="hidden" class="hidden item-retencao_id" value="' + retencao_id + '">\n\
            <input type="hidden" class="hidden item-retencao_designacao" value="' + retencao_designacao + '">\n\
            <input type="hidden" class="hidden item-retencao_taxa" value="' + retencao_taxa + '">\n\
            <input type="hidden" class="hidden item-imposto_id" value="' + imposto_id + '">\n\
            <input type="hidden" class="hidden item-imposto_tipo" value="' + imposto_tipo + '">\n\
            <input type="hidden" class="hidden item-imposto_codigo" value="' + imposto_codigo + '">\n\
            <input type="hidden" class="hidden item-imposto_designacao" value="' + imposto_designacao + '">\n\
            <input type="hidden" class="hidden item-imposto_motivo" value="' + imposto_motivo + '">\n\
            <input type="hidden" style="text-align:center;" step="any" class="hidden item-imposto_taxa" value="' + imposto_taxa + '" readonly>\n\
            <input type="hidden" style="text-align:right;" step="any" class="hidden item-subtotal" value="' + artigo_preco + '" readonly>\n\
            </td>\n\
        </tr>';
                    $('#table-artigo').prepend(html);
                    calcularItem();
                }
            });
    }

}

function calcularItemArtigoPos(e) {
    var i = $('#table-artigo tbody .item-artigo_qtd').index(e);
    var preco = parseFloat($('#table-artigo tbody .item-artigo_preco:eq(' + i + ')').val());
    var qtd = parseFloat($('#table-artigo tbody .item-artigo_qtd:eq(' + i + ')').val());

    if (Number.isInteger(qtd)) {

        var total = (preco * qtd);
        $('#table-artigo tbody .item-subtotal:eq(' + i + ')').val(total.toFixed(2));
        $('#table-artigo tbody .item-total_linha:eq(' + i + ')').html(total.toFixed(2));
    } else {
        $('#table-artigo tbody .item-subtotal:eq(' + i + ')').val(0);
        $('#table-artigo tbody .item-total_linha:eq(' + i + ')').html(0);
    }
    calcularItem();
}

/***END POS***/

function getArtigo() {
    var dados = {
        valor: $('[name=item-artigo]').val()
    }
    $.get(base_url + '/api/artigo/' + dados.valor,
        function(data) {
            if (data) {
                data_json = JSON.parse(data);
                $('[name=item-artigo_id]').val(data_json['artigo'].id);
                $('[name=item-codigo]').val(data_json['artigo'].codigo);
                $('[name=item-artigo_designacao]').val(data_json['artigo'].designacao);
                $('[name=item-artigo_preco]').val(parseFloat(data_json['artigo'].preco).toFixed(2));
                $('[name=item-artigo_qtd]').val(1);
                $('[name=item-unidade]').val(data_json['artigo'].unidade);
                $('[name=item-artigo_desconto]').val(0);
                $('[name=item-retencao_id]').val(parseInt(data_json['retencao'].id));
                $('[name=item-retencao_designacao]').val(data_json['retencao'].designacao);
                $('[name=item-retencao_taxa]').val(data_json['retencao'].taxa);
                $('[name=item-imposto_id]').val(parseInt(data_json['imposto'].id));
                $('[name=item-imposto_designacao]').val((data_json['imposto'].designacao));
                $('[name=item-imposto_tipo]').val((data_json['imposto'].tipo));
                $('[name=item-imposto_codigo]').val((data_json['imposto'].codigo));
                $('[name=item-imposto_taxa]').val((data_json['imposto'].taxa));
                $('[name=item-imposto_motivo]').val((data_json['imposto'].motivo));
                $('[name=item-subtotal]').val(parseFloat(data_json['artigo'].preco).toFixed(2));

            }
        });
}


function calcularItemArtigo() {
    var preco = parseFloat($('[name=item-artigo_preco]').val());
    var qtd = parseFloat($('[name=item-artigo_qtd]').val());
    var desconto = parseInt($('[name=item-artigo_desconto]').val());

    if (desconto >= 0 && desconto <= 100) {
        var resultado = (preco * qtd - (preco * desconto / 100));
        $('[name=item-subtotal]').val(resultado.toFixed(2));
    } else {
        if (desconto != null) {
            $('[name=item-artigo_desconto]').val(0)
            alert('Desconto Inválido');
        }

    }

}


function removerItemArtigo(e) {
    e.parentNode.parentNode.remove();
    calcularItem();
}




$('#form-item').on('submit', function() {

    if ((parseFloat($('[name=item-subtotal]').val()))) {
        var artigo_id = $('[name=item-artigo_id]').val();
        var artigo_designacao = $('[name=item-artigo_designacao').val();
        var artigo_qtd = $('[name=item-artigo_qtd]').val();
        var artigo_preco = $('[name=item-artigo_preco]').val();
        var artigo_desconto = $('[name=item-artigo_desconto]').val();

        var retencao_id = $('[name=item-retencao_id]').val();
        var retencao_designacao = $('[name=item-retencao_designacao]').val();
        var retencao_taxa = $('[name=item-retencao_taxa]').val();

        var imposto_id = $('[name=item-imposto_id]').val();
        var imposto_tipo = $('[name=item-imposto_tipo]').val();
        var imposto_codigo = $('[name=item-imposto_codigo]').val();
        var imposto_designacao = $('[name=item-imposto_designacao]').val();
        var imposto_motivo = $('[name=item-imposto_motivo]').val();

        var imposto_taxa = $('[name=item-imposto_taxa]').val();
        var subtotal = $('[name=item-subtotal]').val();
        var html = '<tr>\n\
            <td><input type="hidden" class="hidden item-artigo_id" value="' + artigo_id + '">\n\
            <input type="text" class="form-control input-sm item-artigo_designacao" value="' + artigo_designacao + '" readonly></td>\n\
            <td><input type="number" step="any" style="text-align:center;" class="form-control input-sm item-artigo_qtd" value="' + artigo_qtd + '" readonly></td>\n\
            <td><input type="number" style="text-align:center;" step="any" class="form-control input-sm item-artigo_preco" value="' + artigo_preco + '" readonly></td>\n\
            <td><input type="number" style="text-align:center;" step="any" class="form-control input-sm item-artigo_desconto" value="' + artigo_desconto + '" readonly></td>\n\
            <td><input type="hidden" class="form-control input-sm item-retencao_id" value="' + retencao_id + '">\n\
            <input type="hidden" class="form-control input-sm item-retencao_designacao" value="' + retencao_designacao + '">\n\
            <input type="hidden" class="form-control input-sm item-retencao_taxa" value="' + retencao_taxa + '">\n\
            <input type="hidden" class="form-control input-sm item-imposto_id" value="' + imposto_id + '">\n\
            <input type="hidden" class="form-control input-sm item-imposto_tipo" value="' + imposto_tipo + '">\n\
            <input type="hidden" class="form-control input-sm item-imposto_codigo" value="' + imposto_codigo + '">\n\
            <input type="hidden" class="form-control input-sm item-imposto_designacao" value="' + imposto_designacao + '">\n\
            <input type="hidden" class="form-control input-sm item-imposto_motivo" value="' + imposto_motivo + '">\n\
            <input type="number" style="text-align:center;" step="any" class="form-control input-sm item-imposto_taxa" value="' + imposto_taxa + '" readonly></td>\n\
            <td><input type="number" style="text-align:right;" step="any" class="form-control input-sm item-subtotal" value="' + subtotal + '" readonly></td>\n\
            <td><button class="btn btn-danger btn-block" onclick="removerItemArtigo(this);">\n\
            <i class="fa fa-trash"></i></button></td></tr>';
        $('#table-item tbody').prepend(html);
        $('[name=item-artigo_id]').val(0);
        $('[name=item-artigo_designacao]').val('').focus();
        $('[name=item-unidade]').val('');
        $('[name=item-artigo_preco]').val('0');

        $('[name=item-retencao_id]').val('0');
        $('[name=item-retencao_designacao]').val('');
        $('[name=item-retencao_taxa]').val('0');

        $('[name=item-imposto_id]').val('0');
        $('[name=item-imposto_tipo]').val('');
        $('[name=item-imposto_codigo]').val('');
        $('[name=item-imposto_designacao]').val('');
        $('[name=item-imposto_motivo]').val('');
        $('[name=item-imposto_taxa]').val('0');

        $('[name=item-artigo_qtd]').val('0');
        $('[name=item-subtotal]').val('0');
        calcularItem();
    } else {
        alert("Dados Inválidos")
    }
    return false;
});


function calcularItem() {

    var array_preco = document.getElementsByClassName('item-artigo_preco');
    var array_qtd = document.getElementsByClassName('item-artigo_qtd');
    var array_imposto_taxa = document.getElementsByClassName('item-imposto_taxa');
    var array_retencao_taxa = document.getElementsByClassName('item-retencao_taxa');
    var array_desconto = document.getElementsByClassName('item-artigo_desconto');
    var array_subtotal = document.getElementsByClassName('item-subtotal');


    var preco = 0;
    var qtd = 0;
    var imposto = 0;
    var retencao = 0;
    var desconto = 0;
    var subtotal = 0;
    var total = 0;

    for (var i = 0; i < array_preco.length; i++) {
        var value = parseFloat(array_preco[i].value);
        preco += value;
    }

    for (var i = 0; i < array_qtd.length; i++) {
        var value = parseFloat(array_qtd[i].value);
        qtd += value;
    }

    for (var i = 0; i < array_imposto_taxa.length; i++) {
        var value = parseFloat(array_imposto_taxa[i].value);
        imposto += ((value * (parseFloat(array_preco[i].value) * parseFloat(array_qtd[i].value))) / 100);
    }

    for (var i = 0; i < array_retencao_taxa.length; i++) {
        var value = parseFloat(array_retencao_taxa[i].value);
        retencao += ((value * (parseFloat(array_preco[i].value) * parseFloat(array_qtd[i].value))) / 100);
    }

    for (var i = 0; i < array_desconto.length; i++) {
        var value = parseFloat(array_desconto[i].value);
        desconto += ((value * (parseFloat(array_preco[i].value) * parseFloat(array_qtd[i].value))) / 100);
    }

    for (var i = 0; i < array_subtotal.length; i++) {
        var value = parseFloat(array_subtotal[i].value);
        subtotal += value;
    }

    //Sumário
    total = ((subtotal + imposto) - (desconto + retencao));
    $('[name=item-total]').val((subtotal.toFixed(2)));
    $('[name=subtotal]').val((subtotal.toFixed(2)));
    $('[name=desconto]').val((desconto.toFixed(2)));
    $('[name=imposto]').val((imposto.toFixed(2)));
    $('[name=retencao]').val((retencao.toFixed(2)));
    $('[name=total]').val((total.toFixed(2)));
}


function finalizar(tabela) {

    var item_id = new Array();
    $("input[class*='item-artigo_id']").each(function() {
        item_id.push($(this).val());
    });

    var item_designacao = new Array();
    $("input[class*='item-artigo_designacao']").each(function() {
        item_designacao.push($(this).val());
    });


    var item_preco = new Array();
    $("input[class*='item-artigo_preco']").each(function() {
        item_preco.push($(this).val());
    });


    var item_qtd = new Array();
    $("input[class*='item-artigo_qtd']").each(function() {
        item_qtd.push($(this).val());
    });

    var item_desconto = new Array();
    $("input[class*='item-artigo_desconto']").each(function() {
        item_desconto.push($(this).val());
    });



    var item_retencao_id = new Array();
    $("input[class*='item-retencao_id']").each(function() {
        item_retencao_id.push($(this).val());
    });

    var item_retencao_designacao = new Array();
    $("input[class*='item-retencao_designacao']").each(function() {
        item_retencao_designacao.push($(this).val());
    });

    var item_retencao_taxa = new Array();
    $("input[class*='item-retencao_taxa']").each(function() {
        item_retencao_taxa.push($(this).val());
    });

    var item_imposto_id = new Array();
    $("input[class*='item-imposto_id']").each(function() {
        item_imposto_id.push($(this).val());
    });

    var item_imposto_tipo = new Array();
    $("input[class*='item-imposto_tipo']").each(function() {
        item_imposto_tipo.push($(this).val());
    });

    var item_imposto_codigo = new Array();
    $("input[class*='item-imposto_codigo']").each(function() {
        item_imposto_codigo.push($(this).val());
    });

    var item_imposto_designacao = new Array();
    $("input[class*='item-imposto_designacao']").each(function() {
        item_imposto_designacao.push($(this).val());
    });

    var item_imposto_motivo = new Array();
    $("input[class*='item-imposto_motivo']").each(function() {
        item_imposto_motivo.push($(this).val());
    });

    var item_imposto_taxa = new Array();
    $("input[class*='item-imposto_taxa']").each(function() {
        item_imposto_taxa.push($(this).val());
    });




    var item_subtotal = new Array();
    $("input[class*='item-subtotal']").each(function() {
        item_subtotal.push($(this).val());
    });

    if (parseFloat($('[name=total]').val())) {
        $.ajax({
            url: base_url + '/documentos/' + tabela + '/store',
            type: 'POST',
            dataType: 'html', //JSON
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                //Negócio
                is_pos: $('[name=is_pos]').val(),
                documento_id: $('[name=documento_id]').val(),
                documento_numero: $('[name=documento_numero]').val(),
                motivo_anulacao_id: $('[name=motivo_anulacao_id]').val(),
                tipo_motivo_anulacao_id: $('[name=tipo_motivo_anulacao_id]').val(),
                local_carga: $('[name=local_carga]').val(),
                local_descarga: $('[name=local_descarga]').val(),

                //Cabecalho
                _token: $('meta[name="csrf-token"]').attr('content'),
                numero: $('[name=numero]').val(),
                cliente_id: $('[name=cliente_id]').val(),
                cliente_nome: $('[name=cliente_nome]').val(),
                contribuinte: $('[name=contribuinte]').val(),
                endereco: $('[name=endereco]').val(),

                //Detalhes
                data: $('[name=data]').val(),
                data_vencimento: $('[name=data_vencimento]').val(),
                formapagamento_id: $('[name=formapagamento_id]').val(),
                moeda_id: $('[name=moeda_id]').val(),
                utilizador_id: $('[name=utilizador_id]').val(),
                utilizador_nome: $('[name=utilizador_nome]').val(),

                //Artigo
                item: {
                    item_id: item_id,
                    item_designacao: item_designacao,
                    item_preco: item_preco,
                    item_qtd: item_qtd,
                    item_desconto: item_desconto,
                    item_retencao_id: item_retencao_id,
                    item_retencao_designacao: item_retencao_designacao,
                    item_retencao_taxa: item_retencao_taxa,
                    item_imposto_id: item_imposto_id,
                    item_imposto_tipo: item_imposto_tipo,
                    item_imposto_codigo: item_imposto_codigo,
                    item_imposto_designacao: item_imposto_designacao,
                    item_imposto_motivo: item_imposto_motivo,
                    item_imposto_taxa: item_imposto_taxa,
                    item_subtotal: item_subtotal
                },
                //Observação
                observacao: $('[name=observacao]').val(),

                //Sumario
                subtotal: $('[name=subtotal]').val(),
                desconto: $('[name=desconto]').val(),
                imposto: $('[name=imposto]').val(),
                retencao: $('[name=retencao]').val(),
                total: $('[name=total]').val(),
            },
            beforeSend: function(data) {
                //console.log(data);
            },
            success: function(data) {
                //console.log(data);
                var data_json = JSON.parse(data);
                if (data_json['status'] == 200) {
                    if (typeof data_json['is_pos'] != "undefined") {
                        location.href = base_url + '/documentos/pos?doc=' + data_json['doc'];
                    } else {
                        location.href = base_url + '/documentos/' + tabela;
                    }

                }
            }
        });
    } else {
        alert("Operação Inválida");
    }
    return false;

}