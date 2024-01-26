// jQuery script for handling comments
$(document).ready(function () {

    // Fetch all posts from the API
    $.ajax({
        type: 'GET',
        url: 'api/get_posts.php', // Update the path to your API endpoint
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                // Clear the existing content in #postsContainer
                $('#postsContainer').html('');

                // Iterate through each post and update the UI
                $.each(response.posts, function (index, post) {
                    // Create a unique container ID based on the post ID
                    var commentsContainerId = 'commentsContainer_' + post.id;

                    var postCard =
                        '<div class="row">' +
                        '   <div class="col-lg-12">' +
                        '       <h2 class="section-heading">' + post.title + '</h2>' +
                        '   </div>' +
                        '</div>' +
                        '<div class="row mb-4">' +
                        '   <div class="col-lg-12">' +
                        '       <div class="card">' +
                        '           <img class="card-img-top" src="https://placehold.it/750x300" alt="">' +
                        '           <div class="card-body">' +
                        '               <h4 class="card-name" id="post-username">' + post.username + '</h4>' +
                        '               <p class="card-text" id="post-content">' + post.content + '</p>';
                        // Check if the logged-in user is the author of the post
                        if (post.current_user_id == post.user_id) {
                            // Add the delete button if the user is the author
                            postCard += ' <button class="btn btn-sm btn-danger delete-post mb-4 mr-2" data-post-id="'+post.id+'">Delete</button>' +
                                        '<button class="btn btn-sm btn-warning text-white update-post mb-4" data-post-id="' + post.id + '" data-post-title="' + post.title + '" data-post-content="' + post.content + '">Update</button>';
                        }
                        postCard +=
                                        // New container for comments with unique ID
                        '               <div class="comments-container" id="' + commentsContainerId + '"></div>' +

                        '               <div class="comment-form">' +
                        '                   <h3>Add a Comment</h3>' +
                        '                   <form class="post-comment-form">' +
                        '                       <input type="hidden" class="form-control" name="post_id" value="' + post.id + '" required>' +
                        '                       <input type="hidden" class="form-control" name="user_id" value="' + post.current_user_id + '" required>' +
                        '                       <div class="form-group">' +
                        '                           <label for="commentName">Name:</label>' +
                        '                           <input type="text" class="form-control" name="commentName" value="' + post.current_user_username + '" required readonly>' +
                        '                       </div>' +
                        '                       <div class="form-group">' +
                        '                           <label for="comment">Comment:</label>' +
                        '                           <textarea class="form-control" name="comment" rows="3" required></textarea>' +
                        '                       </div>' +
                        '                       <button type="button" class="btn btn-primary add-comment-btn">Add Comment</button>' +
                        '                   </form>' +
                        '               </div>' +
                        '           </div>' +
                        '       </div>' +
                        '   </div>' +
                        '</div>';

                    $('#postsContainer').append(postCard);

                    // Fetch and display comments for each post
                    fetchComments(post.id, commentsContainerId);
                });

                function fetchComments(postId, containerId) {
                    $.ajax({
                        type: 'GET',
                        url: 'api/get_comments.php?post_id=' + postId,
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                // Clear the existing content in the specific commentsContainer for the post
                                $('#' + containerId).html('');
                
                                // Filter comments based on the current post ID
                                var postComments = response.comments.filter(function(comment) {
                                    return comment.post_id === postId;
                                });
                
                                // Iterate through each comment and update the UI
                                $.each(postComments, function (index, comment) {
                                    var commentCard =
                                        '<div class="comment card mb-2">' +
                                        '   <div class="card-body">' +
                                        '       <p class="card-text"><strong>' + comment.commenter_username + ':</strong> ' + comment.comment + '</p>' +
                                        '       <p class="card-text"><small class="text-muted">' + comment.created_at + '</small></p>';
                                        if (comment.current_user_id == comment.user_id) {
                                            // Add the delete button if the user is the author
                                            commentCard += '<button class="btn btn-sm btn-danger delete-comment-btn mb-4 mr-2" data-comment-id="'+comment.id+'">Delete</button>' +
                                                           '<button class="btn btn-sm btn-warning text-white update-comment mb-4" data-comment-id="' + comment.id + '" data-comment="' + comment.comment + '">Update</button>';
                                        }
                                        commentCard +=
                                        '   </div>' +
                                        '</div>';
                
                                    $('#' + containerId).append(commentCard);
                                });
                            } else {
                                // Handle the case where fetching comments failed
                                alert('Error fetching comments: ' + response.message);
                            }
                        },
                        error: function (error) {
                            console.error('Error fetching comments:', error);
                        }
                    });
                }

                // Attach event listeners to the "Add Comment" buttons
                $('.add-comment-btn').on('click', function () {
                    var commentForm = $(this).closest('.post-comment-form');
                    var formData = new FormData(commentForm[0]);
                    // Send a POST request to the API to add the comment
                    $.ajax({
                        type: 'POST',
                    url: 'api/comment_posts.php', // Update the path to your API endpoint
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                // If the comment is successfully added to the database, update the UI
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Comment added successfully',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                // Reload the page after a delay
                                setTimeout(function () {
                                    location.reload();
                                }, 1500);
                            } else {
                                // Handle the case where adding the comment to the database failed
                                alert('Error adding comment: ' + response.message);
                            }
                        },
                        error: function (error) {
                            console.error('Error adding comment:', error);
                        }
                    });
                });
            } else {
                // Handle the case where fetching posts from the API failed
                alert('Error fetching posts: ' + response.message);
            }
        },
        error: function (error) {
            console.error('Error fetching posts:', error);
        }
    });

    // Event listener for the "Update Post" link
    $('#postsContainer').on('click', '.update-post', function () {

        var postId = $(this).data('post-id');
        var postTitle = $(this).data('post-title');
        var postContent = $(this).data('post-content');
    
        // Show the modal
        $('#updatePostModal').modal('show');
    
        $('#updatedTitle').val(postTitle);
        $('#updatedContent').val(postContent);
    
        $('#submitUpdatePostButton').on('click', function () {
            // Get the current values from the input fields
            var updatedTitle = $('#updatedTitle').val();
            var updatedContent = $('#updatedContent').val();
    
            // Send a POST request to the API to update the post
            $.ajax({
                type: 'POST',
                url: 'api/update_posts.php', // Update the path to your API endpoint
                data: {
                    id: postId,
                    title: updatedTitle, // Use the updated title
                    content: updatedContent // Use the updated content
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // If the post is successfully updated in the database, show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Post updated successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        location.reload();
                    } else {
                        // Handle the case where updating the post in the database failed
                        Swal.fire('Error', 'Failed to update the post: ' + response.message, 'error');
                    }
                },
                error: function (error) {
                    console.error('Error updating post:', error);
                    Swal.fire('Error', 'Failed to update the post. Please try again.', 'error');
                }
            });
        });
    });

    // Update Comment
    $('#postsContainer').on('click', '.update-comment', function () {

        var commentId = $(this).data('comment-id');
        var commentContent = $(this).data('comment');

        // Show the modal
        $('#updateCommentModal').modal('show');
        
        $('#updatedComment').val(commentContent);

        $('#submitUpdateCommentButton').on('click', function () {
            // Get the current values from the input fields
            var updatedComment = $('#updatedComment').val();
    
            // Send a POST request to the API to update the comment
            $.ajax({
                type: 'POST',
                url: 'api/update_comments.php', // Update the path to your API endpoint
                data: {
                    comment_id: commentId,
                    updated_comment: updatedComment // Use the updated content
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // If the comment is successfully updated in the database, show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Comment updated successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        location.reload();
                    } else {
                        // Handle the case where updating the comment in the database failed
                        Swal.fire('Error', 'Failed to update the comment: ' + response.message, 'error');
                    }
                },
                error: function (error) {
                    console.error('Error updating comment:', error);
                    Swal.fire('Error', 'Failed to update the comment. Please try again.', 'error');
                }
            });
        });
    });

    // Event listener for the "Delete Post" button
    $('#postsContainer').on('click', '.delete-post', function () {
        // Get the post ID from the data attribute
        var postId = $(this).data('post-id');

        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, send request to delete API
                $.ajax({
                    type: 'POST',
                    url: 'api/delete_posts.php',
                    data: {
                        id: postId
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // If deletion is successful, remove the post from the UI
                            $(this).closest('.row').remove();
                            Swal.fire('Deleted!', 'Your post has been deleted.', 'success');
                            location.reload();
                        } else {
                            // Handle the case where deleting the post from the database failed
                            Swal.fire('Error', 'Failed to delete the post: ' + response.message, 'error');
                        }
                    },
                    error: function (error) {
                        console.error('Error deleting post:', error);
                        Swal.fire('Error', 'Failed to delete the post. Please try again.', 'error');
                    }
                });
            }
        });
    });

    // Attach event listener to the "Delete Comment" button
    $('#postsContainer').on('click', '.delete-comment-btn', function () {
        // Get the comment ID from the data attribute
        var commentId = $(this).data('comment-id');

        // Prompt the user for confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send a POST request to the API to delete the comment
                $.ajax({
                    type: 'POST',
                    url: 'api/delete_comments.php', // Update the path to your API endpoint
                    data: {
                        comment_id: commentId
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // If deletion is successful, remove the comment from the UI
                            $(this).closest('.comment').remove();
                            Swal.fire('Deleted!', 'Your comment has been deleted.', 'success');
                            location.reload();
                        } else {
                            // Handle the case where deleting the comment from the database failed
                            Swal.fire('Error', 'Failed to delete the comment: ' + response.message, 'error');
                        }
                    },
                    error: function (error) {
                        console.error('Error deleting comment:', error);
                        Swal.fire('Error', 'Failed to delete the comment. Please try again.', 'error');
                    }
                });
            }
        });
    });

    //Create post
    // Event listener for the "Post" button
    $('#btnCreateBlog').on('click', function () {
        // Get the values from the input fields
        var title = $('#title').val();
        var content = $('#content').val();

        // Send a POST request to the API to create a blog post
        $.ajax({
            type: 'POST',
            url: 'api/create_posts.php', // Update the path to your API endpoint
            data: {
                title: title,
                content: content
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // If the post is created successfully, show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Post created successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // Optionally, you can redirect or perform other actions after successful creation
                    location.reload();
                } else {
                    // Handle the case where creating the post failed
                    Swal.fire('Error', 'Failed to create the post: ' + response.message, 'error');
                }
            },
            error: function (error) {
                console.error('Error creating post:', error);
                Swal.fire('Error', 'Failed to create the post. Please try again.', 'error');
            }
        });
    });
});
