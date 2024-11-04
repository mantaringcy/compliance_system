@section('title', 'Logs')

<x-main>
    {{-- <h2 class="fw-semibold mb-4">Logs</h2> --}}

    {{-- <table>
        <thead>
            <tr>
                <th>At</th>
                <th>User</th>
                <th>Type</th>
                <th>Compliance</th>
                <th>Changes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

    

            @foreach($logs as $log)
                    @php
                        $approvalJSON = json_decode($log->changes, true);

                        $changesData = json_decode($log->changes, true);
                        $oldData = $changesData['old'] ?? [];  // Provide default empty array if 'old' is not present
                        $newData = $changesData['new'] ?? [];  // Provide default empty array if 'new' is not present

                        $frequencyMapping = config('static_data.frequency');
                        $startWorkingOnMapping = config('static_data.start_working_on');
                        $submitOnMapping = config('static_data.submit_on');

                        unset($oldData['id'], $newData['id']);
                        unset($newData['_token']);
                        unset($newData['complianceId']);
                        unset($newData['_method']);

                        $keyMapping = [
                            'compliance_name' => 'Compliance Name',
                            'department_id' => 'Department',
                            'reference_date' => 'Reference Date',
                            'frequency' => 'Frequency',
                            'start_working_on' => 'Start Working On',
                            'submit_on' => 'Submit On'
                        ];

                        $orderedKeys = [
                            'compliance_name', 
                            'department_id', 
                            'frequency', 
                            'reference_date', 
                            'start_working_on',
                            'submit_on'
                        ];


                        // Find the changes between old and new data
                        $changes = array_diff_assoc($newData, $oldData);
                    @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y h:i A') }}</td>
                    <td>{{ $log->user->username }}</td>
                    <td>
                        <span class="badge rounded-pill text-bg-primary">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="fst-italic">
                        @if ($log->action == 'add/approval')
                            {{ $approvalJSON['compliance_name'] }}
                        @else
                            {{ $log->compliance ? $log->compliance->compliance_name : $log->compliance_name }}
                        @endif
                    </td>
                    @if ($log->action == 'add')
                        <td>Addition of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                    @elseif ($log->action == 'edit')
                        <td class="border border-1">
                            @if(empty($changes))
                                <span>No changes were made.</span>
                            @else
                                    @foreach($orderedKeys as $key)
                                        @if (array_key_exists($key, $changes))
                                            <strong>{{ $keyMapping[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                            <span>Old: 
                                                @if ($key === 'department_id')
                                                    {{ $departments[$oldData[$key] - 1]['department_name'] }}
                                                @elseif ($key === 'reference_date')
                                                    {{ \Carbon\Carbon::parse($oldData[$key])->format('F j, Y') }}
                                                @elseif ($key === 'frequency')
                                                    {{ $frequencyMapping[$oldData[$key]] ?? 'N/A' }}
                                                @elseif ($key === 'start_working_on')
                                                    {{ $startWorkingOnMapping[$oldData[$key]] ?? 'N/A' }}
                                                @elseif ($key === 'submit_on')
                                                    {{ $submitOnMapping[$oldData[$key]] ?? 'N/A' }}
                                                @else
                                                    {{ $oldData[$key] }}
                                                @endif
                                            </span> 
                                            <span>New: 
                                                @if ($key === 'department_id')
                                                    {{ $departments[$changes[$key] - 1]['department_name'] }}
                                                @elseif ($key === 'reference_date')
                                                    {{ \Carbon\Carbon::parse($changes[$key])->format('F j, Y') }}
                                                @elseif ($key === 'frequency')
                                                    {{ $frequencyMapping[$changes[$key]] ?? 'N/A' }}
                                                @elseif ($key === 'start_working_on')
                                                    {{ $startWorkingOnMapping[$changes[$key]] ?? 'N/A' }}
                                                @elseif ($key === 'submit_on')
                                                    {{ $submitOnMapping[$changes[$key]] ?? 'N/A' }}
                                                @else
                                                    {{ $changes[$key] }}
                                                @endif
                                            </span><br>
                                        @endif
                                    @endforeach
                            @endif
                        </td>
                    @elseif ($log->action == 'delete')
                        <td>Deletion of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                    @elseif ($log->action == 'add/approval')
                        <td>Request for Addition of Compliance <strong>{{ $approvalJSON['compliance_name'] }}</strong></td>
                    @elseif ($log->action == 'edit/approval')
                        <td>Request for Change of Compliance
                            @foreach($orderedKeys as $key)
                                @if ($key === 'compliance_name') 
                                    <strong>{{$oldData[$key]}}</strong>
                                @else
                                @endif
                            @endforeach
                        </td>
                    @elseif ($log->action == 'delete/approval')
                        <td>Request for Deletion of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                    @elseif ($log->action == 'add/approved')
                    @elseif ($log->action == 'edit/approved')
                    <td>Approved for Change of Compliance
                        @foreach($orderedKeys as $key)
                            @if ($key === 'compliance_name') 
                                <strong>{{$oldData[$key]}}</strong>
                            @else
                            @endif
                        @endforeach
                    </td>
                    @elseif ($log->action == 'delete/approved')
                        <td>Approved for Deletion of Compliance <strong>{{ $log->compliance_name }}</strong></td>
                    @endif
                    

                    <td>ACTION</td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}

    <div>
        <div class="card-lg table-card">

            <div class="card-top">
                <h5 class="fw-semibold m-0 p-0" style="font-size: 16px !important;">Logs</h5>
            </div>
            
            <table class="table logs-table w-100" id="logsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>AT</th>
                        <th>USER</th>
                        <th>TYPE</th>
                        <th>COMPLIANCE NAME</th>
                        <th>CHANGES</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
          
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

    .logs-table {
        width: 100%; /* Full width */
        border-collapse: collapse;
        border-top: 1px solid var(--border) !important;
        border-bottom: 1px solid var(--border) !important;
    }

    .logs-table thead tr th {
        /* padding: 14.5px 5px !important; */
        text-align: left !important;
        vertical-align: middle !important;
    }

    .logs-table th {
        font-size: 13px !important;
    }

    .logs-table tbody tr:last-child {
        border-bottom: 1px solid var(--card-fill) !important;
    }

    .logs-table tbody tr td {
        background: var(--card-fill) !important;
        color: var(--primary-color-text) !important;
        padding: 8px 5px !important;
    }

    .logs-table th, td {
        text-align: left; /* Horizontally center text */
        vertical-align: middle; /* Vertically center text */
    }

    .logs-table thead th {
        color: var(--primary-color-text) !important;
        background: #FCFCFC !important;
        padding: 8px 8px !important
    }

    body.dark .logs-table thead th {
        background: #303F4F !important;
    }

    .badge {
        padding: 5px 10px !important;
        text-align: center !important;
        border-radius: 6px !important;
    }

    .badge-blue {
        color: white !important;
        background-color: var(--badge-blue) !important;
    }

    .badge-green {
        color: white !important;
        background-color: var(--badge-green) !important;
    }

    .badge-red {
        color: white !important;
        background-color: var(--badge-red) !important;
    }

    .badge-blue-light {
        color: var(--badge-blue) !important;
        background-color: var(--badge-blue-light) !important;
    }

    .badge-green-light {
        color: var(--badge-green) !important;
        background-color: var(--badge-green-light) !important;
    }

    .badge-red-light {
        color: var(--badge-red) !important;
        background-color: var(--badge-red-light) !important;
    }

    .badge-light {
        color: var(--badge-light-color) !important;
        background-color: var(--badge-light) !important;
    }


    /* Datatable CSS */
    table.dataTable td {
        color: var(--primary-color-text) !important;
        /* text-align: center !important; */
        font-size: 14px !important;
        background: var(--card-fill) !important;
        border: 0 !important;
        border-top: 1px solid var(--border) !important;
        /* padding: 20px 10px !important; */
    }

    .dataTables_paginate .paginate_button.previous,
    .dataTables_paginate .paginate_button.next {
        display: none; /* Hide the previous and next buttons */
    }

    .dataTables_length,
    .dataTables_filter {
        margin-bottom: 5px !important;
    }

    /* Custom styles for the search input */
    .dataTables_filter input[type="search"] {
        background-color: var(--input-color) !important;
        border: 1px solid var(--input-border) !important;
        padding: 15px !important;
        border-radius: 10px !important;
        font-size: 14px !important;
        /* margin-right: 25px !important; */
        height: 47px !important;
    }

    /* Change focus styles for the search input */
    .dataTables_filter input[type="search"]:focus {
        border-color: var(--profile-fill-hover) !important; 
        /* outline: none !important;  */
    }

    .dataTables_length {
        font-size: 14px !important; 
        /* margin-left: 25px !important; */
    }

    .dataTables_length select {
        width: 100px !important;
        padding: 5px 10px !important;
        border-radius: 10px !important;
        font-size: 14px;
    }

    .dataTables_info,
    .dataTables_paginate {
        border-top: 5px solid red !important
        padding: 5px 0px 0px 0px !important;
    }

    .dataTables_paginate .paginate_button a {
        background: var(--card-fill) !important;
        height: 33px !important;
        width:  28px !important;
        padding: 5px !important;
        margin: 0 5px;
        border: 0 !important;
        text-align: center !important; /* Horizontally center text */
        vertical-align: middle !important; /* Vertically center text */
        border-radius: 2px !important;
        text-decoration: none !important;  /* Remove underline */
        color: var(--primary-color-text) !important;
    }

    .dataTables_paginate .paginate_button a:hover {
        background: #F8F9FA !important;
    }

    body.dark .dataTables_paginate .paginate_button a:hover {
        background: #131920 !important;
    }

    /* Active pagination button */
    .dataTables_paginate .paginate_button.active a {
        background: #F8F9FA  !important;
    }

    body.dark .dataTables_paginate .paginate_button.active a {
        background: #131920 !important;
    }

    /* Optional: Change cursor for active button */
    .dataTables_paginate .paginate_button.active a {
        cursor: default !important; /* Set cursor to default since it's the active button */
    }

    .dataTables_paginate .paginate_button:focus {
        outline: none !important; /* Removes the default focus outline */
        box-shadow: none !important; /* Remove any focus shadow if applied */
    }

    .shortened-compliance-name {
        white-space: nowrap; /* Prevent text from wrapping to the next line */
        overflow: hidden; /* Hide overflow */
        text-overflow: ellipsis; /* Show ellipsis for overflowed text */
        display: inline-block; /* Ensure it behaves like an inline element */
        max-width: 150px; /* Set a max-width as needed */
    }
</style>

<script>
    // Log Table
    $(document).ready(function () {
        $('#logsTable').DataTable({
            language: {
                lengthMenu: "_MENU_ entries per page", // Change "Show entries" text
                search: '', // Set search label to an empty string
                searchPlaceholder: 'Search...' // Set the placeholder text
            },
            pageLength: 20, // Default entries to display
            lengthMenu: [20, 30, 40, 50, 100], // Options available in the dropdown
            processing: true,
            serverSide: true,
            ajax: '{{ route('logs.data') }}',
            columns: [
                { data: 'id', name: 'id', visible: false },
                { 
                    data: 'date', 
                    name: 'date' ,
                    render: function (data, type, row) {
                        const shortenedName = data.length > 10 ? data.substring(0, 11) : data; // Limit to 50 characters


                        return `<span class="shortened-compliance-name" data-toggle="tooltip" title="${data}">${shortenedName}</span>`;
                        return data;
                    }
                },
                { data: 'user', name: 'user' },
                { data: 'action', name: 'action' },
                { 
                    data: 'compliance_name', 
                    name: 'compliance_name',
                    render: function (data, type, row) {
                        // Create a dynamic shortened name based on the full compliance name
                        const shortenedName = data.length > 50 ? data.substring(0, 40) + '...' : data; // Limit to 50 characters

                        if (data.length > 25) {
                            return `<span class="shortened-compliance-name" data-toggle="tooltip" title="${data}">${shortenedName}</span>`;
                        } else {
                            return data;
                        }

                        // Add a tooltip with the full name

                        // return `<span data-toggle="tooltip" title="${data}">${shortenedName}</span>`;
                    }    
                },
                { 
                    data: 'changes', 
                    name: 'changes',
                    render: function (data, type, row) {
                        // Create a dynamic shortened name based on the full compliance name
                        const shortenedName = data.length > 50 ? data.substring(0, 50) + '...' : data; // Limit to 50 characters

                        if (data.length > 50) {
                            return `<span class="" data-toggle="tooltip" title="${data}">${shortenedName}</span>`;
                        } else {
                            return data;
                        }

                        // return shortenedName;
                        // Add a tooltip with the full name

                        // return `<span  data-toggle="tooltip" title="${data}">${shortenedName}</span>`;
                    } 
                },
            ],
            order: [[0, 'desc']],
            columnDefs: [
                {
                    targets: 1,
                    width: '110px'
                }
            ],
            // Tooltip Call
            initComplete: function(settings, json) {
                // Initialize tooltips after table is drawn
                $('[data-toggle="tooltip"]').tooltip();
            },
            drawCallback: function(settings) {
            // Re-initialize tooltips after each draw
            $('[data-toggle="tooltip"]').tooltip();
            },
        });
    });

    // Initialize Bootstrap tooltips with Popper.js
    $('[data-toggle="tooltip"]').tooltip({
        animation: true, // Enable animation
        delay: { "show": 500, "hide": 100 }, // Set delay for showing/hiding
        html: true, // Allow HTML content
        placement: 'top', // Set position: 'top', 'bottom', 'left', 'right'
    });

</script>