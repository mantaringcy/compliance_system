@section('title', 'Compliance List')

{{-- Modals --}}
@include('modals.view-compliance-modal')
@include('modals.edit-compliance-modal')
@include('modals.delete-compliance-modal')
@include('modals.new-compliance-modal')

<x-main>
    
    <h2 class="fw-bold">Compliance List</h2>

    <div>
        <div class="card-lg">
            <div class="card-top text-end">
                {{-- <h5 class="fw-semibold m-0 p-0" style="font-size: 16px !important;">Compliance List</h5> --}}
                
                <!-- Add Compliance -->
                <button type="button border-0" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newComplianceModal"  
                data-departments='@json($departments)'>
                    <i class="fa-solid fa-plus"></i> Add Compliance
                </button>
            </div>
            
            
            <table class="table data-table w-100" id="complianceListTable">
                

                <thead>

                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">COMPLIANCE NAME</th>
                        <th scope="col">DEPARTMENT NAME</th>
                        <th scope="col">REFERENCE DATE</th>
                        <th scope="col">FREQUENCY</th>
                        {{-- <th scope="col">START ON</th>
                        <th scope="col">SUBMIT ON</th> --}}
                        <th scope="col">ACTION</th>
                    </tr>
                    
                    {{-- <tr>
                        <th scope="col">#</th>
                        <th scope="col">Compliance Name</th>
                        <th scope="col">Department Name</th>
                        <th scope="col">Reference Date</th>
                        <th scope="col">Frequency</th>
                        <th scope="col">Start On</th>
                        <th scope="col">Submit On</th>
                        <th scope="col">Action</th>
                    </tr> --}}
                </thead>
                {{-- <tbody>
                    @foreach($complianceEntries as $compliance)
                    <tr>
                        <th scope="row">{{ $compliance->id }}</th>
                        <td>{{ $compliance->compliance_name }}</td>
                        <td>{{ $compliance->mapped_department }}</td>
                        <td>{{ date('M d, Y', strtotime($compliance->reference_date)) }}</td>
                        <td>{{ $compliance->mapped_frequency }}</td>
                        <td>{{ $compliance->mapped_startOn }}</td>
                        <td>{{ $compliance->mapped_submitOn }}</td>
                        <td>
                            <a href="#" class="view-btn view-compliance" 
                            data-bs-toggle="modal" 
                            data-bs-target="#viewComplianceModal"
                            data-compliance-id="{{ $compliance->id }}"
                            data-compliance-name="{{ $compliance->compliance_name }}"
                            data-department="{{ $compliance->department_id }}"
                            data-compliance-reference-date="{{ $compliance->reference_date }}"
                            data-compliance-frequency="{{ $compliance->frequency }}"
                            data-compliance-start-on="{{ $compliance->start_on }}"
                            data-compliance-submit-on="{{ $compliance->submit_on }}"
                            ><i class="fa-regular fa-eye"></i></a>

                            <a href="#" class="edit-btn edit-compliance" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editComplianceModal"
                            data-compliance-id="{{ $compliance->id }}"
                            data-compliance-name="{{ $compliance->compliance_name }}"
                            data-department="{{ $compliance->department_id }}"
                            data-compliance-reference-date="{{ $compliance->reference_date }}"
                            data-compliance-frequency="{{ $compliance->frequency }}"
                            data-compliance-start-on="{{ $compliance->start_on }}"
                            data-compliance-submit-on="{{ $compliance->submit_on }}"
                            ><i class="fa-regular fa-pen-to-square"></i></a>

                            <a href="#" class="delete-btn delete-compliance" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteComplianceModal"
                            data-compliance-id="{{ $compliance->id }}"
                            data-compliance-name="{{ $compliance->compliance_name }}"
                            ><i class="fa-regular fa-trash-can"></i></a>

                        </td>
                    </tr>
                    @endforeach
                </tbody> --}}
              </table>

        </div>
    </div>
</x-main>

<script>
    const departmentMapping = @json($departments);
    const frequencyMapping = @json(config('static_data.frequency'));
    const startOnMapping = @json(config('static_data.start_on'));
    const submitOnMapping = @json(config('static_data.submit_on'));

    // Format Date
    function formatDate(myDate) {
        const date = new Date(myDate); // Create a new Date object
        const options = { year: 'numeric', month: 'long', day: 'numeric' }; // Date options
        return date.toLocaleDateString('en-US', options); // Format to MM/DD/YYYY
    }

    // Compliance List Table
    $(document).ready(function() {
        $('#complianceListTable').DataTable({
            language: {
                lengthMenu: "_MENU_ entries per page", // Change "Show entries" text
                search: '', // Set search label to an empty string
                searchPlaceholder: 'Search...' // Set the placeholder text
            },
            pageLength: 10, // Default entries to display
            lengthMenu: [5, 10, 15, 20, 25], // Options available in the dropdown
            processing: true,
            serverSide: true,
            ajax: '{{ route('compliances.index') }}',
            columns: [
                { data: 'id', name: 'id', orderable: false },
                { data: 'compliance_name', name: 'compliance_name' },
                { data: 'department_id', name: 'department_id', 
                    render: function(data, type, row) {
                        return departmentMapping[data - 1].department_name || 'Unknown';
                    }
                },
                { data: 'reference_date', name: 'reference_date',
                    render: function(data, type, row) {
                        return formatDate(data)
                    }
                 },
                { data: 'frequency', name: 'frequency',
                    render: function(data, type, row) {
                        return frequencyMapping[data] || 'Unknown';
                    }
                 },
                // { data: 'start_on', name: 'start_on',
                //     render: function(data, type, row) {
                //         return startOnMapping[data] || 'Unknown';
                //     }
                //  },
                // { data: 'submit_on', name: 'submit_on',
                //     render: function(data, type, row) {
                //         return submitOnMapping[data] || 'Unknown';
                //     }
                //  },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            columnDefs: [
                { orderable: false, targets: [0, 1, 2, 3, 4] }
            ],
        });
    });

    // Edit Button
    $(document).on('click', '.edit-compliance', function() {
        event.preventDefault();
        // Get the data attributes from the clicked anchor tag
        const id = $(this).attr('data-compliance-id');
        const complianceName = $(this).attr('data-compliance-name');
        const departmentId = $(this).attr('data-department-id');
        const referenceDate = $(this).attr('data-compliance-reference-date');
        const frequency = $(this).attr('data-compliance-frequency');
        const startOn = $(this).attr('data-compliance-start-on');
        const submitOn = $(this).attr('data-compliance-submit-on');

        // Example: You can now pass this data to a modal or form
        $('#editComplianceModal').modal('show');  // Show the modal
        $('#editComplianceModal #complianceId').val(id);   // Set the ID in a hidden input
        $('#editComplianceModal #complianceName').val(complianceName);
        $('#editComplianceModal #departmentSelect').val(departmentId);
        $('#editComplianceModal #referenceDate').val(referenceDate);
        $('#editComplianceModal #frequency').val(frequency);
        $('#editComplianceModal #startOn').val(startOn);
        $('#editComplianceModal #submitOn').val(submitOn);

     

    });

    // View Button
    $(document).on('click', '.view-compliance', function() {

        // Get the data attributes from the clicked anchor tag
        const id = $(this).attr('data-compliance-id');
        const complianceName = $(this).attr('data-compliance-name');
        const departmentId = $(this).attr('data-department-id');
        const referenceDate = $(this).attr('data-compliance-reference-date');
        const frequency = $(this).attr('data-compliance-frequency');
        const startOn = $(this).attr('data-compliance-start-on');
        const submitOn = $(this).attr('data-compliance-submit-on');

        // Example: You can now pass this data to a modal or form
        $('#viewComplianceModal').modal('show');  // Show the modal
        // $('#viewComplianceModal #complianceId').val(id);   // Set the ID in a hidden input
        $('#viewComplianceModal #vComplianceName').text(complianceName);
        $('#viewComplianceModal #vDepartmentName').text(departmentMapping[departmentId - 1].department_name);
        $('#viewComplianceModal #vreferenceDate').text(formatDate(referenceDate));
        $('#viewComplianceModal #vfrequency').text(frequencyMapping[frequency]);
        $('#viewComplianceModal #vstartOn').text(startOnMapping[startOn]);
        $('#viewComplianceModal #vsubmitOn').text(submitOnMapping[submitOn]);
    });

    // Delete Button
    $(document).on('click', '.delete-compliance', function(e) {
        e.preventDefault();
        // Get the compliance ID from the clicked anchor tag
        const complianceId = $(this).attr('data-compliance-id');
        const complianceName = $(this).attr('data-compliance-name');

        $('#deleteComplianceModal #dComplianceName').text(complianceName);

        // Set the compliance ID in the hidden input of the form
        $('#complianceId').val(complianceId);

        $('#deleteComplianceForm').attr('action', '/compliances/' + complianceId);
    });
</script>
    


<style>
    .card-top {
        /* border-radius: 100px !important; */
        font-weight: 500;
        padding: 15px 15px 15px 15px !important;
        /* border: 1px solid red !important */
    }

    .card-top button {
        /* font-size: 100px !important; */
        padding: 8px 15px 8px 15px !important;
        border-radius: 100px !important;
        font-weight: 500 !important;
        background: var(--profile-fill-hover) !important;
        border: 0 !important;
        margin-bottom: 10px !important;
    }

    .card-top button:hover {
        background-color: #3f78ff !important;
    }

    .table {
        width: 100%; /* Full width */
        border-collapse: collapse;
    }

    .table th {
        font-size: 13px !important;
        border-bottom: 1px solid var(--border) !important;
        padding: 10px 0px !important;
    }

    th, td {
        text-align: center; /* Horizontally center text */
        vertical-align: middle; /* Vertically center text */
    }

    .table thead th {
        color: var(--primary-color-text) !important;
        background: #FCFCFC !important;
        padding: 10px 20px !important
    }

    body.dark .table thead th {
        background: #303F4F !important;
    }

    table.dataTable td {
        color: var(--primary-color-text) !important;
        text-align: center !important;
        font-size: 14px !important;
        background: var(--card-fill) !important;
        border: 0 !important;
        border-top: 1px solid var(--border) !important;
        padding: 20px 10px !important;
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
        margin-right: 25px !important;
        height: 47px !important;
    }

    /* Change focus styles for the search input */
    .dataTables_filter input[type="search"]:focus {
        border-color: var(--profile-fill-hover) !important; 
        /* outline: none !important;  */
    }

    .dataTables_length {
        font-size: 14px !important; 
        margin-left: 25px !important; /* Add space at the bottom */
    }

    .dataTables_length select {
        width: 100px !important;
        padding: 5px 10px !important;
        border-radius: 10px !important;
        font-size: 14px;
    }

    .dataTables_info,
    .dataTables_paginate {
        padding: 25px 25px 20px 25px !important;
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


</style>