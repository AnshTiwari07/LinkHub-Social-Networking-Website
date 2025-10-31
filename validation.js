$(document).ready(function() {
    // Signup form validation
    $('#signupForm').on('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        let errors = {};
        
        // Clear previous errors
        $('.error').text('');
        
        // Full name validation
        const fullName = $('#full_name').val().trim();
        if (fullName.length < 2) {
            errors.full_name = 'Full name must be at least 2 characters long';
            isValid = false;
        }
        
        // Email validation
        const email = $('#email').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.email = 'Please enter a valid email address';
            isValid = false;
        }
        
        // Password validation
        const password = $('#password').val();
        if (password.length < 6) {
            errors.password = 'Password must be at least 6 characters long';
            isValid = false;
        }
        
        // Age validation
        const age = parseInt($('#age').val());
        if (isNaN(age) || age < 1 || age > 150) {
            errors.age = 'Please enter a valid age between 1 and 150';
            isValid = false;
        }
        
        // Profile picture validation
        const profilePicture = $('#profile_picture')[0].files[0];
        if (profilePicture) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(profilePicture.type)) {
                errors.profile_picture = 'Please upload a JPEG, PNG, or GIF image';
                isValid = false;
            }
            
            if (profilePicture.size > maxSize) {
                errors.profile_picture = 'Image size must be less than 5MB';
                isValid = false;
            }
        }
        
        // Display errors
        Object.keys(errors).forEach(function(field) {
            $('#' + field + '_error').text(errors[field]);
        });
        
        if (isValid) {
            this.submit();
        }
    });
    
    // Login form validation
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        let errors = {};
        
        // Clear previous errors
        $('.error').text('');
        
        // Email validation
        const email = $('#email').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.email = 'Please enter a valid email address';
            isValid = false;
        }
        
        // Password validation
        const password = $('#password').val();
        if (password.length < 1) {
            errors.password = 'Password is required';
            isValid = false;
        }
        
        // Display errors
        Object.keys(errors).forEach(function(field) {
            $('#' + field + '_error').text(errors[field]);
        });
        
        if (isValid) {
            this.submit();
        }
    });
    
    // Real-time validation for signup form
    $('#full_name').on('blur', function() {
        const value = $(this).val().trim();
        if (value.length < 2) {
            $('#full_name_error').text('Full name must be at least 2 characters long');
        } else {
            $('#full_name_error').text('');
        }
    });
    
    $('#email').on('blur', function() {
        const value = $(this).val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            $('#email_error').text('Please enter a valid email address');
        } else {
            $('#email_error').text('');
        }
    });
    
    $('#password').on('blur', function() {
        const value = $(this).val();
        if (value.length < 6) {
            $('#password_error').text('Password must be at least 6 characters long');
        } else {
            $('#password_error').text('');
        }
    });
    
    $('#age').on('blur', function() {
        const value = parseInt($(this).val());
        if (isNaN(value) || value < 1 || value > 150) {
            $('#age_error').text('Please enter a valid age between 1 and 150');
        } else {
            $('#age_error').text('');
        }
    });
    
    $('#profile_picture').on('change', function() {
        const file = this.files[0];
        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(file.type)) {
                $('#profile_picture_error').text('Please upload a JPEG, PNG, or GIF image');
            } else if (file.size > maxSize) {
                $('#profile_picture_error').text('Image size must be less than 5MB');
            } else {
                $('#profile_picture_error').text('');
            }
        }
    });
    
    // Real-time validation for login form
    $('#loginForm #email').on('blur', function() {
        const value = $(this).val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            $('#email_error').text('Please enter a valid email address');
        } else {
            $('#email_error').text('');
        }
    });
    
    $('#loginForm #password').on('blur', function() {
        const value = $(this).val();
        if (value.length < 1) {
            $('#password_error').text('Password is required');
        } else {
            $('#password_error').text('');
        }
    });
});
