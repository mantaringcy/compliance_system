@section('title', 'Overview')

<x-main>

    <h2 class="fw-bold mb-5">Overview</h2>

    <div>
        <div class="card-lg table-card">

            <div class="card-top">
                <h5 class="fw-semibold m-0 p-0" style="font-size: 16px !important;">{{ \Carbon\Carbon::now()->format('F') }} Compliances</h5>
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
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
        </div>
    </div>
    
</x-main>

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