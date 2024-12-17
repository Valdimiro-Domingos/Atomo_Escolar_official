function facturacaoPorData() {
    $('.data').text(data)
    axios(base_url + '/ajax/estatistica/facturacao/por-data/' + data)
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
            response.data.forEach(function(dado) {
                series.push(dado.designacao)
                quantidades.push(dado.qtd)
            });

            options.xaxis.categories = series;
            options.series[0].data = quantidades;
            (chart = new ApexCharts(document.querySelector("#facturacao_por_data"), options)).render();
        })
        .catch(function(err) {
            console.error(err);
        })
}

$(function() {
    facturacaoPorData();
});