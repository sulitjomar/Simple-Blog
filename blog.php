<?php
// Start the session
session_start();

// Check if the 'username' session variable is not set (indicating not logged in)
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header('Location: index.php');
    exit(); // Ensure that no further code is executed after the redirection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Blog</title>
    <!-- Bootstrap CSS Link (you can replace it with a CDN link) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles/blog/style.css">
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand" href="#">The Blog</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#home">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#about">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#blog">Blog</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#contact">Contact</a>
            </li>
            <li class="nav-item">
                <button onclick="logout()" class="btn btn-primary">Logout</button>
            </li>
        </ul>
    </div>
</nav>

<!-- Header -->
<header id="home">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="mt-5">Your Blog Title</h1>
                <p class="lead">A simple and elegant one-page blog design using Bootstrap 4</p>
            </div>
        </div>
    </div>
</header>

<!-- About Section -->
<section id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">About</h2>
                <p class="text-muted">Briefly describe your blog and what it's all about.</p>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section id="blog" class="bg-light">
    <div class="container">
        <!-- Bootstrap Modal for Updating Post -->
        <div class="modal fade" id="updatePostModal" tabindex="-1" role="dialog" aria-labelledby="updatePostLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updatePostLabel">Update Post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add form fields for updating the post -->
                        <div class="form-group">
                            <label for="updatedTitle">Title:</label>
                            <input type="text" class="form-control" id="updatedTitle" rows="3">
                        </div>
                        <div class="form-group">
                            <label for="updatedContent">Content:</label>
                            <textarea class="form-control" id="updatedContent" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary update-content-submit" id="submitUpdatePostButton">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap Modal for Updating Comment -->
        <div class="modal fade" id="updateCommentModal" tabindex="-1" role="dialog" aria-labelledby="updateCommentLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateCommentLabel">Update Comment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Add form fields for updating the post -->
                        <div class="form-group">
                            <label for="updatedComment">Comment:</label>
                            <textarea class="form-control" id="updatedComment" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary update-content-submit" id="submitUpdateCommentButton">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Create a post form -->
        <div>
            <h3>Create a Blog</h3>
            <!-- Add form fields for updating the post -->
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" rows="3">
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea class="form-control" id="content" rows="3"></textarea>
            </div>
            <button class="btn btn-sm btn-success mb-4" id="btnCreateBlog">Post</button>
        </div>
        <!-- Example Blog Post -->
        <div id="postsContainer">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="section-heading">Blog</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <img class="card-img-top" src="https://placehold.it/750x300" alt="">
                        <div class="card-body">
                            <h4 class="card-title">Blog Post Title</h4>
                            <p class="card-text">Write a brief description of your blog post here.</p>
                        </div>
                    </div>
                </div>
                <!-- New container for comments -->
                <div class="comments-container" id="commentsContainer"></div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">Contact</h2>
                <p class="text-muted">Include a contact form or your contact information here.</p>
            </div>
        </div>
    </div>
</section>
 
<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p>&copy; Your Blog 2024. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS and dependencies (you can replace it with CDN links) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- SweetAlert CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>

<!-- Custom JS -->
<script src="./scripts/index.js"></script>
<script src="./scripts/blog/index.js"></script>

</body>
</html>
