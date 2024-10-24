// Bootstrap
import 'bootstrap/dist/js/bootstrap.bundle.min';
import 'bootstrap/dist/css/bootstrap.min.css';

// Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css';

// Theme Switch Configuration
let darkmode = localStorage.getItem('darkmode');
const themeSwitch =  document.getElementById('theme-switch');

const enableDarkmode = () => {
    document.body.classList.add('dark');
    localStorage.setItem('darkmode', 'active');
}

const disableDarkmode = () => {
    document.body.classList.remove('dark');
    localStorage.setItem('darkmode', null);
}

if (darkmode === "active") enableDarkmode();

themeSwitch.addEventListener("click", () => {
    darkmode = localStorage.getItem('darkmode');
    darkmode !== "active" ? enableDarkmode() : disableDarkmode();
})

// Sidebar Toggle
const toggler = document.querySelector(".main-btn");
toggler.addEventListener("click", function(){
    document.querySelector("#sidebar").classList.toggle("collapsed");
    document.querySelector("#main").classList.toggle("collapsed");
});

// Active Links
const activePage = window.location.pathname;

const navLinks = document.querySelectorAll('.sidebar .sidebar-nav a').forEach(link => {
    if (link.href.includes(`${activePage}`)) {
        link.classList.add('sidebar-item-active');
    }
})

const subLinksCollapse = document.getElementById('profile-list');
const subLinks = document.querySelectorAll('.sidebar .profile a').forEach(link => {
    if (link.href.includes(`${activePage}`)) {
        link.classList.add('profile-list-active');
        subLinksCollapse.classList.remove('collapse');
    }
})


// Media Query
function handleScreenChange(x) {
    if (mediaQuery.matches) { // If media query matches
        document.querySelector("#sidebar").classList.toggle("collapsed");
        document.querySelector("#main").classList.toggle("collapsed");
    } else {
        document.querySelector("#sidebar").classList.remove("collapsed");
        document.querySelector("#main").classList.remove("collapsed");
    }
}
  
// Create a MediaQueryList object
const mediaQuery = window.matchMedia("(max-width: 1000px)")
  
// Call listener function at run time
    handleScreenChange(mediaQuery);
  
// Attach listener function on state changes
mediaQuery.addEventListener("change", function() {
    handleScreenChange(mediaQuery);
});


function formatDate(dateString) {
    // Create an array of month names
    const monthNames = ["January", "February", "March", "April", "May", "June", 
                        "July", "August", "September", "October", "November", "December"];
    
    // Create a new Date object from the incoming string
    const date = new Date(dateString);
    
    // Extract the month, day, and year
    const month = monthNames[date.getMonth()]; // Get the month name
    const day = date.getDate();
    const year = date.getFullYear(); // Use the full year
    
    // Format the date as Month-D-Y
    return `${month} ${day}, ${year}`;
}

// New Compliance Form
// document.addEventListener('DOMContentLoaded', function () {
//     const departmentModal = document.getElementById('newComplianceModal');

//     departmentModal.addEventListener('show.bs.modal', function (event) {
//         const button = event.relatedTarget;

//         // Get departments from data attribute (JSON)
//         const departments = JSON.parse(button.getAttribute('data-departments'));

//         // Get the select input
//         const departmentSelect = departmentModal.querySelector('#nDepartmentId');

//         // Clear existing options
//         // departmentSelect.innerHTML = '';

//         // Append options to the select element
//         departments.forEach(function (department) {
//             var option = document.createElement('option');
//             option.value = department.id; // Set the value to the department ID
//             option.textContent = department.department_name; // Display the department name
//             departmentSelect.appendChild(option);
//         });
//     });
// });

// View Compliance Form
// document.addEventListener('DOMContentLoaded', function() {
//     const viewLinks = document.querySelectorAll('.view-compliance');

//     viewLinks.forEach(link => {
//         link.addEventListener('click', function() {
//             const complianceId = this.getAttribute('data-compliance-id');
//             const complianceName = this.getAttribute('data-compliance-name');
//             const complianceDepartment = this.getAttribute('data-department');
//             const complianceReferenceDate = this.getAttribute('data-compliance-reference-date');
//             const complianceFrequency = this.getAttribute('data-compliance-frequency');
//             const complianceStartOn = this.getAttribute('data-compliance-start-on');
//             const complianceSubmitOn = this.getAttribute('data-compliance-submit-on');

//             // Set the values in the modal
//             const vComplianceName = document.getElementById('vComplianceName');
//             const vDepartmentName = document.getElementById('vDepartmentName');
//             const vReferenceDate = document.getElementById('vReferenceDate');
//             const vFrequency = document.getElementById('vFrequency');
//             const vStartOn = document.getElementById('vStartOn');
//             const vSubmitOn = document.getElementById('vSubmitOn');

//             vComplianceName.textContent = complianceName;
//             vDepartmentName.textContent = complianceDepartment;
//             vDepartmentName.textContent = departmentMapping[complianceDepartment - 1].department_name;
//             vReferenceDate.textContent = formatDate(complianceReferenceDate);
//             vFrequency.textContent = frequencyMapping[complianceFrequency] || 'Unknown';
//             vStartOn.textContent = startOnMapping[complianceStartOn] || 'Unknown';
//             vSubmitOn.textContent = submitOnMapping[complianceSubmitOn] || 'Unknown';

          
//         });
//     });
// });

// Edit Compliance Form
// document.addEventListener('DOMContentLoaded', function() {
//     const editLinks = document.querySelectorAll('.edit-compliance');

//     editLinks.forEach(link => {
//         link.addEventListener('click', function() {
//             const complianceId = this.getAttribute('data-compliance-id');
//             const complianceName = this.getAttribute('data-compliance-name');
//             const complianceDepartment = this.getAttribute('data-department');
//             const complianceReferenceDate = this.getAttribute('data-compliance-reference-date');
//             const complianceFrequency = this.getAttribute('data-compliance-frequency');
//             const complianceStartOn = this.getAttribute('data-compliance-start-on');
//             const complianceSubmitOn = this.getAttribute('data-compliance-submit-on');

//             const form = document.getElementById('updateComplianceForm'); // Your form ID
//             form.action = `/compliance/${complianceId}`; // Or use route helper if needed

//             // Set the values in the modal
//             document.getElementById('complianceId').value = complianceId;
//             document.getElementById('complianceName').value = complianceName;
//             document.getElementById('departmentSelect').value = complianceDepartment;
//             document.getElementById('referenceDate').value = complianceReferenceDate;
//             document.getElementById('frequency').value = complianceFrequency;
//             document.getElementById('startOn').value = complianceStartOn;
//             document.getElementById('submitOn').value = complianceSubmitOn;

//             // console.log(document.getElementById('departmentSelect').value);
//         });
//     });
// });


// Delete Compliance Form
// document.addEventListener('DOMContentLoaded', function() {
//     const deleteLinks = document.querySelectorAll('.delete-compliance');

//     deleteLinks.forEach(link => {
//         link.addEventListener('click', function(e) {
//             const complianceId = this.getAttribute('data-compliance-id');
//             const complianceName = this.getAttribute('data-compliance-name');

//             const dComplianceName = document.getElementById('dComplianceName');

//             dComplianceName.textContent = complianceName;

//             const form = document.getElementById('deleteComplianceForm'); // Your form ID
//             form.action = `/compliance/${complianceId}`; // Or use route helper if needed
//         });
//     });
// });

let originalData = {};

$(document).on('click', '.edit-compliance', function () {
    const id = $(this).attr('data-compliance-id');
    const complianceName = $(this).attr('data-compliance-name');
    const departmentId = $(this).attr('data-department-id');
    const referenceDate = $(this).attr('data-compliance-reference-date');
    const frequency = $(this).attr('data-compliance-frequency');
    const startWorkingOn = $(this).attr('data-compliance-start-working-on');
    const submitOn = $(this).attr('data-compliance-submit-on')
    
    // Store original data for comparison
    originalData = { complianceName, departmentId, referenceDate, frequency, startWorkingOn, submitOn };

    // console.log("Original Data", originalData);
    
    $('#editComplianceModal #complianceName').val(complianceName);
    $('#editComplianceModal #departmentSelect').val(departmentId);
    $('#editComplianceModal #referenceDate').val(referenceDate);
    $('#editComplianceModal #frequency').val(frequency);
    $('#editComplianceModal #startWorkingOn').val(startWorkingOn);
    $('#editComplianceModal #submitOn').val(submitOn);
});

// Edit Compliance Form Submit
$('#editComplianceForm').on('submit', function(event) {
    event.preventDefault();  // Prevent the default form submission
    
    // Get the values from the form
    const complianceId = $('#complianceId').val();
    const complianceName = $('#complianceName').val();
    const complianceDepartment = $('#departmentSelect').val();
    const complianceReferenceDate = $('#referenceDate').val();
    const complianceFrequency = $('#frequency').val();
    const complianceStartWorkingOn = $('#startWorkingOn').val();
    const complianceSubmitOn = $('#submitOn').val();

    const updatedData = {
        complianceName: $('#editComplianceModal #complianceName').val(),
        departmentId: $('#editComplianceModal #departmentSelect').val(),
        referenceDate: $('#editComplianceModal #referenceDate').val(),
        frequency: $('#editComplianceModal #frequency').val(),
        startWorkingOn: $('#editComplianceModal #startWorkingOn').val(),
        submitOn: $('#editComplianceModal #submitOn').val(),
    };


    // Compare original data with updated data
    const hasChanges = Object.keys(updatedData).some(key => updatedData[key] !== originalData[key]);

    if (!hasChanges) {
        alert('No changes detected. Please modify the compliance data before submitting.');
        return; // Prevent submission
    }

    $('#editComplianceForm').attr('action', '/compliances/' + complianceId);

    // Perform your AJAX update here
    $.ajax({
        url: $(this).attr('action'),  // Use the form action URL
        type: 'POST',  // Use POST with _method field for PUT
        data: $(this).serialize(),  // Serialize form data
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success, e.g., update the DataTable or show a success message
            $('#editComplianceModal').modal('hide');
            $('#complianceListTable').DataTable().ajax.reload();  // Reload DataTable data
        },
        error: function(xhr) {
            $('.invalid-feedback').text(''); // Display the first message
            
            if (xhr.responseJSON.errors) {
                // Loop through each error
                $.each(xhr.responseJSON.errors, function(fieldName, messages) {
                    // Display the first error message for each field
                    $('.' + fieldName).text(messages[0]); // Display the first message
                });
            }
        }
    });
});

// Add Compliance Error
$('#newComplianceForm').on('submit', function(event) {
    event.preventDefault();  // Prevent the default form submission

    console.log('object');

    $('#newComplianceForm').attr('action', '/compliances/');

    // Perform your AJAX update here
    $.ajax({
        url: $(this).attr('action'),  // Use the form action URL
        type: 'POST',  // Use POST with _method field for PUT
        data: $(this).serialize(),  // Serialize form data
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success, e.g., update the DataTable or show a success message
            $('#newComplianceModal').modal('hide');
            $('#complianceListTable').DataTable().ajax.reload();  // Reload DataTable data
            location.reload()
        },
        error: function(xhr) {
            $('.invalid-feedback').text(''); // Display the first message
            
            if (xhr.responseJSON.errors) {
                // Loop through each error
                $.each(xhr.responseJSON.errors, function(fieldName, messages) {
                    // Display the first error message for each field
                    $('.' + fieldName).text(messages[0]); // Display the first message
                });
            }
        }
    });
});


// REQUEST MODAL


// $(document).ready(function() {
//     // Handle Cancel button click
//     $('#cancelRequest').on('click', function(e) {
//         e.preventDefault(); // Prevent form submission
        
//         // Perform the action you want for cancel
//         if(confirm("Are you sure you want to cancel this request?")) {
//             // For example, redirect back or reset the form
//             window.location.href = '/dashboard';  // Redirect to another page
//         }
//     });

//     // Handle Approve button click
//     $('#approveRequest').on('click', function(e) {
//         e.preventDefault(); // Prevent form submission

//         // Perform the action you want for approve
//         if(confirm("Are you sure you want to approve this request?")) {
//             // Submit the form via AJAX or trigger form submission
//             $('#complianceForm').submit();  // This will submit the form
//         }
//     });
// });

// ADD REQUEST MODAL
// Approve Button
$('#addApproveButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to approve this compliance?")) {
        $.ajax({
            url:'/admin/compliance/approve/' + requestId,  
            type: 'POST',  
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// Cancel Button
$('#addCancelButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to cancel this request?")) {
        $.ajax({
            url:'/admin/compliance/cancel/' + requestId, 
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// DELETE REQUEST MODAL
// Approve Button
$('#deleteApproveButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to approve the deletion of this compliance?")) {
        $.ajax({
            url:'/admin/compliance/approve/' + requestId,  
            type: 'POST',  
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// Cancel Button
$('#deleteCancelButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to cancel this request?")) {
        $.ajax({
            url:'/admin/compliance/cancel/' + requestId, 
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// EDIT REQUEST MODAL
// Approve Button
$('#editApproveButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to approve the editing of this compliance?")) {
        $.ajax({
            url:'/admin/compliance/approve/' + requestId,  
            type: 'POST',  
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// Cancel Button
$('#editCancelButton').on('click', function(e) {
    e.preventDefault();

    if(confirm("Are you sure you want to cancel this request?")) {
        $.ajax({
            url:'/admin/compliance/cancel/' + requestId, 
            type: 'POST',
            data: $(this).serialize(),  // Serialize form data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addRequestComplianceModal').modal('hide');
                location.reload()
            }
        });

    }
});

// $('#aRequestComplianceForm').on('submit', function(event) {
//     event.preventDefault();  // Prevent the default form submission
    
//     // Get the values from the form
//     const complianceId = $('#complianceId').val();
//     const complianceName = $('#aRequestComplianceName').text();
//     const departmentName = addRequest[0];
//     const referenceDate = addRequest[1];
//     const frequency = addRequest[2];
//     const startWorkingOn = addRequest[3];
//     const submitOn = addRequest[4];

//     // const approveBtn = $('#approveRequest');
//     // const cancelBtn = $('#cancelRequest');

//     // if ($('#approveRequest').attr('name') == "approveRequest") {            
//     //     alert("First Button is pressed - Form will submit")
//     //     $("#myform").submit();
//     // }

//     // if ($('#cancelRequest').attr('name') == "cancelRequest") {
//     //     alert("Second button is pressed - Form did not submit")
//     // }

//     // $('#aRequestComplianceForm').attr('action', '/admin/compliance/approve/' + addRequest[5]);

//     // Perform your AJAX update here
//     $.ajax({
//         url: $(this).attr('action'),  // Use the form action URL
//         type: 'POST',  // Use POST with _method field for PUT
//         // data: $(this).serialize(),  // Serialize form data
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(response) {
//             $('#addRequestComplianceModal').modal('hide');
//             location.reload()
//         }
//     });
// });