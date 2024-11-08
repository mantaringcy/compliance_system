// Bootstrap
import 'bootstrap/dist/js/bootstrap.bundle.min';
import 'bootstrap/dist/css/bootstrap.min.css';

// Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css';

// JS Files
import './components/theme';
// import './components/sidebar';
// import './components/navigation-links';
// import './components/media-query';

// Forms
// import './forms/account-update';
// import './forms/compliance-form';
// import './forms/request-form';

document.addEventListener("DOMContentLoaded", function () {
    if (document.body.id === "main-layout") {
        // JS Files
        import('./components/sidebar');
        import('./components/media-query');
        import('./components/navigation-links');

        // Forms
        import('./forms/account-update');
        import('./forms/compliance-form');
        import('./forms/request-form');
    }
});

// Exporting Globally to all the Blades
window.togglePasswordVisibility = togglePasswordVisibility;
window.toggleIconVisibility = toggleIconVisibility;

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

export function toggleButtonLoading(buttonId, isLoading, buttonTextId, loadingText) {
    const button = $(buttonId);
    const spinner = button.find('.spinner-grow');
    const buttonText = button.find(buttonTextId);

    if (isLoading) {
        spinner.show();
        buttonText.text(loadingText);
        button.prop('disabled', true);
    } else {
        spinner.hide();
        buttonText.text(loadingText);
        button.prop('disabled', false);
    }
}

export function showAlert(alertId, response) {
    let message = response.message;

    if (response.success) {
        $(alertId).css('display', 'block');
        $(alertId).text(message);
    } else {
        $(alertId).css('display', 'block');
        $(alertId).text(message);
    }

    setTimeout(function() {
        $(alertId).fadeOut();
    }, 3000);
}

export function togglePasswordVisibility(icon, fieldId) {

    const toggleIcon = document.getElementById(icon);
    const inputFieldType = document.getElementById(fieldId);

    if (inputFieldType.type === "password") {
        inputFieldType.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        inputFieldType.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}

export function toggleIconVisibility(fieldId, iconContainerId) {
    const passwordField = document.getElementById(fieldId);
    const iconContainer = document.getElementById(iconContainerId);

    // Show the icon only if there's input
    if (passwordField.value.length > 0) {
        iconContainer.style.display = "block"; // Show icon
    } else {
        iconContainer.style.display = "none"; // Hide icon
    }
}

export function showToast() {
    // Show the toast with a fade-in effect
    $('#customToast').addClass('show');

    // Automatically hide the toast after 3 seconds
    setTimeout(function() {
        $('#customToast').removeClass('show');
    }, 3000);
}

export function toast(alertId, complianceId = null, complianceName = null, response) {
    let message = '';
    let action = response.action;
    let complianceRef = complianceId 
        ? `no. ${complianceId}` 
        : (complianceName ? `'${complianceName}'` : '');

    if (response.success) {
        showToast();
        $(alertId).css('display', 'block');

        switch(action) {
            case 'create_compliance':
                message = `Compliance ${complianceRef} has been created successfully.`;
                break;
            case 'edit_compliance':
                message = `Compliance ${complianceRef} has been edited successfully.`;
                break;
            case 'delete_compliance':
                message = `Compliance ${complianceRef} has been deleted successfully.`;
                break;
            case 'request_create_compliance':
                message = `Request for compliance ${complianceRef} creation has been submitted.`;
                break;
            case 'request_edit_compliance':
                message = `Request for compliance ${complianceRef} editing has been submitted.`;
                break;
            case 'request_delete_compliance':
                message = `Request for compliance ${complianceRef} deletion has been submitted.`;
                break;
            case 'approve_create_compliance':
                message = `Compliance ${complianceRef} creation has been approved.`;
                break;
            case 'approve_edit_compliance':
                message = `Compliance ${complianceRef} edit has been approved.`;
                break;
            case 'approve_delete_compliance':
                message = `Compliance ${complianceRef} deletion has been approved.`;
                break;
            case 'cancel_create_compliance':
                message = `Compliance ${complianceRef} creation has been canceled.`;
                break;
            case 'cancel_edit_compliance':
                message = `Compliance ${complianceRef} edit has been canceled.`;
                break;
            case 'cancel_delete_compliance':
                message = `Compliance ${complianceRef} deletion has been canceled.`;
                break;
            case 'cancel_request_create_compliance':
                message = `Request for compliance ${complianceRef} creation has been canceled.`;
                break;
            case 'cancel_request_edit_compliance':
                message = `Request for compliance ${complianceRef} editing has been canceled.`;
                break;
            case 'cancel_request_delete_compliance':
                message = `Request for compliance ${complianceRef} deletion has been canceled.`;
                break;
            case 'edit_profile':
                message = `Profile updated successfully.`;
                break;
            default:
                message = 'Action not recognized.';
                break;
        }
        
        $(alertId).text(message);
    
        setTimeout(function() {
            $(alertId).fadeOut();
        }, 3000);
    }
}

