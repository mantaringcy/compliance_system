@section('title', 'Account Profile')

<x-main>
    <h2 class="fw-bold mb-5" style="font-size: 30px !important;">Account Profile</h2>

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
                        
                        @php
                            $userDepartment = Auth::user()->department_id;
                            $userRole = Auth::user()->role_id;

                            $userDepartmentName = Auth::user()->department->department_name;
                            $userRoleName = Auth::user()->role->role_name;
                        @endphp

                        <!-- Department Name -->
                        <div class="col-md-6">
                            <label for="department_id" class="mb-2">Department Name</label>
                            <select class="form-select" name="department_id" id="departmentSelect">
                                @if (in_array($userDepartment, [1, 2]) && in_array($userRole, [1, 2, 3]))
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" {{ $department->id == $userDepartment ? 'selected' : '' }}>{{ $department->department_name }}</option>
                                        @endforeach
                                @else
                                        <option value="{{ $userDepartment }}">{{ $userDepartmentName }}</option>
                                @endif
                            </select>
                        </div>
                        
                        <!-- Role -->
                        <div class="col-md-6">
                            <label for="role_id" class="mb-2">Role</label>
                            <select class="form-select" name="role_id">
                            @if (in_array($userDepartment, [1, 2]) && in_array($userRole, [1, 2, 3]))
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $role->id == $userRole ? 'selected' : '' }} id="role_id">{{ $role->role_name }}</option>
                                @endforeach
                            @else
                                <option value="{{ $userRole }}">{{ $userRoleName }}</option>
                            @endif
                            </select>
                        </div>

                    </div>

                    {{-- End of Card Body --}}
                    </div>
                    
                    <span class="line"></span>

                    <!-- Button -->
                    <div class="card-button text-end">
                        <x-button 
                            class="update-btn" 
                            id="updateProfileBtn" 
                            spinnerId="buttonProfileSpinner" 
                            textId="buttonProfileText" 
                            text="Update Profile" 
                        />
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

                {{-- Form --}}
                <form action="{{ route('update.password') }}" method="post" id="updatePasswordForm">
                    @csrf
                    {{-- @method('PUT') --}}

                    <input type="hidden" name="update_type" value="password">

                    <div class="row">

                        <!-- Old Password -->
                        <x-password-input
                            class="col-md-12 mb-5"
                            label="Old Password"
                            inputName="old_password"
                            iconName="old_password_icon"
                            placeholder="Old Password"
                        />

                        {{-- <div class="col-md-12 mb-5">
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
                                onclick="togglePasswordVisibility('old_password_icon', 'old_password')"
                                style="display: none !important;"
                            >
                            </i>  
                            
                            @error('old_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <!-- New Password -->
                        <x-password-input
                            class="col-md-6"
                            label="New Password"
                            inputName="new_password"
                            iconName="new_password_icon"
                            placeholder="New Password"
                        />
                        {{-- <div class="col-md-6">
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
                           onclick="togglePasswordVisibility('new_password_icon', 'new_password')"
                            style="display: none !important;"
                            >
                            </i>  

                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <!-- Confirm Password -->
                        <x-password-input
                            class="col-md-6"
                            label="Confirm Password"
                            inputName="new_password_confirmation"
                            id="password_confirmation"
                            iconName="new_password_confirmation_icon"
                            placeholder="Confirm Password"
                        />
                       {{-- <div class="col-md-6">
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
                           onclick="togglePasswordVisibility('new_password_confirmation_icon', 'password_confirmation')"
                            style="display: none !important;"
                            >
                            </i>  
         
                        </div>  --}}

                    </div>
                    
                    {{-- End of Card Body --}}
                    </div>

                    <span class="line"></span>

                    <!-- Button -->
                    <div class="card-button text-end">
                        <x-button 
                            class="update-btn" 
                            id="updatePasswordBtn" 
                            spinnerId="buttonPasswordSpinner" 
                            textId="buttonPasswordText" 
                            text="Update Password" 
                        />
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
</style>