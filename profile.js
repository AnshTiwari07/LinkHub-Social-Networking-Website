$(document).ready(function() {
    // Modal functionality
    const editProfileModal = $('#edit-profile-modal');
    const addPostModal = $('#add-post-modal');
    
    // Edit profile modal
    $('#edit-profile-btn').click(function() {
        editProfileModal.show();
    });
    
    $('.close').click(function() {
        $(this).closest('.modal').hide();
    });
    
    // Add post modal
    $('#add-post-btn').click(function() {
        addPostModal.show();
    });
    
    // Close modal when clicking outside
    $(window).click(function(event) {
        if (event.target.classList.contains('modal')) {
            $('.modal').hide();
        }
    });
    
    // Edit profile form validation
    $('#edit-profile-form').on('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        let errors = {};
        
        // Clear previous errors
        $('.error').text('');
        
        // Full name validation
        const fullName = $('#edit_full_name').val().trim();
        if (fullName.length < 2) {
            errors.edit_full_name = 'Full name must be at least 2 characters long';
            isValid = false;
        }
        
        // Age validation
        const age = parseInt($('#edit_age').val());
        if (isNaN(age) || age < 1 || age > 150) {
            errors.edit_age = 'Please enter a valid age between 1 and 150';
            isValid = false;
        }
        
        // Profile picture validation
        const profilePicture = $('#edit_profile_picture')[0].files[0];
        if (profilePicture) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(profilePicture.type)) {
                errors.edit_profile_picture = 'Please upload a JPEG, PNG, or GIF image';
                isValid = false;
            }
            
            if (profilePicture.size > maxSize) {
                errors.edit_profile_picture = 'Image size must be less than 5MB';
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
    
    // Add post form
    $('#add-post-form').on('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        let errors = {};
        
        // Clear previous errors
        $('.error').text('');
        
        // Description validation
        const description = $('#post_description').val().trim();
        if (description.length < 1) {
            errors.post_description = 'Description is required';
            isValid = false;
        }
        
        // Image validation
        const image = $('#post_image')[0].files[0];
        if (image) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(image.type)) {
                errors.post_image = 'Please upload a JPEG, PNG, or GIF image';
                isValid = false;
            }
            
            if (image.size > maxSize) {
                errors.post_image = 'Image size must be less than 5MB';
                isValid = false;
            }
        }
        
        // Display errors
        Object.keys(errors).forEach(function(field) {
            $('#' + field + '_error').text(errors[field]);
        });
        
        if (isValid) {
            addPost();
        }
    });
    
    // Add post function
    function addPost() {
        const formData = new FormData($('#add-post-form')[0]);
        
        $.ajax({
            url: 'api/add_post.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#add-post-form button').prop('disabled', true).html('<span class="loading"></span> Adding...');
            },
            success: function(response) {
                if (response.success) {
                    // Add new post to the page
                    addPostToPage(response.post);
                    
                    // Reset form and close modal
                    $('#add-post-form')[0].reset();
                    addPostModal.hide();
                    
                    // Show success message
                    showAlert('Post added successfully!', 'success');
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function() {
                showAlert('An error occurred while adding the post.', 'error');
            },
            complete: function() {
                $('#add-post-form button').prop('disabled', false).text('Add Post');
            }
        });
    }
    
    // Add post to page
    function addPostToPage(post) {
        const postHtml = `
            <div class="post-card" data-post-id="${post.id}">
                <div class="post-header">
                    <div class="post-user-info">
                        <img src="${$('#profile-img').attr('src')}" alt="Profile Picture" class="post-user-avatar">
                        <div>
                            <h4>${$('#profile-name').text()}</h4>
                            <small>${new Date().toLocaleString()}</small>
                        </div>
                    </div>
                    <button class="btn btn-danger btn-sm delete-post" data-post-id="${post.id}">Delete</button>
                </div>
                
                <div class="post-content">
                    <p>${escapeHtml(post.description)}</p>
                    ${post.image ? `<img src="${post.image}" alt="Post Image" class="post-image">` : ''}
                </div>
                
                <div class="post-actions">
                    <button class="btn btn-like" data-post-id="${post.id}" data-reaction="like">
                        👍 Like (<span class="like-count">${post.likes}</span>)
                    </button>
                    <button class="btn btn-dislike" data-post-id="${post.id}" data-reaction="dislike">
                        👎 Dislike (<span class="dislike-count">${post.dislikes}</span>)
                    </button>
                </div>
            </div>
        `;
        
        $('#posts-container').prepend(postHtml);
    }
    
    // Delete post
    $(document).on('click', '.delete-post', function() {
        const postId = $(this).data('post-id');
        const postCard = $(this).closest('.post-card');
        
        if (confirm('Are you sure you want to delete this post?')) {
            $.ajax({
                url: 'api/delete_post.php',
                type: 'POST',
                data: { post_id: postId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        postCard.fadeOut(300, function() {
                            $(this).remove();
                        });
                        showAlert('Post deleted successfully!', 'success');
                    } else {
                        showAlert(response.message, 'error');
                    }
                },
                error: function() {
                    showAlert('An error occurred while deleting the post.', 'error');
                }
            });
        }
    });
    
    // Like/Dislike functionality
    $(document).on('click', '.btn-like, .btn-dislike', function() {
        const postId = $(this).data('post-id');
        const reactionType = $(this).data('reaction');
        const button = $(this);
        const likeCount = button.find('.like-count');
        const dislikeCount = button.siblings('.btn-dislike').find('.dislike-count');
        
        $.ajax({
            url: 'api/react_post.php',
            type: 'POST',
            data: { 
                post_id: postId, 
                reaction_type: reactionType 
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update counts
                    likeCount.text(response.likes);
                    dislikeCount.text(response.dislikes);
                    
                    // Update button states
                    if (response.action === 'added') {
                        button.addClass('active');
                        button.siblings().removeClass('active');
                    } else if (response.action === 'removed') {
                        button.removeClass('active');
                    }
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function() {
                showAlert('An error occurred while updating the reaction.', 'error');
            }
        });
    });
    
    // Show alert function
    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        const alert = `<div class="alert ${alertClass}">${message}</div>`;
        
        $('.container').prepend(alert);
        
        setTimeout(function() {
            $('.alert').fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // Escape HTML function
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    // Real-time validation for edit profile form
    $('#edit_full_name').on('blur', function() {
        const value = $(this).val().trim();
        if (value.length < 2) {
            $('#edit_full_name_error').text('Full name must be at least 2 characters long');
        } else {
            $('#edit_full_name_error').text('');
        }
    });
    
    $('#edit_age').on('blur', function() {
        const value = parseInt($(this).val());
        if (isNaN(value) || value < 1 || value > 150) {
            $('#edit_age_error').text('Please enter a valid age between 1 and 150');
        } else {
            $('#edit_age_error').text('');
        }
    });
    
    $('#edit_profile_picture').on('change', function() {
        const file = this.files[0];
        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(file.type)) {
                $('#edit_profile_picture_error').text('Please upload a JPEG, PNG, or GIF image');
            } else if (file.size > maxSize) {
                $('#edit_profile_picture_error').text('Image size must be less than 5MB');
            } else {
                $('#edit_profile_picture_error').text('');
            }
        }
    });
    
    // Real-time validation for add post form
    $('#post_description').on('blur', function() {
        const value = $(this).val().trim();
        if (value.length < 1) {
            $('#post_description_error').text('Description is required');
        } else {
            $('#post_description_error').text('');
        }
    });
    
    $('#post_image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(file.type)) {
                $('#post_image_error').text('Please upload a JPEG, PNG, or GIF image');
            } else if (file.size > maxSize) {
                $('#post_image_error').text('Image size must be less than 5MB');
            } else {
                $('#post_image_error').text('');
            }
        }
    });
});

