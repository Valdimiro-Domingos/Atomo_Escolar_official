//Produto selecionado
function ProdutoSelecionar(prod_id){
    var url = base_url + '/aquisicao/produto';
    
    $.get(url + '/' + prod_id, function (produto, stauts) {
        // console.log(produto);
        $('#codigo').val(produto.codigo);
        $('#idProd').val(produto.id);
        $('#designacao').val(produto.designacao);
        $('#quantidade').val("0");
        $('#valor_aquisicao').val(produto.preco);
        $('#valor_total').val("0");
    }) 

}

function calcular_item(){
    var preco = parseFloat($('#valor_aquisicao').val());
    var quantidade = parseFloat($('#quantidade').val());
    total = preco * quantidade;
    
    $('#valor_total').val(total); 
}

function getproduto(){
    $('#form-aquisicao').on('submit', function(e) {
        e.preventDefault();

        var idProd = $('#idProd').val();
        var codigo = $('#codigo').val();
        var designacao = $('#designacao').val();
        var valor_aquisicao = $('#valor_aquisicao').val();
        var valor_total = $('#valor_total').val();
        var quantidade = $('#quantidade').val();

        if(valor_total != 0){
            var data = ` <tr>
                        <td class="col-md-1">
                            <input type="text" class="form-control" disabled name="number_code" value="${codigo}" required>
                            <input type="hidden" class="form-control idProd" id="idProd" disabled name="idProd" value="${idProd}" required>
                        </td> 
                        <td class="col-md-1">
                            <input type="text" class="form-control" disabled name="number_code" value="${designacao}" required> 
                        </td> 
                        <td class="col-md-1">
                            <input type="text" class="form-control quantidade" disabled name="quantidade" value="${quantidade}" required> 
                        </td> 
                        <td class="col-md-1">
                            <input type="text" class="form-control" disabled name="number_code" value="${valor_aquisicao}" required> 
                        </td> 
                        <td class="col-md-1">
                            <input type="number" class="form-control valor-total" readonly name="valor_total" value="${valor_total}" required> 
                        </td> 
                        <td class="col-md-1">
                            <button class="btn btn-danger" type="submit" onClick="remove_item(this);"><i class="fa fa-trash font-size-16 align-middle mr-2"></i> Eliminar</button>
                        </td> 
                    </tr> 
                        `
            $('#table-itens tbody').append(data);  
            $('#quantidade').val("0");
            $('#valor_aquisicao').val("");
            $('#valor_total').val("0");
            calcularTotalIliquido();
        }
    })
}

function calcularTotalIliquido(){
    
    var valor_total = new Array();
    var total_liquido = 0;
    $("input[class*='valor-total']").each(function() {
        valor_total.push($(this).val());
    });

    
    for(var i = 0; i < valor_total.length; i++){
        total_liquido =total_liquido + parseFloat(valor_total[i]);
    } 
    $('#total-liquido').val(total_liquido + ".00"); 

}

function calcular_valor(){
    var total_liquido = parseFloat($('#total-liquido').val());
    var valor = parseFloat($('#valor').val());
    var resultado = 0;

    if(valor > total_liquido){
        resultado = valor - total_liquido; 

        $('#devido').val("000.00"); 
        $('#troco').val(resultado + ".00"); 
    }else{
        resultado = total_liquido - valor;
        
        $('#devido').val(resultado + ".00"); 
        $('#troco').val("000.00"); 
    }
}

function remove_item(e) {
    e.parentNode.parentNode.remove();
    calcularTotalIliquido();
}

function salvarAquisicao(){
    
    var token = $('#token').attr('content');
    var valor = parseFloat($('#valor').val());
    var troco = parseFloat($('#troco').val());
    var devido = parseFloat($('#devido').val());
    var fornecedor_id = $('#fornecedor_id').val();
    var total_liquido = parseFloat($('#total-liquido').val());

    var idProd_aquisicao = new Array(); 
    $("input[class*='idProd']").each(function() {
        idProd_aquisicao.push($(this).val());
    });

    var quantidade_aquisicao = new Array(); 
    $("input[class*='quantidade']").each(function() {
        quantidade_aquisicao.push($(this).val());
    }); 

    var total_aquisicao = new Array(); 
    $("input[class*='valor-total']").each(function() {
        total_aquisicao.push($(this).val());
    }); 
    
    if(valor != 0 && fornecedor_id != "Selecione" && idProd_aquisicao.length != 0 
        && (devido != 0 || troco != 0)){
         
        var url = base_url + '/aquisicao/store';
        
        /*$.get(url+ '/'+valor + '/'+troco + '/'+devido + '/'+fornecedor_id + '/'+total_liquido
                + '/'+idProd_aquisicao + '/'+quantidade_aquisicao + '/'+total_aquisicao, function(data,stauts) {
            console.log(data);
        }); */
        
        $.ajax({
            url: base_url + '/aquisicao/store',
            type: 'POST',
            dataType: 'JSON',
            data: {
                valor: valor,
                troco: troco,
                devido: devido,
                fornecedor_id: fornecedor_id,
                idProd_aquisicao: idProd_aquisicao,
                quantidade_aquisicao: quantidade_aquisicao,
                total_liquido: total_liquido,
                total_aquisicao: total_aquisicao,
                _token: token
            },
            success: function(data) {
                console.log(data);
                toastr.success("Aquisição salvo com succeso", "Successo!")
                location.href = base_url + '/artigo';
            }
        });

    }else{
        toastr.error("Erro ao salvar Aquisição", "Erro!")
    }
}