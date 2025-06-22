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
      series: [{
        name: 'Alerts Sent',
        data: alertsSentData.series
      }],
      markers: {
        strokeWidth: 7,
        strokeOpacity: 1,
        strokeColors: [cardColor],
        colors: ['#71dd37']
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'straight'
      },
      colors: ['#71dd37'],
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
        custom: function({
          series,
          seriesIndex,
          dataPointIndex,
          w
        }) {
          return '<div class="px-3 py-2">' + '<span>' + series[seriesIndex][dataPointIndex] + ' alerts sent</span>' + '</div>';
        }
      },
      xaxis: {
        categories: alertsSentData.labels,
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

  // Alert Types Distribution Donut Chart
  // --------------------------------------------------------------------
  const alertTypeChartEl = document.querySelector('#alertTypeChart'),
    alertTypeChartOptions = {
      chart: {
        height: 380,
        type: 'donut'
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
        width: 5,
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
            labels: {
              show: true,
              name: {
                fontSize: '2rem',
                fontFamily: 'Public Sans'
              },
              value: {
                fontSize: '1.2rem',
                color: axisColor,
                fontFamily: 'Public Sans',
                formatter: function(val) {
                  return parseInt(val);
                }
              },
              total: {
                show: true,
                fontSize: '1.5rem',
                color: headingColor,
                label: 'Total',
                formatter: function(w) {
                  return alertTypesTotalCount;
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
          plotOptions: {
            pie: {
              donut: {
                labels: {
                  show: true,
                  name: {
                    fontSize: '1.5rem'
                  },
                  value: {
                    fontSize: '1rem'
                  },
                  total: {
                    fontSize: '1.5rem'
                  }
                }
              }
            }
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
})(); 