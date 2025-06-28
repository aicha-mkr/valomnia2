"use strict";

(function() {
  let cardColor, headingColor, axisColor, shadeColor, borderColor;

  cardColor = '#fff';
  headingColor = '#566a7f';
  axisColor = '#a1acb8';
  borderColor = '#eceef1';

  // Alerts Sent Over Time Chart
  // --------------------------------------------------------------------
  const alertsSentChartEl = document.querySelector('#alertsSentChart'),
    alertsSentChartOptions = {
      chart: {
        height: 400,
        type: 'line',
        parentHeightOffset: 0,
        zoom: {
          enabled: false
        },
        toolbar: {
          show: false
        }
      },
      series: emailsEvolution.series,
      markers: {
        strokeWidth: 7,
        strokeOpacity: 1,
        strokeColors: [cardColor],
        colors: ['#71dd37', '#03c3ec']
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'straight'
      },
      colors: ['#71dd37', '#03c3ec'],
      grid: {
        borderColor: borderColor,
        xaxis: {
          lines: {
            show: true
          }
        },
        padding: {
          top: -20
        }
      },
      tooltip: {
        shared: true,
        intersect: false,
        y: {
          formatter: function(val) {
            return val + ' emails sent';
          }
        }
      },
      xaxis: {
        categories: emailsEvolution.labels,
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: axisColor,
            fontSize: '13px'
          }
        }
      },
      yaxis: {
        labels: {
          style: {
            colors: axisColor,
            fontSize: '13px'
          }
        }
      }
    };
  if (typeof alertsSentChartEl !== 'undefined' && alertsSentChartEl !== null) {
    const alertsSentChart = new ApexCharts(alertsSentChartEl, alertsSentChartOptions);
    alertsSentChart.render();
  }

  // Alert Types Distribution Pie Chart
  // --------------------------------------------------------------------
  const alertTypeChartEl = document.querySelector('#alertTypeChart'),
    alertTypeChartOptions = {
      chart: {
        height: 380,
        type: 'pie'
      },
      labels: alertTypesData.labels,
      series: alertTypesData.series,
      colors: [
        '#71dd37',
        '#03c3ec',
        '#ffab00',
        '#ff3e1d',
        '#8592a3',
        '#a8aaae'
      ],
      stroke: {
        width: 2,
        colors: [cardColor]
      },
      dataLabels: {
        enabled: true,
        formatter: function(val, opt) {
          return parseInt(val) + '%';
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: {
          offsetX: -3
        },
        itemMargin: {
          vertical: 3,
          horizontal: 10
        },
        labels: {
          colors: axisColor,
          useSeriesColors: false
        }
      },
      plotOptions: {
        pie: {
          dataLabels: {
            offset: -5
          }
        }
      },
      responsive: [{
        breakpoint: 992,
        options: {
          chart: {
            height: 380
          },
          legend: {
            show: true,
            position: 'bottom'
          }
        }
      }, {
        breakpoint: 576,
        options: {
          chart: {
            height: 320
          },
          legend: {
            show: true,
            position: 'bottom',
            labels: {
              colors: axisColor,
              useSeriesColors: false
            }
          }
        }
      }]
    };
  if (typeof alertTypeChartEl !== 'undefined' && alertTypeChartEl !== null) {
    const alertTypeChart = new ApexCharts(alertTypeChartEl, alertTypeChartOptions);
    alertTypeChart.render();
  }

  // Email Types Distribution Donut Chart
  // --------------------------------------------------------------------
  const emailTypesChartEl = document.querySelector('#emailTypesChart'),
    emailTypesChartOptions = {
      chart: {
        height: 380,
        type: 'donut'
      },
      labels: emailTypesDistribution.labels,
      series: emailTypesDistribution.series,
      colors: [
        '#ffab00',
        '#03c3ec'
      ],
      stroke: {
        width: 2,
        colors: [cardColor]
      },
      dataLabels: {
        enabled: true,
        formatter: function(val, opt) {
          return parseInt(val) + '%';
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: {
          offsetX: -3
        },
        itemMargin: {
          vertical: 3,
          horizontal: 10
        },
        labels: {
          colors: axisColor,
          useSeriesColors: false
        }
      },
      plotOptions: {
        pie: {
          donut: {
            size: '65%',
            labels: {
              show: true,
              name: {
                show: true,
                fontSize: '14px',
                fontFamily: 'Public Sans',
                color: headingColor,
                offsetY: -10
              },
              value: {
                show: true,
                fontSize: '16px',
                fontFamily: 'Public Sans',
                color: headingColor,
                offsetY: 16,
                formatter: function(val) {
                  return parseInt(val) + '%';
                }
              },
              total: {
                show: true,
                label: 'Total',
                fontSize: '16px',
                fontFamily: 'Public Sans',
                color: headingColor,
                formatter: function(w) {
                  const total = emailTypesDistribution.series.reduce((a, b) => a + b, 0);
                  return Math.round(total) + ' emails';
                }
              }
            }
          }
        }
      },
      responsive: [{
        breakpoint: 992,
        options: {
          chart: {
            height: 380
          },
          legend: {
            show: true,
            position: 'bottom'
          }
        }
      }, {
        breakpoint: 576,
        options: {
          chart: {
            height: 320
          },
          legend: {
            show: true,
            position: 'bottom',
            labels: {
              colors: axisColor,
              useSeriesColors: false
            }
          }
        }
      }]
    };
  if (typeof emailTypesChartEl !== 'undefined' && emailTypesChartEl !== null) {
    const emailTypesChart = new ApexCharts(emailTypesChartEl, emailTypesChartOptions);
    emailTypesChart.render();
  }
})(); 