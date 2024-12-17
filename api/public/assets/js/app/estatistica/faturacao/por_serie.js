function facturacaoPorSeries() {
    dados = axios(base_url + '/ajax/estatistica/facturacao/por-serie')
        .then(function(response) {
            let series = [];
            let quantidades = [];
            options = {
                chart: {
                    height: 350,
                    type: "bar",
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
                    name: "Quantidade",
                    data: []
                }],
                colors: ["#34c38f"],
                xaxis: {
                    type: "string",
                    categories: []
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
            response.data.series.forEach(function(serie) {
                series.push(serie.designacao)
                quantidades.push(serie.qtd)
            });

            options.xaxis.categories = series;
            options.series[0].data = quantidades;
            //Gráfico de barra
            (chart = new ApexCharts(document.querySelector("#facturacao_por_serie"), options)).render();

            //Gráfico Pizza
            labels = ["Factura", "Factura Recibo", "Recibo", "Proforma", "Orçamento"];
            series = [0, 0, 0, 0, 0];
            response.data.series.forEach(function(serie) {
                /*
                 *   A designação do documento vem no formato serie -> designação do documento
                 *   Ex: A -> factura
                 *   Por isso a separação da string e pegando somente o nome do documento em si.
                 */
                switch (serie.designacao.split(' ')[2]) {
                    case 'factura':
                        {
                            series[0] += serie.qtd;
                            break;
                        }
                    case 'facturaRECIBO':
                        {
                            series[1] += serie.qtd;
                            break;
                        }
                    case 'RECIBO':
                        {
                            series[2] += serie.qtd;
                            break;
                        }
                    case 'PROFORMA':
                        {
                            series[3] += serie.qtd;
                            break;
                        }
                    case 'ORCAMENTO':
                        {
                            series[4] += serie.qtd;
                            break;
                        }
                }
            });
            options = {
                chart: {
                    height: 320,
                    type: "pie"
                },
                series: series,
                labels: labels,
                colors: ["#34c38f", "#556ee6", "#f46a6a", "#50a5f1", "#f1b44c"],
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
            (chart = new ApexCharts(document.querySelector("#facturacao_por_serie2"), options)).render();

            //Gráfico de multiplas barras
            console.log(response.data.entradas)
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
                    name: "Entradas",
                    data: response.data.entradas
                }, {
                    name: "Saidas",
                    data: response.data.saidas
                }],
                colors: ["#556ee6", "#f46a6a"],
                xaxis: {
                    categories: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]
                },
                yaxis: {
                    title: {
                        text: "AKZ (kwanzas)",
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
                            return e + " Kwanzas"
                        }
                    }
                }
            };
            (chart = new ApexCharts(document.querySelector("#facturacao_por_serie3"), options)).render();

        })
        .catch(function(err) {
            console.log(err);
        })
    return dados;
}
$(function() {
    facturacaoPorSeries();
})