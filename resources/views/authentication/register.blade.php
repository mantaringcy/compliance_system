@section('title', 'Register')

<x-main-auth>

    <div class="image mx-auto">
        <img src="{{ URL('images/People360 Logo Light.png') }}" alt="logo" class="mx-auto mb-4">
        <img src="{{ URL('images/People360 Logo Dark.png') }}" alt="logo" class="mx-auto mb-4">
    </div>

    <h4 class="text-center mb-3">Sign up with your email</h4>

    <form action="{{ route('register') }}" method="post">
        @csrf

        <!-- Username -->
        <div class="mb-2">
            <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" placeholder="Username">

            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Email -->
        <div class="mb-2">
            <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Password -->
        <div class="mb-2">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Confirm Password -->
        <div class="mb-2">
            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password">
        </div>

        <!-- Department -->
        <div class="mb-2">
            <select class="form-select @error('department_id') is-invalid @enderror" aria-label="Default select example" name="department_id">
                <option selected disabled>Select Department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                @endforeach
            </select>

            @error('department_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Role -->
        <div class="mb-4">
            <select class="form-select @error('role_id') is-invalid @enderror" aria-label="Default select example" name="role_id">
                <option selected disabled>Select Role</option>
                @foreach ($data as $role)
                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                @endforeach
            </select>

            @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <!-- Button -->
        <button class="btn btn-primary border-0 w-100">Register</button>

        <!-- Aldready Have an Account -->
        <div class="after-button-div">
            <h6 class="p-0 m-0">Already have an Account?</h6>
            <a href="login" class="text-decoration-none">Login here</a>
        </div>
    </form>
    
</x-main-auth>