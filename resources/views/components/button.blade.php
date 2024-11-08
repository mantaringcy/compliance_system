<button type="submit" 
        class="btn btn-primary {{ $class }}" 
        id="{{ $id }}">
    <span id="{{ $spinnerId }}" 
        class="spinner-grow spinner-grow-sm" 
        role="status" 
        style="display: none;" 
        aria-hidden="true">
    </span>
    <span id="{{ $textId }}">
        {{ $text }}
    </span>
</button>

{{-- <button type="submit" class="btn btn-primary update-btn" id="updatePasswordBtn">
    <span id="buttonPasswordSpinner" class="spinner-grow spinner-grow-sm"  role="status" style="display: none;" aria-hidden="true"></span>
    <span id="buttonPasswordText">Update Password</span>
</button> --}}