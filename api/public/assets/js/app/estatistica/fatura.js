function getSeries() {
    dados = axios(base_url + '/ajax/series')
        .then(function (response) {
            let series = [];
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
                    name: "Despesas",
                    data: [62, 60, 34, 46, 34, 52, 23]
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

            response.data.forEach(function (serie) {
                series.push(serie.designacao)
            });

            options.xaxis.categories = series;
            (chart = new ApexCharts(document.querySelector("#spline_area_teste"), options)).render();
        })
        .catch(function (err) {
            console.error(err);
        })
    return dados;
}

$(function () {

    getSeries();

});