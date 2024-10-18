@section('title', 'Overview')

<x-main>

    <h2 class="fw-bold mb-5">Overview</h2>
 

 
    <div>
        <div class="card-lg table-card">

            <div class="card-top">
                <h5 class="fw-semibold m-0 p-0" style="font-size: 16px !important;">Month Compliance</h5>
            </div>
            
            <table class="table overview-table w-100" id="complianceListTable">
                
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
                                {{-- <td>{{ $item['compliance_submit_on'] }}</td> --}}
                                <td>{{ \Carbon\Carbon::parse($item['deadline'])->format('F j, Y') }}</td>
                                <td>
                                    @if($item['days_left'] < 0)
                                        {{ abs($item['days_left']) }}
                                    @else
                                        -{{ $item['days_left'] }}
                                    @endif
                                <td>{{ $item['compliance_department'] }}</td>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <p>No deadlines for the current month.</p>
                    @endif

                    {{-- <tr>
                        <td>1</td>
                        <td>COMPLIANCE NAME</td>
                        <td>START DATE</td>
                        <td>SUBMIT DATE</td>
                        <td>DEADLINE</td>
                        <td>DAYS LEFT</td>
                        <td>DEPARTMENT</td>
                        <td>ACTION</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>COMPLIANCE NAME</td>
                        <td>START DATE</td>
                        <td>SUBMIT DATE</td>
                        <td>DEADLINE</td>
                        <td>DAYS LEFT</td>
                        <td>DEPARTMENT</td>
                        <td>ACTION</td>
                    </tr> --}}
                  
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
        border-bottom: 1px solid var(--sidebar-border) !important;
    }

    .overview-table thead tr th {
        text-align: left !important;
        vertical-align: middle !important; /* Vertically center text */

    }

    .overview-table th {
        font-size: 13px !important;
        border-top: 1px solid var(--border) !important;
        border-bottom: 2px solid var(--border) !important;
    }

    .overview-table tbody tr {
        border-top: 1.5px solid var(--border) !important;
        border-bottom: 1px solid var(--card-fill) !important;
    }

    .overview-table tbody tr td {
        background: var(--card-fill) !important;
        color: var(--primary-color-text) !important;
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