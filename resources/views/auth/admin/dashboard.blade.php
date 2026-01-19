<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    {{--
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="https://code.highcharts.com/12/highcharts.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<style>
    body {
        margin: 0;
        height: 100vh;
        overflow: hidden;
        /* ini kunci anti geser */
    }

    .wrapper {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding-top: 20px;
    }

    .box-container {
        width: 325px;
        height: 152px;
        position: relative;
        padding: 16px;
        color: #000;
        background-color: #E7ECE6;
        border-radius: 12px;
        box-sizing: border-box;
    }

    .box-container span {
        position: absolute;
        top: 16px;
        left: 16px;
        font-size: 15px;
    }

    .box-container h3 {
        position: absolute;
        right: 16px;
        bottom: 16px;
        font-weight: 600;
        font-size: 45px;
        margin: 0;
    }

    .dashboard-charts {
        max-width: 1400px;
        width: 88%;
        margin: 20px auto;
        padding: 0 24px;
        display: grid;
        grid-template-columns: 3fr 1.3fr;
        gap: 20px;
        height: calc(100vh - 280px);
    }

    .chart-main,
    .chart-side {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 16px;
        box-sizing: border-box;
        height: 100%;
    }

    /* INI KUNCI UTAMA */
    #chartMasukKeluar,
    #chartDistribusi {
        width: 100%;
        height: 100%;
    }

    .chart-wrapper {
        max-width: 1060px;
        margin: 30px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 12px;
        box-sizing: border-box;
    }

    .chart-wrapper canvas {
        width: 100% !important;
        height: 360px !important;
    }

    .title {
        font-size: 16px;
        font-weight: 600;
    }

    .header-bar {
        max-width: 1020px;
        margin: 30px auto 10px;
        display: flex;
        align-items: center;
    }

    .filter {
        display: flex;
        align-items: center;
        margin: 12px;
        gap: 10px;
        padding: 8px 14px;
        border-radius: 10px;
        background: linear-gradient(135deg, #EEF2FF, #F8FAFF);
        border: 1px solid #E0E7FF;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .filter i {
        color: #1C3AFF;
        font-size: 14px;
    }

    .filter label {
        font-weight: 500;
        color: #374151;
    }

    .filter select {
        border: none;
        background: transparent;
        font-size: 13px;
        color: #1F2937;
        outline: none;
        cursor: pointer;
        padding-right: 6px;
    }

    /* efek hover */
    .filter:hover {
        background: linear-gradient(135deg, #E0E7FF, #EEF2FF);
        border-color: #1C3AFF;
    }

    /* efek saat select aktif */
    .filter:focus-within {
        box-shadow: 0 0 0 3px rgba(28, 58, 255, 0.15);
    }
</style>

<body>
    <div class="wrapper">
        <div class="box-container">
            <span>Total Pack</span>
            <h3>12</h3>
        </div>

        <div class="box-container">
            <span>Total Pcs</span>
            <h3>120</h3>
        </div>

        <div class="box-container">
            <span>Total User</span>
            <h3>120</h3>
        </div>
    </div>

    <div class="header-bar">
        <div class="title">
            Filter Statistik
        </div>

        <div class="filter">
            <select id="range">
                <option value="minggu">Mingguan</option>
                <option value="bulan">Bulanan</option>
                <option value="tahun">Tahunan</option>
            </select>
        </div>
    </div>

    <div class="dashboard-charts">
        <div class="chart-main">
            <div id="chartMasukKeluar"></div>
        </div>
        <div class="chart-side">
            <div id="chartDistribusi"></div>
        </div>
    </div>



    {{--
    <script>
        const dataChart1 = {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            datasets: [
                {
                    label: 'Barang Masuk',
                    data: [12, 19, 8, 15, 22, 10, 5],
                    backgroundColor: '#1C3AFF',
                    borderRadius: 6,
                    barThickness: 26,
                    categoryPercentage: 0.8,
                    barPercentage: 0.65
                },
                {
                    label: 'Barang Keluar',
                    data: [7, 14, 6, 8, 18, 9, 4],
                    backgroundColor: '#FF2929',
                    borderRadius: 6,
                    barThickness: 26,
                    categoryPercentage: 0.8,
                    barPercentage: 0.65
                }
            ]
        };

        const configChart1 = {
            type: 'bar',
            data: dataChart1,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 10
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 14,
                            boxHeight: 14,
                            padding: 20,
                            font: {
                                size: 13,
                                weight: '500'
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Statistik Barang Masuk & Keluar',
                        font: {
                            size: 16,
                            weight: '600'
                        },
                        padding: {
                            bottom: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        titleFont: {
                            size: 13,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 12
                        },
                        cornerRadius: 6
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#E5E7EB'
                        },
                        ticks: {
                            stepSize: 5,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        };

        new Chart(
            document.getElementById('myChart'),
            configChart1
        );
    </script> --}}

    {{--
    <script>
        const dataChart2 = {
            labels: ['Buku Tulis', 'Alat Tulis', 'Seragam'],
            datasets: [
                {
                    label: 'Barang Masuk',
                    data: [12, 19, 8, 15, 22, 10, 5],
                    backgroundColor: '#1C3AFF',
                    borderRadius: 6,
                    barThickness: 26,
                    categoryPercentage: 0.8,
                    barPercentage: 0.65
                },
            ]
        };

        const configChart2 = {
            type: 'bar',
            data: dataChart2,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 10
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 14,
                            boxHeight: 14,
                            padding: 20,
                            font: {
                                size: 13,
                                weight: '500'
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Barang Berdasrkan Kategori',
                        font: {
                            size: 16,
                            weight: '600'
                        },
                        padding: {
                            bottom: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        titleFont: {
                            size: 13,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 12
                        },
                        cornerRadius: 6
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#E5E7EB'
                        },
                        ticks: {
                            stepSize: 5,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        };

        new Chart(document.getElementById('myChart2'), configChart2);
    </script> --}}

    <script>
        Highcharts.chart('chartMasukKeluar', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Statistik Barang Masuk & Keluar'
            },
            xAxis: {
                categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah'
                }
            },
            plotOptions: {
                column: {
                    borderRadius: 6,
                    pointPadding: 0.1,
                    groupPadding: 0.15
                }
            },
            series: [
                {
                    name: 'Barang Masuk',
                    data: [12, 19, 8, 15, 22, 10, 5],
                    color: '#1C3AFF'
                },
                {
                    name: 'Barang Keluar',
                    data: [7, 14, 6, 8, 18, 9, 4],
                    color: '#FF2929'
                }
            ]
        });
    </script>

    <script>
        Highcharts.chart('chartDistribusi', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Distribusi Barang'
            },
            xAxis: {
                categories: ['Buku Tulis', 'Alat Tulis', 'Seragam']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah'
                }
            },
            plotOptions: {
                column: {
                    borderRadius: 6
                }
            },
            series: [
                {
                    name: 'Jumlah',
                    data: [40, 25, 15],
                    color: '#1C3AFF'
                }
            ]
        });
    </script>



</body>

</html>