@section('title', 'Forgot Password')

<x-main-auth>
        
    <div class="image">
        <img src="{{ URL('images/People360 Logo Light.png') }}" alt="logo" class="mb-4">
        <img src="{{ URL('images/People360 Logo Dark.png') }}" alt="logo" class="mb-4">
    </div>
    
    <h3 class="mb-2 fw-semibold" style="color: var(--primary-color-text) !important;">Reset Password</h3>
    <p>Please choose your new password</p>
    

    <form action="{{ route('password.update') }}" method="post">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email -->
        <div class="mb-2">
            <label for="email" class="mb-2" style="color: var(--primary-color-text) !important;">Email Address</label>
            <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Password -->
        <div class="mb-2">
            <label for="password" class="mb-2" style="color: var(--primary-color-text) !important;">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="mb-2" style="color: var(--primary-color-text) !important;">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" placeholder="Confirm Password">
        </div>

        <!-- Reset Password -->
        <button class="btn border-0 text-white w-100 ">Reset Password</button>

    </form>

</x-main-auth>