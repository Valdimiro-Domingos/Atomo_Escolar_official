/* script alert */
$(function() {
    /*    $('select').selectpicker(); */
    //Tempo Estático
    setTimeout(function() {
        $('[name=alerts]').hide();
    }, 3000);
});


/* script multiple image */
$('.newbtn').bind("click", function() {
    $('#pic').click();
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#blah')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

//------------------------------------
$('.newbtn_2').bind("click", function() {
    $('#pic_2').click();
});

function readURL_2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#blah_2')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

//------------------------------------
$('.newbtn_3').bind("click", function() {
    $('#pic_3').click();
});

function readURL_3(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#blah_3')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function grafico() {
    $.ajax({
        url: base_url + '/api/grafico',
        type: 'POST',
        dataType: 'html',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            _token: $('metza[name="csrf-token"]').attr('content'),
            serie_grafico: $('[name=serie_grafico]').val(),
            utilizador_grafico: $('[name=utilizador_grafico]').val(),
            tipo_grafico: $('[name=tipo_grafico]').val(),
            data1_grafico: $('[name=data1_grafico]').val(),
            data2_grafico: $('[name=data2_grafico]').val()
        },
        beforeSend: function(data) {},
        success: function(data) {
            var dados_json = JSON.parse(data);

            if (dados_json.length) {
                let dados_receita = [];
                let dados_despesa = [];
                let dados_data = [];
                let dados_designacao_documentos = ["Entrada", "Saida", "Pendente"];
                let dados_cores_documentos = ["#34c38f", "#556ee6", "#f46a6a"];
                let dados_count_documentos = [];
                let count_entrada = 0;
                let count_saida = 0;
                let count_pendente = 0;

                dados_json.forEach(item => {
                    switch (item.operacao) {
                        case 'C':
                            dados_data.push(item.data.replace(" ", "T"));
                            dados_receita.push(item.total);
                            dados_despesa.push(0);
                            count_entrada++;
                            break;
                        case 'D':
                            dados_data.push(item.data.replace(" ", "T"));
                            dados_receita.push(0);
                            dados_despesa.push(item.total);
                            count_saida++;
                            break;
                        case 'P':
                            count_pendente++;
                            break;
                        default:
                            break;
                    }

                });

                dados_count_documentos.push(count_entrada);
                dados_count_documentos.push(count_saida);
                dados_count_documentos.push(count_pendente);

                options = {
                    chart: {
                        height: 350,
                        type: "area",
                        toolbar: {
                            show: !1
                        }
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        curve: "smooth",
                        width: 3
                    },
                    series: [{
                        name: "Receitas",
                        data: dados_receita
                    }, {
                        name: "Despesas",
                        data: dados_despesa
                    }],
                    colors: ["#34c38f", "#ed0c0c"],
                    xaxis: {
                        type: "datetime",
                        categories: dados_data
                    },
                    grid: {
                        borderColor: "#f1f1f1"
                    },
                    tooltip: {
                        x: {
                            format: "dd/MM/yy HH:mm"
                        }
                    }
                };
                (chart = new ApexCharts(document.querySelector("#spline_area"), options)).render();

                options = {
                    chart: {
                        height: 320,
                        type: "pie"
                    },
                    series: dados_count_documentos,
                    labels: dados_designacao_documentos,
                    colors: dados_cores_documentos,
                    legend: {
                        show: !0,
                        position: "bottom",
                        horizontalAlign: "center",
                        verticalAlign: "middle",
                        floating: !1,
                        fontSize: "14px",
                        offsetX: 0
                    },
                    responsive: [{
                        breakpoint: 600,
                        options: {
                            chart: {
                                height: 240
                            },
                            legend: {
                                show: !1
                            }
                        }
                    }]
                };
                (chart = new ApexCharts(document.querySelector("#pie_chart"), options)).render();

                options = {
                    chart: {
                        height: 350,
                        type: "bar",
                        toolbar: {
                            show: !1
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: !1,
                            columnWidth: "45%",
                            endingShape: "rounded"
                        }
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        show: !0,
                        width: 2,
                        colors: ["transparent"]
                    },
                    series: [{
                        name: "Net Profit",
                        data: [46, 57, 59, 54, 62, 58, 64, 60, 66]
                    }, {
                        name: "Revenue",
                        data: [74, 83, 102, 97, 86, 106, 93, 114, 94]
                    }, {
                        name: "Free Cash Flow",
                        data: [37, 42, 38, 26, 47, 50, 54, 55, 43]
                    }],
                    colors: ["#34c38f", "#556ee6", "#f46a6a"],
                    xaxis: {
                        categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct"]
                    },
                    yaxis: {
                        title: {
                            text: "$ (thousands)",
                            style: {
                                fontWeight: "500"
                            }
                        }
                    },
                    grid: {
                        borderColor: "#f1f1f1"
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(e) {
                                return "$ " + e + " thousands"
                            }
                        }
                    }
                };
                (chart = new ApexCharts(document.querySelector("#column_chart"), options)).render();

            }
        }
    });

    return false;
}