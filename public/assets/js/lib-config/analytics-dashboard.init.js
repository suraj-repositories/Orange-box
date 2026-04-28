document.addEventListener('DOMContentLoaded', function () {


    enableDocumentationVisitors();
    enableBounceRateCharts();
    enableAudienceDailyChart();


    const isDashboard = document.querySelector('.analytics-dashboard');
    if (!isDashboard) {
        return;
    }

    // =====================================
    // Analytics Chart
    // =====================================
    var options = {
        chart: {
            type: "area",
            fontFamily: 'inherit',
            height: 45,
            sparkline: {
                enabled: true
            },
            animations: {
                enabled: false
            },
        },
        dataLabels: {
            enabled: false,
        },
        fill: {
            opacity: .16,
            type: 'solid'
        },
        stroke: {
            width: 2,
            lineCap: "round",
            curve: "smooth",
        },
        series: [{
            name: "Profits",
            data: [63, 16, 8, 22, 20, 7, 26, 10, 63, 5, 25, 19, 16, 11, 16, 1, 37, 23, 31, 1, 24, 15, 2, 17, 5, 25, 19, 16, 11, 28]
        }],
        tooltip: {
            theme: 'light'
        },
        grid: {
            strokeDashArray: 4,
        },
        xaxis: {
            labels: {
                padding: 0,
            },
            tooltip: {
                enabled: false
            },
            axisBorder: {
                show: false,
            },
            type: 'datetime',
        },
        yaxis: {
            labels: {
                padding: 4
            },
        },
        labels: [
            '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19', '2020-07-20'
        ],
        colors: ["#FF8600"],
        legend: {
            show: false,
        },
    };
    var chart = new ApexCharts(document.querySelector("#website-visitors"), options);
    chart.render();

    // Conversion Chart
    var options = {
        chart: {
            type: "area",
            fontFamily: 'inherit',
            height: 45,
            sparkline: {
                enabled: true
            },
            animations: {
                enabled: false
            },
        },
        dataLabels: {
            enabled: false,
        },
        fill: {
            opacity: .16,
            type: 'solid'
        },
        stroke: {
            width: 2,
            lineCap: "round",
            curve: "smooth",
        },
        series: [{
            name: "Profits",
            data: [27, 21, 18, 24, 29, 19, 23, 3, 20, 26, 12, 28, 25, 37, 12, 18, 21, 18, 24, 29, 19, 17, 10, 34, 9, 22, 8, 31, 18, 27],
        }],
        tooltip: {
            theme: 'light'
        },
        grid: {
            strokeDashArray: 4,
        },
        xaxis: {
            labels: {
                padding: 0,
            },
            tooltip: {
                enabled: false
            },
            axisBorder: {
                show: false,
            },
            type: 'datetime',
        },
        yaxis: {
            labels: {
                padding: 4
            },
        },
        labels: [
            '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19', '2020-07-20'
        ],
        colors: ["#ec8290"],
        legend: {
            show: false,
        },
    };
    var chart = new ApexCharts(document.querySelector("#conversion-visitors"), options);
    chart.render();


    // Session Chart
    var options = {
        chart: {
            type: "line",
            height: 45,
            sparkline: {
                enabled: true
            },
            animations: {
                enabled: false
            },
        },
        fill: {
            opacity: 1,
        },
        stroke: {
            width: [2],
            dashArray: [0, 3],
            lineCap: "round",
            curve: "smooth",
        },
        series: [{
            name: "May",
            data: [40, 51, 62, 70, 65, 53, 51, 46, 62, 93, 62, 61, 51, 62, 51, 66, 70, 53, 62, 44, 53, 46, 40, 65, 55, 62, 70, 75, 78, 80]
        }],
        tooltip: {
            theme: 'light'
        },
        grid: {
            strokeDashArray: 4,
        },
        xaxis: {
            labels: {
                padding: 0,
            },
            tooltip: {
                enabled: false
            },
            type: 'datetime',
        },
        yaxis: {
            labels: {
                padding: 4
            },
        },
        labels: [
            '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29', '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04', '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09', '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14', '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19', '2020-07-20'
        ],
        colors: ["#FF8600", "#343a40"],
        legend: {
            show: false,
        },
    };
    var chart = new ApexCharts(document.querySelector("#session-visitors"), options);
    chart.render();


    // Active Users
    var options = {
        series: [
            {
                data: [40, 30, 38, 47, 42, 36, 47, 75, 65, 42, 35, 48, 46, 55, 24],
            },
        ],
        chart: {
            height: 45,
            type: "bar",
            sparkline: {
                enabled: true,
            },
            animations: {
                enabled: false
            },
        },
        colors: ["#FF8600"],
        plotOptions: {
            bar: {
                columnWidth: "35%",
                borderRadius: 3,
            },
        },
        dataLabels: {
            enabled: false,
        },
        fill: {
            opacity: 1,
        },
        grid: {
            strokeDashArray: 4,
        },
        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
        xaxis: {
            crosshairs: {
                width: 1,
            },
        },
        yaxis: {
            labels: {
                padding: 4
            },
        },
        tooltip: {
            theme: 'light'
        },
        legend: {
            show: false,
        },
    };
    var chartOne = new ApexCharts(document.querySelector('#active-users'), options);
    chartOne.render();





});


function enableBounceRateCharts() {
    document.querySelectorAll('.sparkline-bounce').forEach((el) => {

        let values = [];

        try {
            values = JSON.parse(el.dataset.values || '[]');
        } catch (e) {
            values = [];
        }

        let labels = [];

        try {
            labels = JSON.parse(el.dataset.labels || '[]');
        } catch (e) {
            labels = [];
        }

        const color = getComputedStyle(document.documentElement)
            .getPropertyValue('--sparkline-color')
            .trim();

        const options = {
            chart: {
                type: "line",
                height: 24,
                parentHeightOffset: 0,
                toolbar: { show: false },
                animations: { enabled: false },
                sparkline: { enabled: true },
            },
            series: [{
                name: 'Views',
                data: values
            }],
            labels: labels,
            colors: [color],
            tooltip: {
                enabled: true,
                y: {
                    formatter: function (val) {
                        return val;
                    }
                }
            },
            stroke: {
                width: 2,
                lineCap: "round",
                curve: "smooth"
            },
        };

        new ApexCharts(el, options).render();
    });
}

function enableDocumentationVisitors() {
    const el = document.querySelector('#documentation-visitors');
    if (!el) return;

    let categories = [];
    let data = [];

     const color = getComputedStyle(document.documentElement)
            .getPropertyValue('--chart-color')
            .trim();


    try {
        categories = JSON.parse(el.dataset.categories || '[]');
        data = JSON.parse(el.dataset.series || '[]');
    } catch (e) {
        categories = [];
        data = [];
    }

    const options = {
        chart: {
            type: "bar",
            height: 307,
            parentHeightOffset: 0,
            toolbar: { show: false },
        },
        colors: [color],
        series: [{
            name: 'Visitors',
            data: data
        }],
        fill: { opacity: 1 },
        plotOptions: {
            bar: {
                columnWidth: "50%",
                borderRadius: 4,
                borderRadiusApplication: 'end',
            },
        },
        grid: {
            strokeDashArray: 4,
            padding: { top: -20, right: 0, bottom: -4 },
        },
        xaxis: {
            type: 'category',
            categories: categories,
            labels: {
                rotate: -45
            }
        },
        yaxis: {
            title: {
                text: 'Documentation Visitors',
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                }
            },
        },
        tooltip: {
            x: {
                formatter: function (val, opts) {
                    return categories[opts.dataPointIndex] || val;
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
        },
        stroke: { width: 0 },
        dataLabels: { enabled: false },
        theme: { mode: 'light' },
    };

    if (el._chart) {
        el._chart.destroy();
    }

    el._chart = new ApexCharts(el, options);
    el._chart.render();
}

function enableAudienceDailyChart() {
    const el = document.querySelector('#audiences-daily');
    if (!el) return;

    let series = [];

    try {
        series = JSON.parse(el.dataset.series || '[]');
    } catch (e) {
        series = [];
    }

    const color = getComputedStyle(document.documentElement)
            .getPropertyValue('--sparkline-color')
            .trim();

    const options = {
        series: series,
        chart: {
            height: 345,
            type: 'heatmap',
            parentHeightOffset: 0,
            toolbar: { show: false },
        },
        plotOptions: {
            heatmap: {
                radius: 10,
                enableShades: true,
                shadeIntensity: 0.5
            }
        },
        grid: { show: false },
        dataLabels: { enabled: false },
        colors: [color],
        legend: {
            show: true,
            position: "top",
            horizontalAlign: "center",
        },
    };

    if (el._chart) el._chart.destroy();

    el._chart = new ApexCharts(el, options);
    el._chart.render();
}
