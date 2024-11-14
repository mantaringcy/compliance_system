@section('title', 'Compliance Projection')

<x-main>
    <h2 class="fw-bold mb-5">Monthly Projection</h2>

    {{-- showAllCompliances --}}
    {{-- @foreach ($monthlyProjections as $monthYear => $compliances)
        <h2 class="fw-semibold">{{ $monthYear }}</h2> <!-- Show the month and year -->


        <ul>
            @foreach ($compliances as $compliance)
                <li>
                    <span class="fw-semibold m-0 p-0 mt-3">Compliance Name: </span>{{ $compliance['name'] }}
                    <span class="fw-semibold m-0 p-0">Adjusted Date: </span>{{ \Carbon\Carbon::parse($compliance['adjusted_date'])->format('F j, Y') }}
                    <span class="fw-semibold m-0 p-0">Display Date: </span>{{ \Carbon\Carbon::parse($compliance['display_date'])->format('F j, Y') }}</li> <!-- Show compliance name and adjusted date -->
            @endforeach
        </ul>
    @endforeach --}}

    {{-- Monthly Projection Table - 12 Months --}}
    <div>
        @foreach ($groupedResults as $monthYear => $items)
            <div class="card-lg table-card mb-5">

                <div class="card-top">
                    <h5 class="fw-semibold m-0 p-0" style="font-size: 16px !important;">{{ $monthYear }} Compliances</h5>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
    </div>

    {{-- Initial Data No UI --}}
    {{-- <div class="container mt-4">
        @foreach ($groupedResults as $monthYear => $items)
            <div class="card mb-3">
                <div class="card-header">
                    <h5>{{ $monthYear }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($items as $item)
                            <li class="list-group-item">
                                {{ \Carbon\Carbon::parse($item['deadline'])->format('F j, Y') }} : {{ $item['compliance']['compliance_name'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div> --}}

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