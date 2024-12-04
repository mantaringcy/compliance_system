@section('title', 'Logs')

<x-main>
    <h2 class="fw-bold mb-5" style="font-size: 30px !important;">Logs and Compliance</h2>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="compliance-tab" data-bs-toggle="tab" href="#compliance" role="tab" aria-controls="compliance" aria-selected="true">Compliance Records</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="logs-tab" data-bs-toggle="tab" href="#logs" role="tab" aria-controls="logs" aria-selected="false">Logs</a>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content" id="myTabContent">
        
        <!-- Compliance Tab -->
        <div class="tab-pane fade show active" id="compliance" role="tabpanel" aria-labelledby="compliance-tab">
            <div class="card-lg custom-table-card-sm">
                <table class="table custom-table custom-table-sm w-100" id="complianceManagementTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>COMPLIANCE NAME</th>
                            <th>DEPARTMENT NAME</th>
                            <th>DEADLINE</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
              
                </table>
            </div>
        </div>

        <!-- Logs Tab -->
        <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="logs-tab">
            <div class="card-lg custom-table-card-sm">
                <table class="table custom-table custom-table-sm w-100" id="logsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>AT</th>
                            <th>USER</th>
                            <th>TYPE</th>
                            <th>COMPLIANCE NAME</th>
                            <th>CHANGES</th>
                            {{-- <th>ACTION</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populate logs data here -->
                    </tbody>
                </table>
            </div>
        </div>

      
    </div>
</x-main>

<style>
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
        /* padding: 15px !important; */
        border-radius: 6px !important;
        font-size: 14px !important;
        /* margin-right: 25px !important; */
        height: 20px !important;
        width: 160px !important;
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
        width: 80px !important;
        padding: 5px 10px !important;
        border-radius: 6px !important;
        font-size: 12px;
        height: 30px !important;
        margin-right: 5px !important;
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
                emptyTable: "No logs available", // When no data is in the table
                zeroRecords: "No matching logs found", // When search results in no data
                infoEmpty: "No logs to display", // When there are no records available
                lengthMenu: "_MENU_ entries per page", // Change "Show entries" text
                search: 'Search:', // Set search label to an empty string
                searchPlaceholder: '' // Set the placeholder text
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

<script>
    $(document).ready(function () {
        $('#complianceManagementTable').DataTable({
            language: {
                emptyTable: "No compliance due available", // When no data is in the table
                zeroRecords: "No matching compliance due found", // When search results in no data
                infoEmpty: "No compliance due to display", // When there are no records available
                lengthMenu: "_MENU_ entries per page", // Change "Show entries" text
                search: 'Search:', // Set search label to an empty string
                searchPlaceholder: '' // Set the placeholder text
            },
            pageLength: 20, // Default entries to display
            lengthMenu: [20, 30, 40, 50, 100], // Options available in the dropdown
            processing: true,
            serverSide: true,
            ajax: '{{ route('compliance-management.index') }}',
            order: [], // Disable default sorting
            columns: [
                { data: 'compliance_id', name: 'compliance_id' },
                { data: 'compliance_name', name: 'compliance_name' },
                { data: 'department_id', name: 'department_id' },
                { data: 'deadline', name: 'deadline' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' },
            ],
            createdRow: function (row, data) {
                // Check if the status is completed
                if (data.status.includes('COMPLETED')) {
                    $(row).addClass('completed'); // Use Bootstrap's gray class
                }
            }
        });
    });
</script>