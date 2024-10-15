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
            {{-- <label for="username">Username</label> --}}
            <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" placeholder="Username">

            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Email -->
        <div class="mb-2">
            {{-- <label for="email">Email</label> --}}
            <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Password -->
        <div class="mb-2">
            {{-- <label for="password">Password</label> --}}
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Confirm Password -->
        <div class="mb-2">
            {{-- <label for="password_confirmation">Confirm Password</label> --}}
            <input type="password" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" placeholder="Confirm Password">
        </div>

        <div class="mb-4">
            <select class="form-select" aria-label="Default select example" name="role_id">
                <option selected disabled>Select role</option>
                @foreach ($data as $row)
                    <option value="{{ $row->id }}">{{ $row->role_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Password -->
        <button class="btn btn-primary border-0 w-100">Register</button>

        <!-- Aldready Have an Account -->
        <div class="after-button-div">
            <h6 class="p-0 m-0">Already have an Account?</h6>
            <a href="login" class="text-decoration-none">Login here</a>
        </div>
    </form>
    
</x-main-auth>