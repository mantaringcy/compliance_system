@section('title', 'Forgot Password')

<x-main-auth>

    <div class="image">
        <img src="{{ URL('images/People360 Logo Light.png') }}" alt="logo">
        <img src="{{ URL('images/People360 Logo Dark.png') }}" alt="logo">
    </div>
    
    <div class="after-button-div">
        <h3 class="mb-3 fw-semibold" style="color: var(--primary-color-text) !important;">Forgot Password</h3>
        <a href="login" class="text-decoration-none">Back to Login</a>
    </div>

    <form action="{{ route('password.request') }}" method="post">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="mb-2" style="color: var(--primary-color-text) !important;">Email Address</label>
            <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <p style="font-size: 12px; margin-bottom: 12px !important;">Do not forgot to check SPAM box.</p>

        <!-- Password -->
        <button class="btn border-0 text-white w-100 ">Send Password Reset Email</button>

    </form>

</x-main-auth>