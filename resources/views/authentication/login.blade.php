@section('title', 'Login')

<x-main-auth>
        
    <div class="image mx-auto">
        <img src="{{ URL('images/People360 Logo Light.png') }}" alt="logo" class="mx-auto mb-4">
        <img src="{{ URL('images/People360 Logo Dark.png') }}" alt="logo" class="mx-auto mb-4">
    </div>

    <h4 class="text-center mb-3">Login with your email</h4>

    <form action="{{ route('login') }}" method="post">
        @csrf

        <!-- Email -->
        <div class="mb-2">
            {{-- <label for="email">Email</label> --}}
            <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Password -->
        <x-password-input
            class="mb-4"
            inputName="password"
            iconName="password_icon"
            placeholder="Password"
        />

        {{-- <div class="mb-4">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div> --}}

        <!-- Remember Me, Forgot Password -->
        <div class="after-button-div mb-3">
            <div>
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label for="remember" class="form-check-label checkbox-label">Remember me?</label>
            </div>    
            <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
        </div>


        
        @error('failed')
            <p class="text-danger m-0 p-0" style="font-size: 12px !important;">{{ $message }}</p>
        @enderror
        
        <!-- Button -->
        {{-- <button class="btn border-0 text-white w-100 ">Login</button> --}}
        <x-button 
            class="update-btn w-100" 
            id="loginBtn" 
            spinnerId="buttonLoginSpinner" 
            textId="buttonLoginText" 
            text="Login" 
        />

        <!-- Create Account -->
        <div class="after-button-div">
            <h6 class="p-0 m-0">Don't have an Account?</h6>
            <a href="register" class="text-decoration-none">Create Account</a>
        </div>
    </form>

    
</x-main-auth>