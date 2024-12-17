function vendaPorVendedor() {
    axios(base_url + '/ajax/documento/por-vendedor')
        .then(function(response) {
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
                    name: "RECIBOS",
                    data: []
                }, {
                    name: "factura-RECIBO",
                    data: []
                }],
                colors: ["#556ee6", "#34c38f"],
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
            options.xaxis.categories = response.data.nomes;
            options.series[0].data = response.data.qtd_recibo;
            options.series[1].data = response.data.qtd_facturarecibo;
            (chart = new ApexCharts(document.querySelector("#venda_por_vendedor"), options)).render();
        })
        .catch(function(err) {
            console.error(err);
        });
}

$(function() {
    vendaPorVendedor();
})