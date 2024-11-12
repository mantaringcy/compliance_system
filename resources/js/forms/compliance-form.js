import { toast } from '../app';

let originalData = {};

$(document).on('click', '.edit-compliance', function () {
    const id = $(this).attr('data-compliance-id');
    const complianceName = $(this).attr('data-compliance-name');
    const departmentId = $(this).attr('data-department-id');
    const referenceDate = $(this).attr('data-compliance-reference-date');
    const frequency = $(this).attr('data-compliance-frequency');
    const startWorkingOn = $(this).attr('data-compliance-start-working-on');
    const submitOn = $(this).attr('data-compliance-submit-on')
    
    // Store original data for comparison
    originalData = { complianceName, departmentId, referenceDate, frequency, startWorkingOn, submitOn };

    // console.log("Original Data", originalData);
    
    $('#editComplianceModal #complianceName').val(complianceName);
    $('#editComplianceModal #departmentSelect').val(departmentId);
    $('#editComplianceModal #referenceDate').val(referenceDate);
    $('#editComplianceModal #frequency').val(frequency);
    $('#editComplianceModal #startWorkingOn').val(startWorkingOn);
    $('#editComplianceModal #submitOn').val(submitOn);
});

// Add Compliance Error
$('#newComplianceForm').on('submit', function(event) {
    event.preventDefault();  // Prevent the default form submission

    $('#newComplianceForm').attr('action', '/compliances/');

    // Perform your AJAX update here
    $.ajax({
        url: $(this).attr('action'),  // Use the form action URL
        type: 'POST',  // Use POST with _method field for PUT
        data: $(this).serialize(),  // Serialize form data
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success, e.g., update the DataTable or show a success message
            $('#newComplianceModal').modal('hide');
            $('#complianceListTable').DataTable().ajax.reload(null, false);  // Reload DataTable data
            $('#newComplianceForm')[0].reset();
            toast('#alert-compliance-created', null, response.compliance_name, response);
        },
        error: function(xhr) {
            $('.invalid-feedback').text(''); // Display the first message
            
            if (xhr.responseJSON.errors) {
                // Loop through each error
                $.each(xhr.responseJSON.errors, function(fieldName, messages) {
                    // Display the first error message for each field
                    $('.' + fieldName).text(messages[0]); // Display the first message
                });
            }
        }
    });
});

// Edit Compliance Form Submit
$('#editComplianceForm').on('submit', function(event) {
    event.preventDefault();  // Prevent the default form submission
    
    // Get the values from the form
    const complianceId = $('#complianceId').val();
    const complianceName = $('#complianceName').val();
    const complianceDepartment = $('#departmentSelect').val();
    const complianceReferenceDate = $('#referenceDate').val();
    const complianceFrequency = $('#frequency').val();
    const complianceStartWorkingOn = $('#startWorkingOn').val();
    const complianceSubmitOn = $('#submitOn').val();

    const updatedData = {
        complianceName: $('#editComplianceModal #complianceName').val(),
        departmentId: $('#editComplianceModal #departmentSelect').val(),
        referenceDate: $('#editComplianceModal #referenceDate').val(),
        frequency: $('#editComplianceModal #frequency').val(),
        startWorkingOn: $('#editComplianceModal #startWorkingOn').val(),
        submitOn: $('#editComplianceModal #submitOn').val(),
    };


    // Compare original data with updated data
    const hasChanges = Object.keys(updatedData).some(key => updatedData[key] !== originalData[key]);

    if (!hasChanges) {
        alert('No changes detected. Please modify the compliance data before submitting.');
        return; // Prevent submission
    }

    $('#editComplianceForm').attr('action', '/compliances/' + complianceId);

    // Perform your AJAX update here
    $.ajax({
        url: $(this).attr('action'),  // Use the form action URL
        type: 'POST',  // Use POST with _method field for PUT
        data: $(this).serialize(),  // Serialize form data
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success, e.g., update the DataTable or show a success message
            $('#editComplianceModal').modal('hide');
            $('#complianceListTable').DataTable().ajax.reload(null, false);  // Reload DataTable data

            toast('#alert-compliance-edited', complianceId, null, response);
        },
        error: function(xhr) {
            $('.invalid-feedback').text(''); // Display the first message
            
            if (xhr.responseJSON.errors) {
                // Loop through each error
                $.each(xhr.responseJSON.errors, function(fieldName, messages) {
                    // Display the first error message for each field
                    $('.' + fieldName).text(messages[0]); // Display the first message
                });
            }
        }
    });
});

// Edit Compliance Form Submit
$('#deleteComplianceForm').on('submit', function(event) {
    event.preventDefault();  // Prevent the default form submission
    
    // Get the values from the form
    const complianceId = $('#complianceId').val();

    // console.log(complianceId);

    $('#deleteComplianceForm').attr('action', '/compliances/' + complianceId);

    // Perform your AJAX update here
    $.ajax({
        url: $(this).attr('action'),  // Use the form action URL
        type: 'POST',  // Use POST with _method field for PUT
        data: $(this).serialize(),  // Serialize form data
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success, e.g., update the DataTable or show a success message
            $('#deleteComplianceModal').modal('hide');
            $('#complianceListTable').DataTable().ajax.reload(null, false);  // Reload DataTable data

            toast('#alert-compliance-deleted', complianceId, null, response);
        },
    });
});