import { toggleButtonLoading } from '../app';
import { showAlert } from '../app';

let initialProfileFormData = {
    username: $('#username').val(),
    email: $('#email').val(),
    // departmentId: $('#departmentSelect').val(),
    departmentId: $('select[name="department_id"]').val(),
    roleId: $('select[name="role_id"]').val(),
}; 

$('#updateProfileForm').on('submit', function(event) {
    event.preventDefault();

    const currentProfileFormData = {
        username: $('#username').val(),
        email: $('#email').val(),
        departmentId: $('#departmentSelect').val(),
        roleId: $('select[name="role_id"]').val(),
    }

    const hasChanges = Object.keys(currentProfileFormData).some(key => currentProfileFormData[key] !== initialProfileFormData[key]);

    if (!hasChanges) {
        alert('No changes detected. Please make changes to update your profile.');
        return;
    }

    toggleButtonLoading('#updateProfileBtn', true, '#buttonProfileText', 'Updating Profile')

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showAlert('#alertProfileUpdate', response);

            // Get the username from your data object
            let username = currentProfileFormData['username'];
            let role = currentProfileFormData['roleId'];
            let department = currentProfileFormData['departmentId'];
            
            // Capitalize the first letter of the username
            let capitalizedUsername = username.charAt(0).toUpperCase() + username.slice(1);
            
            // Set the capitalized username to the sidebar element
            $('#sidebar-username').text(capitalizedUsername);
            $('#sidebar-role').text(roleMapping[role - 1].role_name);
            $('#sidebar-department').text(departmentMapping[department - 1].department_name + ' Department');

            toggleButtonLoading('#updateProfileBtn', false, '#buttonProfileText', 'Update Profile')

            initialProfileFormData = currentProfileFormData;
        },
        error: function(xhr) {
            
        }
    });
});

$('#updatePasswordForm').on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    let oldPassword = $('#old_password').val();
    let newPassword = $('#new_password').val();
    let confirmPassword = $('#password_confirmation').val();

    // Validate that all fields are filled
    if (!oldPassword || !newPassword || !confirmPassword) {
        alert('Please fill out all password fields.');
        return; // Exit the function if any field is empty
    }

    if (newPassword === confirmPassword) {
        toggleButtonLoading('#updatePasswordBtn', true, '#buttonPasswordText', 'Updating Password')
    }


    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            toggleButtonLoading('#updatePasswordBtn', false, '#buttonPasswordText', 'Update Password')

            if (response.success) {
                showAlert('#alertPasswordUpdateSuccess', response);
                $('#updatePasswordForm').trigger('reset');
            } else {
                showAlert('#alertPasswordUpdateError', response);
            }
        },
        error: function(xhr, status, error) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;

                if (errors.new_password) {
                    alert(errors.new_password[0]); // Display error for new password mismatch
                }

                if (errors.old_password) {
                    alert(errors.old_password[0]); // Display error if old password is incorrect
                }
            }

            console.error('Password update error:', error);
            $('#alert').text('An error occurred while updating the password.').show();
        }
    });
});