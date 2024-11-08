@section('title', 'Request')

{{-- Modal --}}
@include('modals.add-request-compliance-modal')
@include('modals.edit-request-compliance-modal')
@include('modals.delete-request-compliance-modal')

<x-main>

    {{-- Toast --}}
    <div id="customToast" class="custom-toast">
        {{-- Compliance Create --}}
        <div class="shadow custom-alert custom-alert-blue" style="display: none;"  id="alert-compliance-request-add">
        </div>

        {{-- Compliance Edit --}}
        <div class="shadow custom-alert custom-alert-green" style="display: none;"  id="alert-compliance-request-edit">
        </div>

        {{-- Compliance Delete --}}
        <div class="shadow custom-alert custom-alert-red" style="display: none;"  id="alert-compliance-request-delete">
        </div>

        {{-- Cancel --}}
        <div class="shadow custom-alert custom-alert-secondary" style="display: none;"  id="alert-compliance-request-cancel">
        </div>
    </div>

    <h2 class="fw-bold mb-5" style="font-size: 30px !important;">Compliance Request</h2>


    <div>
        <div class="card-lg custom-table-card-lg">

            {{-- <div class="card-top">
                <h5 class="fw-semibold m-0 p-0" style="font-size: 16px !important;">Request for Change</h5>
            </div> --}}
          
            <table class="table table-hover w-100 request-table custom-table custom-table-lg" id="requestComplianceTable">
                

                <thead>

                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">#</th>
                        <th scope="col">COMPLIANCE NAME</th>
                        <th scope="col">DEPARTMENT NAME</th>
                        <th scope="col">REQUEST TYPE</th>
                        <th scope="col">ACTION</th>
                    </tr>
             
                </thead>

                {{-- <tbody>

                    @foreach ($requestsWithCompliance as $item)
                        <tr id="request-row-{{ $item['originalCompliance']->id ?? '#' }}">
                            <td>{{ $item['originalCompliance']->id ?? '#' }}</td>
                            <td>{{ $item['originalCompliance']->compliance_name ?? $item['changes']['compliance_name'] }}</td>
                            <td>
                                @if(isset($item['originalCompliance']->department->department_name))
                                    {{ $item['originalCompliance']->department->department_name }}
                                @else
                                    <script>
                                        document.write(departmentMapping[{{ $item['changes']['department_id'] }} - 1].department_name || 'Unknown Department');
                                    </script>
                                @endif
                            </td>
                            <td>ADD/EDIT/DELETE</td>
                            <td>
                                @if ($item['request']->action === 'add')
                                    <a href="#" 
                                    class="view-btn add-request-compliance"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#addRequestComplianceModal"
                                    data-compliance-id="{{ $item['request']['id'] }}"
                                    data-compliance-name="{{ $item['changes']['compliance_name'] }}"
                                    data-department-id="{{ $item['changes']['department_id'] }}"
                                    data-compliance-reference-date="{{ $item['changes']['reference_date'] }}"
                                    data-compliance-frequency="{{ $item['changes']['frequency'] }}"
                                    data-compliance-start-working-on="{{ $item['changes']['start_working_on'] }}"
                                    data-compliance-submit-on="{{ $item['changes']['submit_on'] }}"
                                    ><i class="fa-regular fa-square-plus"></i></a>
                                @elseif ($item['request']->action === 'edit')
                                    <a href="#" 
                                    class="edit-btn edit-request-compliance"
                                    data-bs-toggle="modal" 
                                    data-compliance-id="{{ $item['request']['id'] }}"

                                    data-compliance-name="{{ $item['originalCompliance']->compliance_name }}"
                                    data-department-id="{{ $item['originalCompliance']->department_id }}"
                                    data-compliance-reference-date="{{ $item['originalCompliance']->reference_date }}"
                                    data-compliance-frequency="{{ $item['originalCompliance']->frequency }}"
                                    data-compliance-start-working-on="{{ $item['originalCompliance']->start_working_on }}"
                                    data-compliance-submit-on="{{ $item['originalCompliance']->submit_on }}"

                                    data-new-compliance-name="{{ $item['changes']['compliance_name'] }}"
                                    data-new-department-id="{{ $item['changes']['department_id'] }}"
                                    data-new-compliance-reference-date="{{ $item['changes']['reference_date'] }}"
                                    data-new-compliance-frequency="{{ $item['changes']['frequency'] }}"
                                    data-new-compliance-start-working-on="{{ $item['changes']['start_working_on'] }}"
                                    data-new-compliance-submit-on="{{ $item['changes']['submit_on'] }}"
                                    
                                    data-bs-target="#editRequestComplianceModal"
                                    ><i class="fa-regular fa-pen-to-square"></i></a>
                                @else
                                    <a href="#" 
                                    class="delete-btn delete-request-compliance"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteRequestComplianceModal"
                                    data-compliance-id="{{ $item['request']['id'] }}"
                                    data-compliance-name="{{ $item['originalCompliance']->compliance_name }}"
                                    data-department-id="{{ $item['originalCompliance']->department_id }}"
                                    data-compliance-reference-date="{{ $item['originalCompliance']->reference_date }}"
                                    data-compliance-frequency="{{ $item['originalCompliance']->frequency }}"
                                    data-compliance-start-working-on="{{ $item['originalCompliance']->start_working_on }}"
                                    data-compliance-submit-on="{{ $item['originalCompliance']->submit_on }}"
                                    ><i class="fa-regular fa-trash-can"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody> --}}
     
              </table>

        </div>
    </div>

</x-main>

<script>
    // Request Table
    let requestTable = $('#requestComplianceTable').DataTable({
        language: {
            emptyTable: "No requests for change available",
            zeroRecords: "No matching requests found",
            infoEmpty: "No requests to display",
            lengthMenu: "_MENU_ entries per page", // Change "Show entries" text
            search: '', // Set search label to an empty string
            searchPlaceholder: 'Search...' // Set the placeholder text
        },
        pageLength: 20, // Default entries to display
        lengthMenu: [20, 30, 40, 50, 100], // Options available in the dropdown
        processing: true,
        serverSide: true,
        ajax: '{{ route('complianceRequests') }}',
        paging: false,
        info: false,
        columns: [
            { data: 'id', name: 'id', visible: false },
            { data: 'complianceId', name: 'complianceId' },
            { data: 'compliance_name', name: 'compliance_name' },
            { data: 'department_name', name: 'department_name' },
            { data: 'request_type', name: 'request_type' },
            { data: 'action', name: 'action' },
        ],
        order: [[0, 'desc']],
    });

    requestTable.on('draw', function () {
        updateBadge();
    });

    function updateBadge() {
        let totalRows = requestTable.rows({ filter: 'applied' }).data().length;
        $('#requestBadge').text(totalRows); // Update badge

        if (totalRows > 0) {
            $('#requestBadge').text('');
            $('#requestBadge').text(totalRows).show();
        } else {
            $('#requestBadge').hide();
        }
    }


</script>

<script>
    // Format Date
    function formatDate(myDate) {
        const date = new Date(myDate); // Create a new Date object
        const options = { year: 'numeric', month: 'long', day: 'numeric' }; // Date options
        return date.toLocaleDateString('en-US', options); // Format to MM/DD/YYYY
    }

    let requestId = '';
    let responseComplianceName = '';

    // Add Request
    $(document).on('click', '.add-request-compliance', function() {

        // Get the data attributes from the clicked anchor tag
        const id = $(this).attr('data-compliance-id');
        const complianceName = $(this).attr('data-compliance-name');
        const departmentId = $(this).attr('data-department-id');
        const referenceDate = $(this).attr('data-compliance-reference-date');
        const frequency = $(this).attr('data-compliance-frequency');
        const startWorkingOn = $(this).attr('data-compliance-start-working-on');
        const submitOn = $(this).attr('data-compliance-submit-on');

        requestId = id;

        responseComplianceName = $(this).attr('data-compliance-name');

        $('#addRequestComplianceModal').modal('show');  // Show the modal
        // $('#addRequestComplianceModal #aRequestComplianceId').val(id);   // Set the ID in a hidden input
        $('#addRequestComplianceModal #aRequestComplianceName').text(complianceName);
        $('#addRequestComplianceModal #aRequestDepartmentName').text(departmentMapping[departmentId - 1].department_name);
        $('#addRequestComplianceModal #aRequestReferenceDate').text(formatDate(referenceDate));
        $('#addRequestComplianceModal #aRequestFrequency').text(frequencyMapping[frequency]);
        $('#addRequestComplianceModal #aRequestStartWorkingOn').text(startWorkingOnMapping[startWorkingOn]);
        $('#addRequestComplianceModal #aRequestSubmitOn').text(submitOnMapping[submitOn]);
    });

    // Edit Request
    $(document).on('click', '.edit-request-compliance', function() {
        event.preventDefault();

        // Original Compliance
        const id = $(this).attr('data-compliance-id');
        const complianceName = $(this).attr('data-compliance-name');
        const departmentId = $(this).attr('data-department-id');
        const referenceDate = $(this).attr('data-compliance-reference-date');
        const frequency = $(this).attr('data-compliance-frequency');
        const startWorkingOn = $(this).attr('data-compliance-start-working-on');
        const submitOn = $(this).attr('data-compliance-submit-on');

        requestId = id;

        // Request for Edit
        const rComplianceName = $(this).attr('data-new-compliance-name');
        const rDepartmentId = $(this).attr('data-new-department-id');
        const rReferenceDate = $(this).attr('data-new-compliance-reference-date');
        const rFrequency = $(this).attr('data-new-compliance-frequency');
        const rStartWorkingOn = $(this).attr('data-new-compliance-start-working-on');
        const rSubmitOn = $(this).attr('data-new-compliance-submit-on');

        responseComplianceName = $(this).attr('data-new-compliance-name');

        $('#editRequestComplianceModal').modal('show');  // Show the modal

        // Original Compliance
        $('#editRequestComplianceModal #eOriginalComplianceName').val(id);   // Set the ID in a hidden input
        $('#editRequestComplianceModal #eOriginalComplianceName').text(complianceName);
        $('#editRequestComplianceModal #eOriginalDepartmentName').text(departmentMapping[departmentId - 1].department_name);
        $('#editRequestComplianceModal #eOriginalReferenceDate').text(formatDate(referenceDate));
        $('#editRequestComplianceModal #eOriginalFrequency').text(frequencyMapping[frequency]);
        $('#editRequestComplianceModal #eOriginalStartWorkingOn').text(startWorkingOnMapping[startWorkingOn]);
        $('#editRequestComplianceModal #eOriginalSubmitOn').text(submitOnMapping[submitOn]);

        // Request for Edit
        $('#editRequestComplianceModal #eRequestComplianceName').text(rComplianceName);
        $('#editRequestComplianceModal #eRequestDepartmentName').text(departmentMapping[rDepartmentId - 1].department_name);
        $('#editRequestComplianceModal #eRequestReferenceDate').text(formatDate(rReferenceDate));
        $('#editRequestComplianceModal #eRequestFrequency').text(frequencyMapping[rFrequency]);
        $('#editRequestComplianceModal #eRequestStartWorkingOn').text(startWorkingOnMapping[rStartWorkingOn]);
        $('#editRequestComplianceModal #eRequestSubmitOn').text(submitOnMapping[rSubmitOn]);
    });

    // Delete Request
    $(document).on('click', '.delete-request-compliance', function() {
        event.preventDefault();

        // Get the data attributes from the clicked anchor tag
        const id = $(this).attr('data-compliance-id');
        const complianceName = $(this).attr('data-compliance-name');
        const departmentId = $(this).attr('data-department-id');
        const referenceDate = $(this).attr('data-compliance-reference-date');
        const frequency = $(this).attr('data-compliance-frequency');
        const startWorkingOn = $(this).attr('data-compliance-start-working-on');
        const submitOn = $(this).attr('data-compliance-submit-on');

        requestId = id;
        responseComplianceName = $(this).attr('data-compliance-name');

        $('#deleteRequestComplianceModal').modal('show');  // Show the modal
        $('#deleteRequestComplianceModal #dRequestComplianceId').val(id);   // Set the ID in a hidden input
        $('#deleteRequestComplianceModal #dRequestComplianceName').text(complianceName);
        $('#deleteRequestComplianceModal #dRequestDepartmentName').text(departmentMapping[departmentId - 1].department_name);
        $('#deleteRequestComplianceModal #dRequestReferenceDate').text(formatDate(referenceDate));
        $('#deleteRequestComplianceModal #dRequestFrequency').text(frequencyMapping[frequency]);
        $('#deleteRequestComplianceModal #dRequestStartWorkingOn').text(startWorkingOnMapping[startWorkingOn]);
        $('#deleteRequestComplianceModal #dRequestSubmitOn').text(submitOnMapping[submitOn]);
    });

</script>

<style>
    /* Datatable CSS */
    .dataTable td {
        color: var(--primary-color-text) !important;
        text-align: center !important;
        font-size: 14px !important;
        background: var(--card-fill) !important;
        border: 0 !important;
        border-top: 1px solid var(--border) !important;
        padding: 20px 10px !important;
        max-width: 300px; /* Adjust this width as needed */
        white-space: normal; /* Allow line breaks */
        overflow: hidden; /* Hide overflow if necessary */
        text-overflow: ellipsis; /* Show ellipsis if text overflows */
    }

    .dataTables_filter {
        margin-top: 25px !important;
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

    /* Hide the sorting arrows for all columns */
    th.sorting::before, 
    th.sorting_asc::before, 
    th.sorting_desc::before,
    th.sorting::after, 
    th.sorting_asc::after, 
    th.sorting_desc::after {
        content: '' !important;        
        background: none !important;
    }
</style>