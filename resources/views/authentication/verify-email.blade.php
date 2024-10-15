@section('title', 'Login')

<x-main-auth>

    <div class="image">
        <img src="{{ URL('images/People360 Logo Light.png') }}" alt="logo" class="mb-4">
        <img src="{{ URL('images/People360 Logo Dark.png') }}" alt="logo" class="mb-4">
    </div>

    <h3 class="mb-3 fw-semibold" style="color: var(--primary-color-text) !important;">Hi {{ auth()->user()->username }}, Check Your Mail</h3>

    <p>We have send a confirmation to your email</p>

    <form action="{{ route('verification.send') }}" method="post">
        @csrf

        <!-- Password -->
        <button class="btn border-0 text-white w-100 ">Send Again</button>

    </form>

</x-main-auth>