// Bootstrap
import 'bootstrap/dist/js/bootstrap.bundle.min';
import 'bootstrap/dist/css/bootstrap.min.css';

// Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css';

// JS Files
import './components/theme';
import './components/sidebar';
import './components/navigation-links';
import './components/media-query';


function formatDate(dateString) {
    // Create an array of month names
    const monthNames = ["January", "February", "March", "April", "May", "June", 
                        "July", "August", "September", "October", "November", "December"];
    
    // Create a new Date object from the incoming string
    const date = new Date(dateString);
    
    // Extract the month, day, and year
    const month = monthNames[date.getMonth()]; // Get the month name
    const day = date.getDate();
    const year = date.getFullYear(); // Use the full year
    
    // Format the date as Month-D-Y
    return `${month} ${day}, ${year}`;
}

function showToast() {
    // Show the toast with a fade-in effect
    $('#customToast').addClass('show');

    // Automatically hide the toast after 3 seconds
    setTimeout(function() {
        $('#customToast').removeClass('show');
    }, 3000);
}

function alert(alertId, complianceId = null, complianceName = null, response) {
    let message = '';
    let action = response.action;
    let complianceRef = complianceId 
        ? `no. ${complianceId}` 
        : (complianceName ? `'${complianceName}'` : '');

    if (response.success) {
        showToast();
        $(alertId).css('display', 'block');

        switch(action) {
            case 'create_compliance':
                message = `Compliance ${complianceRef} has been created successfully.`;
                break;
            case 'edit_compliance':
                message = `Compliance ${complianceRef} has been edited successfully.`;
                break;
            case 'delete_compliance':
                message = `Compliance ${complianceRef} has been deleted successfully.`;
                break;
            case 'request_create_compliance':
                message = `Request for compliance ${complianceRef} creation has been submitted.`;
                break;
            case 'request_edit_compliance':
                message = `Request for compliance ${complianceRef} editing has been submitted.`;
                break;
            case 'request_delete_compliance':
                message = `Request for compliance ${complianceRef} deletion has been submitted.`;
                break;
            case 'approve_create_compliance':
                message = `Compliance ${complianceRef} creation has been approved.`;
                break;
            case 'approve_edit_compliance':
                message = `Compliance ${complianceRef} edit has been approved.`;
                break;
            case 'approve_delete_compliance':
                message = `Compliance ${complianceRef} deletion has been approved.`;
                break;
            case 'cancel_create_compliance':
                message = `Compliance ${complianceRef} creation has been canceled.`;
                break;
            case 'cancel_edit_compliance':
                message = `Compliance ${complianceRef} edit has been canceled.`;
                break;
            case 'cancel_delete_compliance':
                message = `Compliance ${complianceRef} deletion has been canceled.`;
                break;
            case 'cancel_request_create_compliance':
                message = `Request for compliance ${complianceRef} creation has been canceled.`;
                break;
            case 'cancel_request_edit_compliance':
                message = `Request for compliance ${complianceRef} editing has been canceled.`;
                break;
            case 'cancel_request_delete_compliance':
                message = `Request for compliance ${complianceRef} deletion has been canceled.`;
                break;
            default:
                message = 'Action not recognized.';
                break;
        }
        
        $(alertId).text(message);
    
        setTimeout(function() {
            $(alertId).fadeOut();
        }, 3000);
    }
}

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
            $('#complianceListTable').DataTable().ajax.reload();  // Reload DataTable data
            $('#newComplianceForm')[0].reset();
            alert('#alert-compliance-created', null, response.compliance_name, response);
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
            $('#complianceListTable').DataTable().ajax.reload();  // Reload DataTable data

            alert('#alert-compliance-edited', complianceId, null, response);
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
            $('#complianceListTable').DataTable().ajax.reload();  // Reload DataTable data

            alert('#alert-compliance-deleted', complianceId, null, response);
        },
    });
});

// ADD REQUEST MODAL
// Approve Button
$('#addApproveButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to approve this compliance?")) {
        $.ajax({
            url:'/admin/compliance/approve/' + requestId,  
            type: 'POST',  
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// Cancel Button
$('#addCancelButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to cancel this request?")) {
        $.ajax({
            url:'/admin/compliance/cancel/' + requestId, 
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// DELETE REQUEST MODAL
// Approve Button
$('#deleteApproveButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to approve the deletion of this compliance?")) {
        $.ajax({
            url:'/admin/compliance/approve/' + requestId,  
            type: 'POST',  
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// Cancel Button
$('#deleteCancelButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to cancel this request?")) {
        $.ajax({
            url:'/admin/compliance/cancel/' + requestId, 
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// EDIT REQUEST MODAL
// Approve Button
$('#editApproveButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to approve the editing of this compliance?")) {
        $.ajax({
            url:'/admin/compliance/approve/' + requestId,  
            type: 'POST',  
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// Cancel Button
$('#editCancelButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to cancel this request?")) {
        $.ajax({
            url:'/admin/compliance/cancel/' + requestId, 
            type: 'POST',
            data: $(this).serialize(),  // Serialize form data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});