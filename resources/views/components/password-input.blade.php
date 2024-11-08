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

<div class="{{ $class }}">
    @if($label)
        <label for="{{ $id }}" class="mb-2">
                <label>{{ $label }}</label>  <!-- Only display the label if it's provided -->
        </label>
    @endif
    <input 
    type="password" 
    name="{{ $inputName }}" 
    class="form-control @error('{{ $id }}') is-invalid @enderror" 
    {{ $attributes }}
    id="{{ $id }}"
    oninput="toggleIconVisibility('{{ $id }}', '{{ $iconId }}')"
    >

    <i 
    class="togglePasswordIcon fa-solid fa-eye" 
    id="{{ $iconName }}" 
    onclick="togglePasswordVisibility('{{ $iconId }}', '{{ $id }}')"
    style="display: none !important;"
    >
    </i>  

    @error('{{ $id }}')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>