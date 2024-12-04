@section('title', 'Compliance Management Update')

<x-main>
    {{-- <h2 class="fw-bold mb-5" style="font-size: 30px !important;">{{ $monthlyCompliance->compliance_name }}</h2> --}}

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="">
            <h2 class="fw-bold" style="font-size: 30px !important;">{{ $monthlyCompliance->compliance_name }}</h2>
        </div>

        @if (Auth::user()->role->id == 3)
            <div class="d-flex">
                <!-- Status -->
                <div class="me-2">
                    {{-- <label for="status" class="mb-2">Status</label> --}}
                    <select name="status" id="status" class="form-select" onchange="updateStatus()">
                        @foreach($enumValues as $value)
                            <option value="{{ $value }}" {{ $monthlyCompliance->status === $value ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <!-- Approve Button -->
                <button class="btn btn-success" id="approveButton" onclick="approveCompliance()" 
                    {{ $monthlyCompliance->status === 'completed' ? 'disabled' : '' }}>
                    {{ $monthlyCompliance->status === 'completed' ? 'Approved' : 'Approve' }}
                </button>
            </div>
        @endif

        

    </div>

    

    {{-- Image Upload --}}
    @php
        $filePaths = json_decode($monthlyCompliance->file_path, true);
    @endphp

    <div class="bottom-content mb-5">
        {{-- Details UI & Image UI --}}
        <div class="bottom-left col-7">

            <div class="card-bottom" style="margin-bottom: 20px !important;">
                        
                <table class="table table-striped m-0 table-details">
                    <tbody>
                        <tr class="border-top">
                            <td class="fw-bold">Compliance ID:</td>
                            <td>{{ $monthlyCompliance->compliance_id }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Department Name:</td>
                            <td>{{ $monthlyCompliance->department->department_name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Start Date:</td>
                            <td>{{ \Carbon\Carbon::parse($monthlyCompliance['computed_start_date'])->format('F j, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Submit Date:</td>
                            <td>{{ \Carbon\Carbon::parse($monthlyCompliance['computed_submit_date'])->format('F j, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Deadline:</td>
                            <td>{{ \Carbon\Carbon::parse($monthlyCompliance['computed_deadline'])->format('F j, Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            
            </div>


            <div class="card-image h-50">
                @if (!empty($filePaths))
        
                    <form id="uploadForm" enctype="multipart/form-data">
        
                        <div class="upload-container" onclick="document.getElementById('images').click()">
                            @foreach ($filePaths as $filePath)
                                @php
                                    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                    $isPdf = strtolower($extension) === 'pdf';
                                @endphp

                                <div class="position-relative d-inline-block image-card uploaded-images" style="width: 100; height: 100;">
        
                                    <!-- Image -->
                                    {{-- <img src="{{ asset('storage/' . $filePath) }}" alt="Compliance Image" > --}}

                                    @if ($isPdf)
                                        <img src="{{ asset('images/pdf.png') }}" alt="PDF Icon" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('storage/' . $filePath) }}" alt="Compliance Image" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                
                                    <!-- Hover Overlay -->
                                    <div class="overlay d-flex flex-column justify-content-center align-items-center position-absolute top-0 start-0 bg-dark bg-opacity-50 text-white" style="display: none;" onclick="event.stopPropagation()">
            
                                        <!-- View Full Image Button -->
                                        @if ($isPdf)
                                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="btn btn-sm btn-light mb-1">View PDF</a>
                                        @else
                                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="btn btn-sm btn-light mb-1">View Image</a>
                                        @endif

                                        {{-- <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="btn btn-sm btn-light mb-1">View Image</a> --}}
            
                                        <!-- Delete Button -->
                                        <form action="{{ route('compliance-management.delete-image', $monthlyCompliance->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                onclick="deleteImage({{ $monthlyCompliance->id }}, '{{ $filePath }}')"
                                                class="btn btn-sm btn-danger"
                                            >
                                                Delete
                                            </button>
                                        </form>
                            
                                    </div>
                                </div>
                            @endforeach
                        </div>
        
                        <input 
                            type="file" 
                            id="images" 
                            name="images[]" 
                            accept=".jpg,.jpeg,.png,.pdf" 
                            multiple 
                            {{-- onchange="uploadImages(event)"  --}}
                            style="display: none;"
                        >
        
                    </form>
        
          
                @else
                    <form id="uploadForm" enctype="multipart/form-data">
        
                        <div class="container upload-container" onclick="document.getElementById('images').click()">
        
                            <div class="upload-text">
                                Click to upload or drag and drop images here
                            </div>
        
                        </div>
        
                        <input 
                            type="file" 
                            id="images" 
                            name="images[]" 
                            accept=".jpg,.jpeg,.png,.pdf" 
                            multiple 
                            onchange="uploadImages(event)" 
                            style="display: none;"
                        >
                    </form>
                @endif
            </div>
        </div>
    
        {{-- Message UI --}}
        <div class="card-lg card-message col-5">
            <div class="card-top">
                <span class="profile-image">
                    <img src="{{ URL('images/avatar-1.jpg') }}" alt="logo">
                </span>

                <div class="profile-details">
                    @php
                        // Get the current user's ID
                        $currentUserId = auth()->id();

                        // Collect unique usernames and department IDs of other users
                        $otherUsers = $monthlyCompliance->messages
                            ->filter(fn($message) => $message->user_id !== $currentUserId) // Exclude current user
                            ->map(fn($message) => [
                                'username' => $message->user->username,
                                'department_name' => $message->user->department->department_name ?? 'Unknown'
                            ])
                            ->unique('username'); // Ensure uniqueness by username
                    @endphp

                    <!-- Display the usernames and department IDs of other users -->
                    @foreach ($otherUsers as $user)
                        <h6 class="fw-semibold m-0" style="margin-bottom: 2px !important;">{{ $user['username'] }}</h6>
                        <span class="m-0" style="font-size: 12px !important; color: #585C5E !important;">
                           {{ $user['department_name'] }}
                        </span>
                    @endforeach

                </div>
            </div>
    
            <span class="line-message"></span>
    
            <div class="card-body">
    
                {{-- <div class="message-secondary">
                    <div class="message-profile">
                        <img src="{{ URL('images/avatar-2.jpg') }}" alt="logo">
                    </div>
    
                    <div class="message-body">
                        <div class="message-name">
                            <p class="m-0">
                                Cymon
                                <small style="font-size: 11px !important; color: #585C5E !important;">
                                    12:00
                                </small>
                            </p>
                        </div>
    
                        <div class="message-content">
                            <div class="message-user">
                                <p class="m-0">Hello</p>
                            </div>
        
                            <div class="message-user">
                                <p class="m-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                            </div>
                        </div>
        
                    </div>
    
    
                </div>
    
                <div class="message-primary">
                    <div class="message-body">
                        <div class="message-name text-end">
                            <small style="font-size: 11px !important; color: #585C5E !important;">
                                9h ago
                            </small>
                        </div>
    
                        <div class="message-content">
                            <div class="message-user">
                                <p class="m-0">Hello</p>
                            </div>
        
                            <div class="message-user">
                                <p class="m-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                            </div>
                        </div>
        
                    </div>
                </div>
    
                <div class="message-secondary">
                    <div class="message-profile">
                        <img src="{{ URL('images/avatar-2.jpg') }}" alt="logo">
                    </div>
    
                    <div class="message-body">
                        <div class="message-name">
                            <p class="m-0">
                                Cymon
                                <small style="font-size: 11px !important; color: #585C5E !important;">
                                    12:00
                                </small>
                            </p>
                        </div>
    
                        <div class="message-content">
                            <div class="message-user">
                                <p class="m-0">Hello</p>
                            </div>
        
                            <div class="message-user">
                                <p class="m-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                            </div>
                        </div>
        
                    </div>
    
    
                </div>
    
                <div class="message-primary">
                    <div class="message-body">
                        <div class="message-name text-end">
                            <small style="font-size: 11px !important; color: #585C5E !important;">
                                9h ago
                            </small>
                        </div>
    
                        <div class="message-content">
                            <div class="message-user">
                                <p class="m-0">Hello</p>
                            </div>
        
                            <div class="message-user">
                                <p class="m-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                            </div>
                        </div>
        
                    </div>
                </div> --}}

                @php
                    $lastUserId = null; // Tracks the user of the last message
                @endphp
               
                
                @foreach ($monthlyCompliance->messages as $message)

                    @php
                        $showTime = $lastUserId !== $message->user->id; // Show time only for the first message of a new user block
                    @endphp
                    
                    @if ($message->user->id === auth()->id()) 
                        <div class="message-primary">
                            <div class="message-body">
                                <div class="message-name text-end">

                                    <!-- Show the time only for the first message of the block -->
                                    @if ($showTime)
                                        <small style="font-size: 11px !important; color: #585C5E !important;">
                                            {{ $message->created_at->diffForHumans() }}
                                        </small>
                                    @endif

                                </div>
            
                                <div class="message-content">
                                    <div class="message-user">
                                        <p class="m-0">{{ $message->message }}</p>
                                    </div>
                                </div>
                
                            </div>
                        </div>

                        <!-- Current user's message (Right-aligned) -->
                        {{-- <div class="message-primary text-end">
                            <div class="message-body">
                                <div class="message-name text-end">
                                    <small style="font-size: 11px !important; color: #585C5E !important;">
                                        {{ $message->created_at->diffForHumans() }}
                                    </small>
                                </div>
            
                                <div class="message-content">
                                    <div class="message-user">
                                        <p class="m-0">{{ $message->message }}</p>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    @else
                        <div class="message-secondary">
                            {{-- <div class="message-profile">
                                <img src="{{ URL('images/avatar-2.jpg') }}" alt="logo">
                            </div> --}}
            
                            <div class="message-body">
                                <div class="message-name">
                                    <p class="m-0">
                                        {{-- <small style="font-size: 11px !important; color: #585C5E !important;">
                                            {{ $message->created_at->format('H:i') }}
                                        </small> --}}

                                        @if ($showTime)
                                            <small style="font-size: 11px !important; color: #585C5E !important;">
                                                {{ $message->created_at->format('h:i A') }}
                                            </small>
                                        @endif

                                    </p>
                                </div>
            
                                <div class="message-content">
                                    <div class="message-user">
                                        <p class="m-0">{{ $message->message }}</p>
                                    </div>
                
                                    {{-- <div class="message-user">
                                        <p class="m-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                                    </div> --}}
                                </div>
                
                            </div>
            
            
                        </div>
                            <!-- Other user's message (Left-aligned) -->
                            {{-- <div class="message-secondary">
                                <div class="message-body">
                                    <div class="message-name">
                                        <p class="m-0">
                                            {{ $message->user->name }}
                                            <small style="font-size: 11px !important; color: #585C5E !important;">
                                                {{ $message->created_at->format('H:i') }}
                                            </small>
                                        </p>
                                    </div>
                
                                    <div class="message-content">
                                        <div class="message-user">
                                            <p class="m-0">{{ $message->message }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                    @endif

                    @php
                        // Update the last user ID
                        $lastUserId = $message->user->id;
                    @endphp

                @endforeach
            </div>
    
            <span class="line-message"></span>
    
            <div class="card-bottom">
                <div class="textarea-container">
                    <form action="" id="chat-form">
                        {{-- <input type="hidden" name="monthly_compliance_id" value="{{ $monthlyCompliance->id }}"> --}}
                        <textarea class="message" id="chat-message" placeholder="Type a Message"></textarea>
                        <button class="send-button" type="submit">
                            <i class="fa-regular fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
    
        </div>
    </div>

    {{-- Top Content --}}
    {{-- <div>
        <div class="card-lg custom-table-card-lg mb-4">

            <div class="card-top">

            </div>

            <div class="card-body">
                <form action="{{ route('compliance-management.update', $monthlyCompliance->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
    
    
                    <div class="row">

                        <div class="col-md-6 mb-md-4 mb-4">
                            <p>Compliance ID</p>
                            <h6 class="fw-semibold" id="vComplianceName">{{ $monthlyCompliance->compliance_id }}</h6>
                        </div>

                        <div class="col-md-6 mb-md-4 mb-4">
                            <p>Department Name</p>
                            <h6 class="fw-semibold" id="vComplianceName">{{ $monthlyCompliance->department_id }}</h6>
                        </div>

                        <div class="col-md-6 mb-md-4 mb-4">
                            <p>Start Date</p>
                            <h6 class="fw-semibold" id="vComplianceName">{{ $monthlyCompliance->computed_start_date }}</h6>
                        </div>

                        <div class="col-md-6 mb-md-4 mb-4">
                            <p>Submit Date</p>
                            <h6 class="fw-semibold" id="vComplianceName">{{ $monthlyCompliance->computed_submit_date }}</h6>
                        </div>

                        <div class="col-md-6 mb-md-4 mb-4">
                            <p>Deadline</p>
                            <h6 class="fw-semibold" id="vComplianceName">{{ $monthlyCompliance->computed_deadline }}</h6>
                        </div>
    
                        <div class="col-md-6">
                            <label for="status" class="mb-2">Status</label>
                            <select name="status" id="status" class="form-select">
                                @foreach($enumValues as $value)
                                    <option value="{{ $value }}" {{ $monthlyCompliance->status === $value ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="col-md-6">
                            <label for="status" class="mb-2">Approval</label>
                            <select name="approve" id="approve" class="form-control">
                                <option value="0" {{ $monthlyCompliance->approve == 0 ? 'selected' : '' }}>Disapprove</option>
                                <option value="1" {{ $monthlyCompliance->approve == 1 ? 'selected' : '' }}>Approve</option>
                            </select>
                        </div>

                    </div>
    
                        <button type="submit" class="btn btn-success">Update Status</button>
                    </div>
                
                </form>

            </div>

            

        </div>
    </div> --}}
    
</x-main>

{{-- Approve Button --}}
<script>
    // Function to handle the approve button click
    function approveCompliance() {
        const complianceId = {{ $monthlyCompliance->id }}; // Pass the compliance ID dynamically
        const approvedStatus = 1; // Assuming 1 represents "approved" status
        const completedStatus = 'completed'; // Set to "completed" status for the select dropdown

        // Send the AJAX request to update the status to approved
        fetch(`/compliance/approve/${complianceId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF token for security
            },
            body: JSON.stringify({ status: approvedStatus }), // Send the approved status
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Change the button text to "Approved"
                const approveButton = document.getElementById('approveButton');
                approveButton.textContent = 'Approved'; // Change text
                approveButton.classList.remove('btn-success'); // Optional: remove success class
                approveButton.classList.add('btn-secondary'); // Optional: change button color to indicate approval
                approveButton.disabled = true; // Optionally disable the button to prevent further clicks

                // Change the status dropdown to "completed"
                const statusDropdown = document.getElementById('status');
                statusDropdown.value = completedStatus; // Change the dropdown value to "completed"

                alert('Compliance has been approved and status is updated to "Completed"!');
            } else {
                alert('There was an error approving the compliance.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error occurred while approving compliance.');
        });
    }
</script>

{{-- Update Status --}}
<script>
    // Function to handle status change and send an AJAX request to update the database
    function updateStatus() {
        const status = document.getElementById('status').value;
        const complianceId = {{ $monthlyCompliance->id }}; // Pass the compliance ID dynamically

        // Send the AJAX request to update the status
        fetch(`/compliance/update-status/${complianceId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF token for security
            },
            body: JSON.stringify({ status: status }), // Send the updated status
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Status updated successfully');
                // Optionally, show a success message or update the UI
            } else {
                console.error('Error updating status');
                // Optionally, show an error message
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

{{-- Chat Form --}}
<script>
    $('#chat-form').on('submit', function (e) {
        e.preventDefault();

        let message = $('#chat-message').val().trim();

        if (!message) {
            alert('Message cannot be empty.');
            return;
        }

        let monthlyComplianceId = {{ $monthlyCompliance->id }};

        $.post(`/compliance-management/${monthlyComplianceId}/messages`, {
            message: message,
            _token: '{{ csrf_token() }}',
        })
        .done(function (response) {
            // fetchMessages(monthlyComplianceId); // Reload chat after sending
            $('#chat-message').val(''); // Clear the input field
        })
        .fail(function () {
            alert('Error sending message.');
        });
    });

    // function fetchMessages(complianceId) {
    //     $.get(`/compliance-management/${complianceId}/messages`, function (messages) {
    //         const chatBox = $('#chat-box');
    //         chatBox.empty();

    //         messages.forEach((msg) => {
    //             const messageHtml = `
    //                 <div>
    //                     <strong>${msg.user.name}:</strong> ${msg.message}
    //                     <small class="text-muted">${new Date(msg.created_at).toLocaleString()}</small>
    //                 </div>
    //                 <hr>
    //             `;
    //             chatBox.append(messageHtml);
    //         });

    //         chatBox.scrollTop(chatBox.prop('scrollHeight')); // Scroll to the latest message
    //     });
    // }
</script>

<style>
    .bottom-content {
        /* background: red !important; */
    }

    .bottom-content .bottom-left {
        /* background: yellow !important; */
    }

    .bottom-content .bottom-left .card-bottom {
        display: flex !important;
        /* align-items: center !important; */
        background: #fff !important;
        border: 1px solid #E7EAEE;
        border-radius: 8px !important;
        padding: 25px !important;
        text-align: center !important;
    }

    .bottom-content .bottom-left .card-bottom .table-details td {
        padding: 10px !important;
    }
</style>

<style>
    .bottom-content {
        display: flex !important;
        gap: 20px !important;
    }

    .col-7 {
      flex: 0 0 calc((7 / 12 * 100%) - 10px); /* Adjust width to include gap */
    }

    .col-5 {
      flex: 0 0 calc((5 / 12 * 100%) - 10px); /* Adjust width to include gap */
    }
</style>

<style>
    .card-bottom {
        width: 100% !important;
        padding: 10px 25px 20px 25px !important;
    }

    .textarea-container {
      position: relative; /* Enable positioning for child elements */
      width: 100%; /* Full width container */
      /* max-width: 500px; Optional max width */
    }

    .message {
        width: 100%; /* Full width of the container */
        padding: 10px 40px 10px 10px; /* Add padding-right to make space for the button */
        border-radius: 5px;
        font-size: 14px;
        height: 50px; /* Fixed height */
        outline: none;
        border: 0 !important;
        border-bottom: 1px solid #E7EAEE !important;

    }

    .message::placeholder {
        color: #BEC8D0 !important;
    }

    .send-button {
        position: absolute;
        top: 50%; /* Center vertically */
        right: 10px; /* Position on the right side */
        transform: translateY(-50%); /* Adjust for perfect vertical centering */
        padding: 12px;
        border-radius: 8px !important;
        cursor: pointer;
        border: 0 !important;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4680FF !important;
        background: #fff !important;
    }

    .send-button:hover {
      background: #E3ECFE !important;
    }

    .send-button i {
      font-size: 14px;
    }
</style>

<style>
    .card-message .line-message {
        display: block !important;
        width: 100%;
        border-top: 1px solid var(--border);
    }

    .card-message .card-top {
        display: flex !important;
        padding: 14px 0px 14px 25px !important;
    }

    .card-message .card-top .profile-image {
        margin-right: 10px !important;
    }

    .card-message .card-top .profile-image img {
        width: 40px !important;
        border-radius: 50% !important;
    }

    .card-message .card-body {
        padding: 0px 25px !important;
        min-height: 400px !important;
        max-height: 400px; /* Restrict the height */
        overflow-y: auto; /* Enable vertical scrolling */

        /* background: red !important; */
    }

    .card-message .card-body .message-secondary {
        display: flex;
    }

    .card-message .card-body .message-secondary .message-profile {
        margin-right: 10px !important;
    }

    .card-message .card-body .message-secondary .message-profile img {
        width: 40px !important;
        border-radius: 50% !important;
    }

    .card-message .card-body .message-secondary .message-name {
        margin-bottom: 5px !important;
    }

    .card-message .card-body .message-secondary .message-body .message-user {
        border: 1px solid #E7EAEE;
        border-radius: 3px;
        padding: 15px !important;
        margin-right: 100px !important;
        margin-bottom: 5px !important;
        width: fit-content; /* Adjust the width based on content */

    }

    .card-message .card-body .message-secondary .message-body .message-user p {
        color: #000000 !important;
    }


    /* Primary Message */
    .card-message .card-body .message-primary {
        margin-left: 100px !important;
    }

    .card-message .card-body .message-primary .message-content {
        display: flex;
        flex-direction: column;
        align-items: flex-end; 
    }

    .card-message .card-body .message-primary .message-name {
        margin-bottom: 5px !important;
    }

    .card-message .card-body .message-primary .message-body .message-user {
        border-radius: 3px;
        padding: 15px !important;
        margin-bottom: 5px !important;
        width: fit-content;
        background: #4680FF !important;

    }

    .card-message .card-body .message-primary .message-body .message-user p {
        color: #FFFFFF !important;
    }
</style>


<script>
    // function uploadImages(event) {
    //     const files = event.target.files;

    //     if (files.length === 0) return;

    //     const formData = new FormData();
    //     formData.append('_token', '{{ csrf_token() }}'); // Include CSRF token
    //     formData.append('monthly_compliance_id', '{{ $monthlyCompliance->id }}'); // Include compliance ID

    //     // Append files to the FormData object
    //     for (let i = 0; i < files.length; i++) {
    //         formData.append('images[]', files[i]);
    //     }

    //     // Send the AJAX request to upload the files
    //     fetch('{{ route('compliance-management.upload-image', $monthlyCompliance->id) }}', {
    //         method: 'POST',
    //         body: formData,
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 // Reload the uploaded images
    //                 const uploadedImagesContainer = document.querySelector('.uploaded-images');
    //                 uploadedImagesContainer.innerHTML = ''; // Clear existing images
    //                 data.filePaths.forEach(filePath => {
    //                     const img = document.createElement('img');
    //                     img.src = `{{ asset('storage') }}/${filePath}`;
    //                     img.alt = 'Uploaded Image';
    //                     img.className = 'thumbnail';
    //                     uploadedImagesContainer.appendChild(img);
    //                 });
    //                 alert('Images uploaded successfully!');
    //             } else {
    //                 alert('Error uploading images.');
    //             }
    //         })
    //         .catch(error => console.error('Error:', error));
    // }

    $('#images').on('change', function(event) {
        let files = event.target.files;
        
        if (files.length === 0) return;

        let formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // CSRF token
        formData.append('monthly_compliance_id', $('#monthly_compliance_id').val()); // Compliance ID

        // Append files to FormData
        $.each(files, function(index, file) {
            formData.append('images[]', file);
        });

        // Upload the files via AJAX
        $.ajax({
            url: '{{ route('compliance-management.upload-image', $monthlyCompliance->id) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    location.reload();
                }
            },
        });
    });

    function deleteImage(complianceId, filePath) {
        if (confirm('Are you sure you want to delete this image?')) {
            $.ajax({
                url: `/compliance-management/${complianceId}/delete-image`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    file_path: filePath
                },
                success: function (response) {
                    // console.log(response.filePaths);
                    location.reload();
                },
            });
        }
    }
</script>

<style>
    .card-image {
    }

    .card-image #uploadForm {
        height: 100% !important;
    }
    
    .upload-container {
        position: relative !important;
        width: 100%;
        height: 100% !important;
        /* max-width: 400px; */
        border: 2px dashed #ccc;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 35px 0px !important;
        gap: 30px !important;
        flex-wrap: wrap !important;
        /* overflow: hidden; */
    }

    .upload-container:hover {
        border-color: #007bff;
    }

    .upload-container .upload-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #007bff;
        font-size: 14px;
        /* opacity: 0; */
        transition: opacity 0.3s ease;
        z-index: 10;
    }

    .upload-container:hover .upload-text {
        opacity: 1; /* Show text on hover */
    }
    
</style>

<style>
    /* Show the overlay on hover */
    .position-relative:hover .overlay {
        display: flex;
    }

    /* Smooth transition for overlay appearance */
    .overlay {
        width: 100%;
        height: 100%;
        border-radius: 20px !important;
        transition: opacity 0.3s ease-in-out;
        opacity: 0;
    }

    .position-relative:hover .overlay {
        opacity: 1;
    }

    .card-body {
        padding: 20px 25px !important;
    }

    .container {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-wrap: wrap !important;
        gap: 30px !important;
        padding: 25px 25px !important;
    }

    .image-card img {
        border-radius: 20px !important;
        display: block !important;
        width: 120px !important; 
        height: 120px !important;
        object-fit: cover !important;
    }

</style>