<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Sensor</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: #f5f5f0;
            color: #1a1a1a;
            line-height: 1.4;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 80px 20px 40px 20px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            color: #000;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .header .subtitle {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1rem;
            color: #666;
            text-transform: lowercase;
        }

        .main-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: auto auto auto auto;
            gap: 20px;
            margin-bottom: 40px;
        }

        /* Stats Cards */
        .stats-section {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #fff;
            border: 4px solid #000;
            box-shadow: 8px 8px 0 #000;
            padding: 32px 24px;
            position: relative;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:active {
          box-shadow: none;
          transform: translateY(5px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .stat-title {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #666;
            margin-bottom: 12px;
        }

        .stat-value {
            font-size: 3.5rem;
            font-weight: 700;
            color: #000;
            line-height: 0.9;
            font-family: 'JetBrains Mono', monospace;
        }

        .stat-unit {
            font-size: 1.2rem;
            font-weight: 400;
            color: #666;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: #000;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            border: 3px solid #000;
        }

        .temp-icon { background: #ff6b6b; }
        .humid-icon { background: #4ecdc4; }
        .light-icon { background: #f9ca24; color: #000; }

        .condition-badge {
            display: inline-block;
            padding: 6px 16px;
            background: #000;
            color: #fff;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            margin-top: 16px;
            border: 2px solid #000;
        }

        .condition-gelap { background: #2d3748; }
        .condition-redup { background: #4a5568; }
        .condition-cerah { background: #ed8936; }
        .condition-terang { background: #ecc94b; color: #000; }

        .last-update {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            color: #666;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Charts Section */
        .charts-grid {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .chart-card {
            background: #fff;
            border: 4px solid #000;
            box-shadow: 8px 8px 0 #000;
            padding: 32px 24px;
            position: relative;
        }

        .chart-card::before {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            background: #000;
            z-index: -1;
        }

        .chart-title {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #000;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chart-container {
            position: relative;
            height: 320px;
            width: 100%;
        }

        /* Status Indicator */
        .status-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #000;
            color: #fff;
            padding: 12px 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            z-index: 1000;
            border-bottom: 4px solid #ff6b6b;
        }

        .status-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            background: #00ff00;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .main-grid {
                gap: 16px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 80px 16px 40px;
            }
            
            .stat-card,
            .chart-card {
                box-shadow: 4px 4px 0 #000;
                border-width: 3px;
            }
            
            .stat-card:hover {
                transform: translate(-1px, -1px);
                box-shadow: 6px 6px 0 #000;
            }

            .stat-value {
                font-size: 2.8rem;
            }
        }

        /* Print Styles */
        @media print {
            .status-bar {
                display: none;
            }
            
            .stat-card,
            .chart-card, {
                box-shadow: none;
                border: 2px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="status-bar">
        <div class="status-content">
            <div>
                <span class="status-dot"></span>
                LIVE MONITORING ACTIVE
            </div>
            <div id="systemTime">--:--:--</div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>SENSOR.DASH</h1>
            <p class="subtitle">real-time environmental monitoring system</p>
        </div>

        <div class="main-grid">
            <div class="stats-section">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">TEMPERATURE</div>
                            <div class="stat-value">
                                <span id="valTemp">--</span>
                                <span class="stat-unit">°C</span>
                            </div>
                        </div>
                        <div class="stat-icon temp-icon">T°</div>
                    </div>
                    <div class="last-update">LAST: <span id="tempTime">--</span></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">HUMIDITY</div>
                            <div class="stat-value">
                                <span id="valHum">--</span>
                                <span class="stat-unit">%</span>
                            </div>
                        </div>
                        <div class="stat-icon humid-icon">H%</div>
                    </div>
                    <div class="last-update">LAST: <span id="humTime">--</span></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">LIGHT SENSOR</div>
                            <div class="stat-value">
                                <span id="valLdrRaw">--</span>
                            </div>
                            <span id="valLdrCond" class="condition-badge">--</span>
                        </div>
                        <div class="stat-icon light-icon">LUX</div>
                    </div>
                    <div class="last-update">LAST: <span id="ldrTime">--</span></div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-title">
                        [TEMP/HUMID] TIMELINE
                    </div>
                    <div class="chart-container">
                        <canvas id="chartTemp"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-title">
                        [LIGHT] LEVELS
                    </div>
                    <div class="chart-container">
                        <canvas id="chartLdr"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const REFRESH_MS = 3000;

        // Update system time
        function updateSystemTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('systemTime').textContent = timeString;
        }

        function mapCondition(v) {
            if (v > 2000) return 'GELAP';
            if (v > 1500) return 'REDUP';
            if (v > 1000) return 'CERAH';
            return 'TERANG';
        }

        function getConditionClass(condition) {
            return 'condition-' + condition.toLowerCase();
        }

        function fetchLatest() {
            $.getJSON('get_latest.php', function(res) {
                if (res.status !== 'ok') return;
                
                const s = res.suhu;
                const l = res.ldr;
                
                if (s) {
                    $('#valTemp').text(parseFloat(s.temperature).toFixed(1));
                    $('#valHum').text(parseFloat(s.humidity).toFixed(1));
                    const formattedTime = new Date(s.ts).toLocaleTimeString('en-US', {
                        hour12: false,
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    $('#tempTime').text(formattedTime);
                    $('#humTime').text(formattedTime);
                }
                
                if (l) {
                    $('#valLdrRaw').text(l.ldr_raw);
                    const condition = l.kondisi.toUpperCase();
                    const conditionEl = $('#valLdrCond');
                    conditionEl.text(condition);
                    conditionEl.removeClass().addClass('condition-badge ' + getConditionClass(condition));
                    
                    const ldrTime = new Date(l.ts || s.ts).toLocaleTimeString('en-US', {
                        hour12: false,
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    $('#ldrTime').text(ldrTime);
                }
            });
        }

        let chartTemp, chartLdr;

        function setupCharts() {
            Chart.defaults.font.family = "'JetBrains Mono', monospace";
            Chart.defaults.font.size = 11;
            Chart.defaults.color = '#000';

            const ctxT = document.getElementById('chartTemp').getContext('2d');
            chartTemp = new Chart(ctxT, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'TEMP (°C)',
                        data: [],
                        borderColor: '#ff6b6b',
                        backgroundColor: 'rgba(255, 107, 107, 0.1)',
                        fill: true,
                        tension: 0,
                        borderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#ff6b6b',
                        pointBorderColor: '#000',
                        pointBorderWidth: 2
                    }, {
                        label: 'HUM (%)',
                        data: [],
                        borderColor: '#4ecdc4',
                        backgroundColor: 'rgba(78, 205, 196, 0.1)',
                        fill: true,
                        tension: 0,
                        borderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#4ecdc4',
                        pointBorderColor: '#000',
                        pointBorderWidth: 2,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: false,
                                boxWidth: 15,
                                boxHeight: 3,
                                font: {
                                    family: "'JetBrains Mono', monospace",
                                    weight: '700'
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { 
                                display: true,
                                color: '#000',
                                lineWidth: 1
                            },
                            border: {
                                color: '#000',
                                width: 2
                            },
                            ticks: { 
                                maxTicksLimit: 6,
                                font: {
                                    family: "'JetBrains Mono', monospace",
                                    weight: '500'
                                }
                            }
                        },
                        y: {
                            beginAtZero: false,
                            position: 'left',
                            grid: { 
                                display: true,
                                color: '#000',
                                lineWidth: 1
                            },
                            border: {
                                color: '#000',
                                width: 2
                            },
                            ticks: {
                                font: {
                                    family: "'JetBrains Mono', monospace",
                                    weight: '500'
                                }
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: { display: false },
                            border: {
                                color: '#000',
                                width: 2
                            },
                            ticks: {
                                font: {
                                    family: "'JetBrains Mono', monospace",
                                    weight: '500'
                                }
                            }
                        }
                    }
                }
            });

            const ctxL = document.getElementById('chartLdr').getContext('2d');
            chartLdr = new Chart(ctxL, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'LIGHT RAW',
                        data: [],
                        borderColor: '#f9ca24',
                        backgroundColor: 'rgba(249, 202, 36, 0.1)',
                        fill: true,
                        tension: 0,
                        borderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#f9ca24',
                        pointBorderColor: '#000',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: false,
                                boxWidth: 15,
                                boxHeight: 3,
                                font: {
                                    family: "'JetBrains Mono', monospace",
                                    weight: '700'
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { 
                                display: true,
                                color: '#000',
                                lineWidth: 1
                            },
                            border: {
                                color: '#000',
                                width: 2
                            },
                            ticks: { 
                                maxTicksLimit: 6,
                                font: {
                                    family: "'JetBrains Mono', monospace",
                                    weight: '500'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { 
                                display: true,
                                color: '#000',
                                lineWidth: 1
                            },
                            border: {
                                color: '#000',
                                width: 2
                            },
                            ticks: {
                                font: {
                                    family: "'JetBrains Mono', monospace",
                                    weight: '500'
                                }
                            }
                        }
                    }
                }
            });
        }

        function loadHistory(limit = 50) {
            $.getJSON('get_history.php?limit=' + limit, function(res) {
                if (!res) return;

                // Temperature and humidity chart
                const sLabels = res.suhu.map(r => {
                    const date = new Date(r.ts);
                    return date.toLocaleTimeString('en-US', { 
                        hour12: false,
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                });
                const sTemp = res.suhu.map(r => parseFloat(r.temperature));
                const sHum = res.suhu.map(r => parseFloat(r.humidity));

                chartTemp.data.labels = sLabels;
                chartTemp.data.datasets[0].data = sTemp;
                chartTemp.data.datasets[1].data = sHum;
                chartTemp.update('none');

                // Light sensor chart
                const lLabels = res.ldr.map(r => {
                    const date = new Date(r.ts);
                    return date.toLocaleTimeString('en-US', { 
                        hour12: false,
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                });
                const lRaw = res.ldr.map(r => parseInt(r.ldr_raw));

                chartLdr.data.labels = lLabels;
                chartLdr.data.datasets[0].data = lRaw;
                chartLdr.update('none');
            });
        }

        $(document).ready(function() {
            setupCharts();
            fetchLatest();
            loadHistory();
            updateSystemTime();

            // Update time every second
            setInterval(updateSystemTime, 1000);

            // Update data every 3 seconds
            setInterval(function() {
                fetchLatest();
                loadHistory();
            }, REFRESH_MS);
        });
    </script>
</body>
</html>