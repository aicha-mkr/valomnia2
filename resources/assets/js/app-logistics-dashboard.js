"use strict";
! function() {
    let e, t;
    const s = {
            donut: {
                series1: config.colors.success,
                series2: "rgba(113, 221, 55, 0.6)",
                series3: "rgba(113, 221, 55, 0.4)",
                series4: "rgba(113, 221, 55, 0.2)"
            },
            line: {
                series1: config.colors.warning,
                series2: config.colors.primary,
                series3: "#7367f029"
            }
        },
        a = document.querySelector("#shipmentStatisticsChart"),
        o = {
            series: [{
                name: "Scheduled",
                type: "column",
                data: [38, 45, 33, 38, 28, 30, 34, 35, 30, 20]
            }, {
                name: "Sent",
                type: "line",
                data: [38, 43, 30, 37, 28, 30, 32, 31, 30, 15]
            }],
            chart: {
                height: 270,
                type: "line",
                stacked: !1,
                parentHeightOffset: 0,
                toolbar: {
                    show: !1
                },
                zoom: {
                    enabled: !1
                }
            },
            markers: {
                size: 4,
                colors: [config.colors.white],
                strokeColors: s.line.series2,
                hover: {
                    size: 6
                },
                borderRadius: 4
            },
            stroke: {
                curve: "smooth",
                width: [0, 3],
                lineCap: "round"
            },
            legend: {
                show: !0,
                position: "bottom",
                markers: {
                    width: 8,
                    height: 8,
                    offsetX: -3
                },
                height: 40,
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                },
                fontSize: "15px",
                fontFamily: "Public Sans",
                fontWeight: 400,
                labels: {
                    colors: t,
                    useSeriesColors: !1
                },
                offsetY: 10
            },
            grid: {
                strokeDashArray: 8
            },
            colors: [s.line.series1, s.line.series2],
            fill: {
                opacity: [1, 1]
            },
            plotOptions: {
                bar: {
                    columnWidth: "30%",
                    startingShape: "rounded",
                    endingShape: "rounded",
                    borderRadius: 4
                }
            },
            dataLabels: {
                enabled: !1
            },
            xaxis: {
                tickAmount: 10,
                categories: ["1 June", "2 June", "3 June", "4 June", "5 June", "6 June", "7 June", "8 June", "9 June", "10 June"],
                labels: {
                    style: {
                        colors: e,
                        fontSize: "13px",
                        fontFamily: "Public Sans",
                        fontWeight: 400
                    }
                },
                axisBorder: {
                    show: !1
                },
                axisTicks: {
                    show: !1
                }
            },
            yaxis: {
                tickAmount: 4,
                min: 10,
                max: 50,
                labels: {
                    style: {
                        colors: e,
                        fontSize: "13px",
                        fontFamily: "Public Sans",
                        fontWeight: 400
                    },
                    formatter: function(e) {
                        return e
                    }
                }
            },
            responsive: [{
                breakpoint: 1400,
                options: {
                    chart: {
                        height: 270
                    },
                    xaxis: {
                        labels: {
                            style: {
                                fontSize: "10px"
                            }
                        }
                    },
                    legend: {
                        itemMargin: {
                            vertical: 0,
                            horizontal: 10
                        },
                        fontSize: "13px",
                        offsetY: 12
                    }
                }
            }, {
                breakpoint: 1399,
                options: {
                    chart: {
                        height: 415
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: "50%"
                        }
                    }
                }
            }, {
                breakpoint: 982,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: "30%"
                        }
                    }
                }
            }, {
                breakpoint: 480,
                options: {
                    chart: {
                        height: 250
                    },
                    legend: {
                        offsetY: 7
                    }
                }
            }]
        };
    if (void 0 !== typeof a && null !== a) {
        new ApexCharts(a, o).render()
    }
    const r = document.querySelector("#deliveryExceptionsChart"),
        i = {
            chart: {
                height: 420,
                parentHeightOffset: 0,
                type: "donut"
            },
            labels: ["Incorrect address", "Weather conditions", "Federal Holidays", "Damage during transit"],
            series: [13, 25, 22, 40],
            colors: [s.donut.series1, s.donut.series2, s.donut.series3, s.donut.series4],
            stroke: {
                width: 0
            },
            dataLabels: {
                enabled: !1,
                formatter: function(e, t) {
                    return parseInt(e) + "%"
                }
            },
            legend: {
                show: !0,
                position: "bottom",
                offsetY: 10,
                markers: {
                    width: 8,
                    height: 8,
                    offsetX: -3
                },
                itemMargin: {
                    horizontal: 15,
                    vertical: 5
                },
                fontSize: "13px",
                fontFamily: "Public Sans",
                fontWeight: 400,
                labels: {
                    colors: t,
                    useSeriesColors: !1
                }
            },
            tooltip: {
                theme: !1
            },
            grid: {
                padding: {
                    top: 15
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: "75%",
                        labels: {
                            show: !0,
                            value: {
                                fontSize: "26px",
                                fontFamily: "Public Sans",
                                color: t,
                                fontWeight: 500,
                                offsetY: -30,
                                formatter: function(e) {
                                    return parseInt(e) + "%"
                                }
                            },
                            name: {
                                offsetY: 20,
                                fontFamily: "Public Sans"
                            },
                            total: {
                                show: !0,
                                fontSize: "0.7rem",
                                label: "AVG. Exceptions",
                                color: e,
                                formatter: function(e) {
                                    return "30%"
                                }
                            }
                        }
                    }
                }
            },
            responsive: [{
                breakpoint: 420,
                options: {
                    chart: {
                        height: 360
                    }
                }
            }]
        };
    if (void 0 !== typeof r && null !== r) {
        new ApexCharts(r, i).render()
    }
}(), $((function() {
    var e = $(".dt-route-vehicles");
    if (e.length) {
        e.DataTable({
            ajax: assetsPath + "json/logistics-dashboard.json",
            columns: [{
                data: "id"
            }, {
                data: "id"
            }, {
                data: "location"
            }, {
                data: "start_city"
            }, {
                data: "end_city"
            }, {
                data: "warnings"
            }, {
                data: "progress"
            }],
            columnDefs: [{
                className: "control",
                orderable: !1,
                searchable: !1,
                responsivePriority: 2,
                targets: 0,
                render: function(e, t, s, a) {
                    return ""
                }
            }, {
                targets: 1,
                orderable: !1,
                searchable: !1,
                responsivePriority: 3,
                render: function() {
                    return '<input type="checkbox" class="dt-checkboxes form-check-input">'
                },
                checkboxes: {
                    selectAllRender: '<input type="checkbox" class="form-check-input">'
                }
            }, {
                targets: 2,
                responsivePriority: 1,
                render: function(e, t, s, a) {
                    var o = s.location;
                    return '<div class="d-flex justify-content-start align-items-center user-name"><div class="avatar-wrapper"><div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary"><i class="bx bxs-truck"></i></span></div></div><div class="d-flex flex-column"><a class="text-body fw-medium" href="' + baseUrl + 'app/logistics/fleet">VOL-' + o + "</a></div></div>"
                }
            }, {
                targets: 3,
                render: function(e, t, s, a) {
                    return '<div class="text-body">' + s.start_city + ", " + s.start_country + "</div >"
                }
            }, {
                targets: 4,
                render: function(e, t, s, a) {
                    return '<div class="text-body">' + s.end_city + ", " + s.end_country + "</div >"
                }
            }, {
                targets: -2,
                render: function(e, t, s, a) {
                    var o = s.warnings,
                        r = {
                            1: {
                                title: "No Warnings",
                                class: "bg-label-success"
                            },
                            2: {
                                title: "Temperature Not Optimal",
                                class: "bg-label-warning"
                            },
                            3: {
                                title: "Ecu Not Responding",
                                class: "bg-label-danger"
                            },
                            4: {
                                title: "Oil Leakage",
                                class: "bg-label-info"
                            },
                            5: {
                                title: "fuel problems",
                                class: "bg-label-primary"
                            }
                        };
                    return void 0 === r[o] ? e : '<span class="badge rounded ' + r[o].class + '">' + r[o].title + "</span>"
                }
            }, {
                targets: -1,
                render: function(e, t, s, a) {
                    var o = s.progress;
                    return '<div class="d-flex align-items-center"><div div class="progress w-100" style="height: 8px;"><div class="progress-bar" role="progressbar" style="width:' + o + '%;" aria-valuenow="' + o + '" aria-valuemin="0" aria-valuemax="100"></div></div><div class="text-body ms-3">' + o + "%</div></div>"
                }
            }],
            order: [2, "asc"],
            dom: '<"table-responsive"t><"row d-flex align-items-center"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 5,
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(e) {
                            return "Details of " + e.data().location
                        }
                    }),
                    type: "column",
                    renderer: function(e, t, s) {
                        var a = $.map(s, (function(e, t) {
                            return "" !== e.title ? '<tr data-dt-row="' + e.rowIndex + '" data-dt-column="' + e.columnIndex + '"><td>' + e.title + ":</td> <td>" + e.data + "</td></tr>" : ""
                        })).join("");
                        return !!a && $('<table class="table"/><tbody />').append(a)
                    }
                }
            }
        });
        $(".dataTables_info").addClass("pt-0")
    }
}));
