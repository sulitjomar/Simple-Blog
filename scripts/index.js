document.addEventListener('DOMContentLoaded', function() {
    // For dynamic footer
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();

    $('#footer').text('\u00A9 ' + currentYear);
});

// Auth
function login() {
    // Get form data
    var formData = $('#loginForm').serialize();

    // Send AJAX request to PHP API
    $.ajax({
        type: 'POST',
        url: 'api/login.php',
        data: formData,
        success: function(response) {
            // Use SweetAlert to display the message
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(function () {
                    window.location.href = 'http://localhost/blog/blog.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message
                });
            }
        },
        error: function(error) {
            console.error('Error:', error);
        }
    });
}

// Logout
function logout() {
    // Display a confirmation dialog
    Swal.fire({
        title: 'Logout Confirmation',
        text: 'Are you sure you want to logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with logout
            $.ajax({
                type: 'GET',
                url: 'api/logout.php?action=logout',
                success: function(response) {
                    // Redirect to the login page
                    window.location.href = 'index.php';
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }
    });
}
