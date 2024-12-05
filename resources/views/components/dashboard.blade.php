@section('title', 'Dashboard')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Include Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<x-main>
    <h2 class="fw-bold mb-5">Dashboard</h2>
   
    <div class="container">
        
        <!-- Dropdown for selecting month -->
        <div class="mb-3">
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="input-group">
                    <select name="month_year" class="form-select" onchange="this.form.submit()">
                        @for ($y = 2020; $y <= 2025; $y++)
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i . '-' . $y }}" {{ ($i == $month && $y == $year) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }} {{ $y }}
                                </option>
                            @endfor
                        @endfor
                    </select>
                </div>
            </form>
        </div>
    
        {{-- Content --}}
        <div class="row">
            
            {{-- Completed --}}
            <div class="col-md-4">
                <div class="kpi-card mb-4">
                    <div class="card-header mb-3 d-flex justify-content-between align-items-center">
                        <span class="kpi-icon completed me-3">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <h6 class="fw-semibold m-0 flex-grow-1">Completed</h6>
                    </div>
                    <div class="kpi-card-body">
                        <h5 class="card-title fw-semibold">{{ $data['completed'] }}</h5>
                        <p class="card-text">
                            Percentage: 
                            @php
                                $totalItems = $data['completed'] + $data['in_progress'] + $data['pending'];
                            @endphp
                            {{ $totalItems > 0 ? round(($data['completed'] / $totalItems) * 100, 2) : 0 }}%
                        </p>
                    </div>
                </div>
            </div>

            {{-- In Progress --}}
            <div class="col-md-4">
                <div class="kpi-card mb-4">
                    <div class="card-header mb-3 d-flex justify-content-between align-items-center">
                        <span class="kpi-icon in-progress me-3">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                        <h6 class="fw-semibold m-0 flex-grow-1">In Progress</h6>
                    </div>
                    <div class="kpi-card-body">
                        <h5 class="card-title fw-semibold">{{ $data['in_progress'] }}</h5>
                        <p class="card-text">Compliance In Progress</p>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div class="col-md-4">
                <div class="kpi-card mb-4">
                    <div class="card-header mb-3 d-flex justify-content-between align-items-center">
                        <span class="kpi-icon pending me-3">
                            <i class="fas fa-clock"></i>
                        </span>
                        <h6 class="fw-semibold m-0 flex-grow-1">Pending</h6>
                    </div>
                    <div class="kpi-card-body">
                        <h5 class="card-title fw-semibold">{{ $data['pending'] }}</h5>
                        <p class="card-text">Compliance On Pending</p>
                    </div>
                </div>
            </div>

            {{-- Overdue --}}
            <div class="col-md-6">
                <div class="kpi-card mb-4">
                    <div class="card-header mb-3 d-flex justify-content-between align-items-center">
                        <span class="kpi-icon overdue me-3">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>
                        <h6 class="fw-semibold m-0 flex-grow-1">Overdue</h6>
                    </div>
                    <div class="kpi-card-body">
                        <h5 class="card-title fw-semibold">{{ $data['overdue'] }}</h5>
                        <p class="card-text">Compliance Overdue</p>
                    </div>
                </div>
            </div>

            {{-- Total --}}
            <div class="col-md-6">
                <div class="kpi-card mb-4">
                    <div class="card-header mb-3 d-flex justify-content-between align-items-center">
                        <span class="kpi-icon total me-3">
                            <i class="fas fa-list"></i>
                        </span>
                        <h6 class="fw-semibold m-0 flex-grow-1">Total</h6>
                    </div>
                    <div class="kpi-card-body">
                        <h5 class="card-title fw-semibold">{{ $data['total'] }}</h5>
                        <p class="card-text">Percentage: {{ $data['total'] > 0 ? round(($data['completed'] / $data['total']) * 100, 2) : 0 }}%</p>
                    </div>
                </div>
            </div>
    
            {{-- <div class="col-md-6">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">In Progress</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $data['in_progress'] }}</h5>
                        <p class="card-text">Percentage: {{ $data['total'] > 0 ? round(($data['in_progress'] / $data['total']) * 100, 2) : 0 }}%</p>
                    </div>
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Pending</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $data['pending'] }}</h5>
                        <p class="card-text">Percentage: {{ $data['total'] > 0 ? round(($data['pending'] / $data['total']) * 100, 2) : 0 }}%</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $data['total'] }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Overdue</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $data['overdue'] }}</h5>
                    </div>
                </div>
            </div> --}}
            
        </div>
    
        <div class="container mt-5">
            <h2 class="fw-bold mb-5">Compliance Status Chart</h2>
            <canvas id="complianceChart" width="200" height="200"></canvas>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
        <script>
            const ctx = document.getElementById('complianceChart').getContext('2d');
            const complianceChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Completed', 'In Progress', 'Pending'],
                    datasets: [{
                        data: [
                            {{ $data['completed'] }},
                            {{ $data['in_progress'] }},
                            {{ $data['pending'] }}
                        ],
                        backgroundColor: [
                            '#2CA87F', // Completed
                            '#4680FF', // In Progress
                            '#E48901'  // Pending
                        ],
                        borderColor: [
                            'rgba(255, 255, 255, 1)', // White border for Completed
                            'rgba(255, 255, 255, 1)', // White border for In Progress
                            'rgba(255, 255, 255, 1)'  // White border for Pending
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true // Use point style for legend items
                            }
                        },
                        title: {
                            display: true,
                            text: 'Compliance Status Overview for {{ date('F Y', strtotime($year . '-' . $month . '-01')) }}'
                        },
                        tooltip: {
                            callbacks: {
                                label: (tooltipItem) => {
                                    const value = tooltipItem.raw || 0; // Get the value
                                    const label = tooltipItem.label || ''; // Get the label
                                    return `${label}: ${value}`; // Format the tooltip without HTML
                                },
                                title: () => {
                                    return ''; // Remove the title (default label)
                                }
                            },
                            displayColors: false // Disable the color box
                        },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((acc, val) => acc + val, 0);
                                const percentage = ((value / total) * 100).toFixed(2) + '%';
                                return percentage === '0.00%' ? '' : percentage; // Hide 0% labels
                            },
                            color: '#fff', // Color of the text
                            font: {
                                weight: 'bold' // Make the font bold
                            },
                            anchor: 'center', // Positioning of the label inside the pie
                            align: 'center'   // Center alignment of the label
                        }
                    }
                },
                plugins: [ChartDataLabels] // Register the plugin
            });
        </script>
        
        <style>
            /* Custom CSS to make the tooltip value bold */
            .chartjs-tooltip {
                font-weight: normal; /* Default weight */
            }
            .chartjs-tooltip strong {
                font-weight: bold; /* Bold weight for the value */
            }
        </style>
        
    </div>

</x-main>

<style>
    #complianceChart {
        max-width: 400px;  /* Set the maximum width */
        max-height: 400px; /* Set the maximum height */
        width: 100%;        /* Allow it to scale down */
        height: auto;      /* Maintain aspect ratio */
    }
</style>

<style>
    .kpi-card {
        background: white !important;
        height: 178px !important;
        outline: 1px solid #E7EAEE !important;
        border-radius: 8px !important;
        padding: 25px !important;
    }

    .kpi-icon {
        padding: 10px !important;
        border-radius: 8px !important;
        font-size: 20px !important;
    }

    .kpi-card-body {
        background: #F8F9FA !important;
        padding: 15px 10px !important;
        border-radius: 8px !important;
    }

    .kpi-icon.completed {
        color: #2CA87F !important;
        background: #ECF6F2 !important;
    }

    .kpi-icon.in-progress {
        color: #4680FF !important;
        background: #EDF2FF !important;
    }

    .kpi-icon.pending {
        color: #E48A01 !important;
        background: #FCF4E9 !important;
    }

    .kpi-icon.overdue {
        color: #DC2625 !important;
        background: #FDEAE9 !important;
    }

    .kpi-icon.total {
        color: #3D4144 !important;
        background: #DEDEDF !important;
    }
</style>