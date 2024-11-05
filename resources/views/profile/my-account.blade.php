@section('title', 'Account Profile')



<x-main>
    <h2 class="fw-bold mb-5" style="font-size: 30px !important;">Account Profile</h2>

    </div>


    <div>
        <!-- General Setttings -->
        <div class="card-lg mb-5">
            <div class="card-title">
                <h5 class="fw-semibold" style="font-size: 16px !important;">General Settings</h5>
         
            </div>

            <span class="line"></span>

            <div class="card-body">

                <div class="custom-alert custom-alert-green" id="alertProfileUpdate" style="display: none;">
                </div>

                {{-- Form --}}
                <form action="{{ route('update.profile') }}" method="post" id="updateProfileForm">
                    @csrf

                    <input type="hidden" name="update_type" value="profile">

                    <div class="row">

                        <!-- Username -->
                        <div class="col-md-6 mb-5">
                            <label for="username" class="mb-2">Username *</label>
                            <input type="text" name="username" class="form-control"  placeholder="Username" id="username" value="{{ Auth::user()->username }}">      
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="mb-2">Email *</label>
                            <input type="text" name="email" class="form-control"  placeholder="Email" id="email" value="{{ Auth::user()->email }}">             
                        </div>
                        
                        <!-- Department Name -->
                        <div class="col-md-6">
                            <label for="department_id" class="mb-2">Department Name</label>
                            <select class="form-select @error('department_id') is-invalid @enderror" aria-label="Default select example" name="department_id" id="departmentSelect">
                                @php
                                    $userDepartment = Auth::user()->department_id;
                                @endphp

                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ $department->id == $userDepartment ? 'selected' : '' }}>{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Role -->
                        <div class="col-md-6">
                            <label for="role_id" class="mb-2">Role</label>
                            <select class="form-select" name="role_id">
                                @php
                                    $userRole = Auth::user()->role_id;
                                @endphp

                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $role->id == $userRole ? 'selected' : '' }} id="role">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- End of Card Body --}}
                    </div>
                    
                    <span class="line"></span>

                    <!-- Button -->
                    <div class="card-button text-end">
                        {{-- <button class="btn btn-outline-secondary cancel-btn me-1">Cancel</button> --}}
                        <button type="submit" class="btn btn-primary update-btn">Update Profile</button>
                    </div>

                </form>

        </div>


        <!-- Change Password -->
        <div class="card-lg">
            <div class="card-title">
                <h5 class="fw-semibold" style="font-size: 16px !important;">Change Password</h5>
         
            </div>

            <span class="line"></span>

            <div class="card-body">

                <div class="custom-alert custom-alert-green" id="alertPasswordUpdateSuccess" style="display: none;">
                </div>

                <div class="custom-alert custom-alert-red" id="alertPasswordUpdateError" style="display: none;">
                </div>

                {{-- @if(session('success_password')) --}}
                    {{-- <div class="custom-alert custom-alert-green">
                        {{ session('success_password') }}
                    </div> --}}
                {{-- @endif --}}

                {{-- @error('old_password')
                    <div class="custom-alert custom-alert-red auto-close-alert">
                        {{ $message }}
                    </div>
                @enderror --}}

                {{-- Form --}}
                <form action="{{ route('update.password') }}" method="post" id="updatePasswordForm">
                    @csrf
                    {{-- @method('PUT') --}}

                    <input type="hidden" name="update_type" value="password">

                    <div class="row">

                        <!-- Old Password -->
                        <div class="col-md-12 mb-5">
                            <label for="old_password" class="mb-2">Old Password *</label>
                            <input 
                                type="password" 
                                name="old_password" 
                                class="form-control @error('old_password') is-invalid @enderror" placeholder="Old Password" 
                                id="old_password"
                                oninput="toggleIconVisibility('old_password', 'old_password_icon')"
                            >
                            <i 
                                class="togglePasswordIcon fa-solid fa-eye" 
                                id="old_password_icon" 
                                onclick="togglePassswordVisibility('old_password_icon', 'old_password')"
                                style="display: none !important;"
                            >
                            </i>  
                            
                            @error('old_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="col-md-6">
                            <label for="new_password" class="mb-2">New Password *</label>
                            <input 
                                type="password" 
                                name="new_password" 
                                class="form-control @error('new_password') is-invalid @enderror"  placeholder="New Password" 
                                id="new_password"
                                oninput="toggleIconVisibility('new_password', 'new_password_icon')"
                            >         
                            <i 
                            class="togglePasswordIcon fa-solid fa-eye" 
                            id="new_password_icon" 
                            onclick="togglePassswordVisibility('new_password_icon', 'new_password')"
                            style="display: none !important;"
                            >
                            </i>  

                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label for="new_password_confirmation" class="mb-2">Confirm Password</label>
                            <input 
                                type="password" 
                                name="new_password_confirmation" 
                                class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                placeholder="Confirm Password" 
                                id="password_confirmation"
                                oninput="toggleIconVisibility('password_confirmation', 'new_password_confirmation_icon')"
                            >    
                            <i 
                            class="togglePasswordIcon fa-solid fa-eye" 
                            id="new_password_confirmation_icon" 
                            onclick="togglePassswordVisibility('new_password_confirmation_icon', 'password_confirmation')"
                            style="display: none !important;"
                            >
                            </i>  
         
                        </div>

                    </div>
                    
                    {{-- End of Card Body --}}
                    </div>

                    <span class="line"></span>

                    <!-- Button -->
                    <div class="card-button text-end">
                        {{-- <button class="btn btn-outline-secondary cancel-btn me-1">Cancel</button> --}}
                        <button type="submit" class="btn btn-primary update-btn">Update Password</button>
                    </div>

                </form>

        </div>
    </div>
    
</x-main>

<style>
    .card-title {
        padding: 27px 0px 6px 25px !important;
    }

    .line{
        display: inline-block;
        width: 100%;
        border-top: 1px solid var(--border);
    }

    .card-body {
        padding: 20px 25px !important;
    }

    .card-button {
        padding: 20px 30px 25px 30px !important;
    }

    .card-button .cancel-btn,
    .card-button .update-btn {
        height: 39px !important;
        border-radius: 100px !important;
        font-weight: 500;
        padding: 0px 15px;
    }

    .card-button .update-btn {
        border: 1px solid #737B83 !important;
    }

    .card-button .update-btn {
        border: 0 !important;
        background-color: var(--profile-fill-hover) !important;
    }

    .togglePasswordIcon {
        float: right !important;
        margin-left: -25px !important;
        margin-top: -30px !important;
        right: 10px !important;
        position: relative !important;
        z-index: 100 !important;
    }
</style>

<script>
    const departmentMapping = @json($departments);
    const roleMapping = @json($roles);
</script>

<script>
    function showAlert(alertId, response) {
        let message = response.message;
        // let action = response.action;
        // let complianceRef = complianceId 
        //     ? `no. ${complianceId}` 
        //     : (complianceName ? `'${complianceName}'` : '');

        if (response.success) {
            $(alertId).css('display', 'block');
            $(alertId).text(message);
        } else {
            $(alertId).css('display', 'block');
            $(alertId).text(message);
        }

        setTimeout(function() {
            $(alertId).fadeOut();
        }, 3000);
    }
    

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

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
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

</script>

<script>
    function togglePassswordVisibility(icon, fieldId) {

        const toggleIcon = document.getElementById(icon);
        const inputFieldType = document.getElementById(fieldId);

        if (inputFieldType.type === "password") {
            inputFieldType.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            inputFieldType.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }

    function toggleIconVisibility(fieldId, iconContainerId) {
        const passwordField = document.getElementById(fieldId);
        const iconContainer = document.getElementById(iconContainerId);

        // Show the icon only if there's input
        if (passwordField.value.length > 0) {
            iconContainer.style.display = "block"; // Show icon
        } else {
            iconContainer.style.display = "none"; // Hide icon
        }
    }

    function autoCloseAlert(timeout = 3000) {
        // Select all elements with the 'auto-close-alert' class
        const alertElements = document.querySelectorAll('.auto-close-alert');

        // Loop through each alert element and set a timeout to hide it
        alertElements.forEach(alertElement => {
            setTimeout(() => {
                alertElement.style.display = 'none';
            }, timeout);
        });
    }

    // Call the function after the page loads
    document.addEventListener("DOMContentLoaded", function() {
        autoCloseAlert();
    });
</script>