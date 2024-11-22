@section('title', 'Compliance Due')

<x-main>

    <h2 class="fw-bold mb-5">Compliance Due</h2>

    <div class="custom-alert custom-alert-blue custom-toast" id="alert-compliance-created">
        Sample toast!
    </div>

    <!-- Custom Toast Structure -->
    <div id="customToast" class="custom-toast">
        <div class="custom-alert custom-alert-blue shadow-lg" id="alert-compliance-created">
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sint officiis animi incidunt, in officia vero iste at quaerat beatae illum.
        </div>
    </div>

    <div>
        <div class="card-lg table-card">

            <div class="card-top">
                <h5 class="fw-semibold m-0 p-0" style="font-size: 16px !important;">{{ \Carbon\Carbon::now()->format('F') }} Compliances</h5>
            </div>

            <div class="compliance-overview">
                {{-- <h4>Compliance Overview</h4> --}}
                <p>Total Compliances: {{ $overviewData['totalCompliances'] }}</p>
                <p>Completed Compliances: {{ $overviewData['completedCompliances'] }}</p>
                {{-- <p>Completion Percentage: {{ $overviewData['completionPercentage'] }}%</p> --}}
            
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar" role="progressbar" 
                         style="width: {{ $overviewData['completionPercentage'] }}%;" 
                         aria-valuenow="{{ $overviewData['completionPercentage'] }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ $overviewData['completionPercentage'] }}%
                    </div>
                </div>
            </div>
            
            <table class="table table-hover overview-table w-100" id="complianceListTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>COMPLIANCE NAME</th>
                        <th>START DATE</th>
                        <th>SUBMIT DATE</th>
                        <th>DEADLINE</th>
                        <th>DAYS LEFT</th>
                        <th>DEPARTMENT</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                {{-- <tbody>
                    @if(!empty($currentMonthDeadlines))
                        @foreach($currentMonthDeadlines as $item)
                            <tr>
                                <td>{{ $item['compliance']['id'] }}</td>
                                <td>{{ $item['compliance']['compliance_name'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($item['startWorkingOn'])->format('F j, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item['submitOn'])->format('F j, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item['deadline'])->format('F j, Y') }}</td>
                                <td>
                                    @if($item['days_left'] < 0)
                                        {{ abs($item['days_left']) }}
                                    @else
                                        -{{ $item['days_left'] }}
                                    @endif
                                </td>
                                <td>{{ $item['compliance_department'] }}</td>
                                <td>Action</td>
                            </tr>
                        @endforeach
                    @else
                        <p>No deadlines for the current month.</p>
                    @endif
                </tbody> --}}

                @php
                    
                @endphp

                <tbody>
                    @if(!empty($monthlyCompliances))
                        @foreach($monthlyCompliances as $monthlyCompliance)
                            <tr 
                                class="{{ $monthlyCompliance['status'] == 'completed' ? 'completed' : '' }}"
                                onclick="window.location.href='{{ route('compliance-management.edit', $monthlyCompliance['id']) }}';"
                                style="cursor: pointer;"
                            >
                                    <td>{{ $monthlyCompliance['compliance_id'] }}</td>
                                    <td>{{ $monthlyCompliance['compliance_name'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($monthlyCompliance['computed_start_date'])->format('F j, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($monthlyCompliance['computed_submit_date'])->format('F j, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($monthlyCompliance['computed_deadline'])->format('F j, Y') }}</td>
                                    <td>
                                        @if ($monthlyCompliance->status == 'completed')
                                            <span>0</span>  <!-- Show N/A if status is completed -->
                                        @else

                                        @if ($monthlyCompliance->days_difference > 0)
                                            <span style="color: green;">{{ $monthlyCompliance->days_difference }} days remaining</span>
                                        @elseif ($monthlyCompliance->days_difference < 0)
                                            <span style="color: red;">{{ abs($monthlyCompliance->days_difference) }} days overdue</span>
                                        @else
                                            <span style="color: orange;">Deadline is today</span>
                                        @endif
                                    @endif
                                    </td>
                                    <td>{{ $monthlyCompliance->department_name }}</td>
                                    <td>
                                        @if($monthlyCompliance['status'] == 'completed')
                                            <span class="badge badge-green">COMPLIED</span>
                                        @elseif($monthlyCompliance['status'] == 'in_progress')
                                            <span class="badge badge-blue-light">IN PROGRESS</span>
                                        @elseif($monthlyCompliance['status'] == 'pending')
                                            <span class="badge badge-yellow-light">PENDING</span>
                                        @endif
                                    </td>
                            </tr>
                        @endforeach
                    @else
                        <p>No deadlines for the current month.</p>
                    @endif
                </tbody>

            </table>
        </div>
    </div>

    {{-- <button onclick="showToast()">Show Custom Toast</button> --}}

    
</x-main>

<style>
    .overview-table tbody .completed td {
        background-color: #f0f0f0 !important;   /* Light gray background */
        color: #888;  /* Light gray text */
        text-decoration: line-through;  /* Optional: strikethrough text */
    }


    .progress {
        font-size: 14px !important;
        border-radius: 8px !important;
        background: var(--body-color) !important;
    }

    .progress .progress-bar {
        background: var(--profile-fill-hover) !important;
    }
</style>

<script>
    function showToast() {
        // Show the toast with a fade-in effect
        $('#customToast').addClass('show');

        // Automatically hide the toast after 3 seconds
        setTimeout(function() {
            $('#customToast').removeClass('show');
        }, 3000);
    }

    function closeToast() {
        // Hide the toast when close button is clicked
        $('#customToast').removeClass('show');
    }
</script>

<style>
    .custom-toast {
        position: fixed;
        bottom: 1px;
        right: 20px;
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        z-index: 1055;
    }

    .custom-toast-close {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }

    /* Show animation */
    .custom-toast.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<style>
    .table-card {
        padding: 25px !important;
    }

   .card-top {
        font-weight: 500;
        margin-bottom: 25px !important;
    }

    .overview-table {
        width: 100%; /* Full width */
        border-collapse: collapse;
        border-top: 1px solid var(--border) !important;
        border-bottom: 1px solid var(--border) !important;
    }

    .overview-table thead tr th {
        padding: 14.5px 5px !important;
        text-align: left !important;
        vertical-align: middle !important;
    }

    .overview-table th {
        font-size: 13px !important;
    }

    .overview-table tbody tr:last-child {
        border-bottom: 1px solid var(--card-fill) !important;
    }

    .overview-table tbody tr td {
        background: var(--card-fill) !important;
        color: var(--primary-color-text) !important;
        padding: 11.5px 5px !important;
    }

    .overview-table th, td {
        text-align: left; /* Horizontally center text */
        vertical-align: middle; /* Vertically center text */
    }

    .overview-table thead th {
        color: var(--primary-color-text) !important;
        background: #FCFCFC !important;
        padding: 8px 8px !important
    }

    body.dark .overview-table thead th {
        background: #303F4F !important;
    }
</style>