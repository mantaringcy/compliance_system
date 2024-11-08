import { toast } from '../app';

// ADD REQUEST MODAL
// Approve Button
$('#addApproveButton').on('click', function(e) {
    e.preventDefault();


    if(confirm("Are you sure you want to approve this compliance?")) {
        $.ajax({
            url:'/admin/compliance/approve/' + requestId,  
            type: 'POST',  
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                $('#requestComplianceTable').DataTable().ajax.reload(); 

                const customResponse = {
                    success: true,
                    action: 'approve_create_compliance',
                    compliance_name: responseComplianceName,
                }

                toast('#alert-compliance-request-add', null, customResponse.compliance_name, customResponse);

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
                $('#requestComplianceTable').DataTable().ajax.reload(); 
                toast('#alert-compliance-request-cancel', null, response.compliance_name, response);
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
                $('#editRequestComplianceModal').modal('hide');
                $('#requestComplianceTable').DataTable().ajax.reload(); 

                const customResponse = {
                    success: true,
                    action: 'approve_edit_compliance',
                    compliance_name: responseComplianceName,
                }

                toast('#alert-compliance-request-edit', null, customResponse.compliance_name, customResponse);
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
                $('#editRequestComplianceModal').modal('hide');
                $('#requestComplianceTable').DataTable().ajax.reload();

                toast('#alert-compliance-request-cancel', null, response.compliance_name, response);
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
                $('#deleteRequestComplianceModal').modal('hide');
                $('#requestComplianceTable').DataTable().ajax.reload();

                const customResponse = {
                    success: true,
                    action: 'approve_delete_compliance',
                    compliance_name: responseComplianceName,
                }

                toast('#alert-compliance-request-delete', null, customResponse.compliance_name, customResponse);
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
                $('#deleteRequestComplianceModal').modal('hide');
                $('#requestComplianceTable').DataTable().ajax.reload(); 
                toast('#alert-compliance-request-cancel', null, response.compliance_name, response);
            }
        });

    }
});