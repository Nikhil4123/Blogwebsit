<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Get all blog posts
$posts = getAllPosts();

// Static categories for demonstration
$categories = [
    ['id' => 1, 'name' => 'Technology'],
    ['id' => 2, 'name' => 'Travel'],
    ['id' => 3, 'name' => 'Food'],
    ['id' => 4, 'name' => 'Health'],
    ['id' => 5, 'name' => 'Lifestyle']
];

// Static recent posts for sidebar if no actual posts
$recentPosts = [];
if (count($posts) > 0) {
    // Use actual posts if available
    $recentPosts = array_slice($posts, 0, 3);
} else {
    // Use static sample posts if no actual posts
    $recentPosts = [
        ['id' => 1, 'title' => 'Getting Started with Web Development', 'created_at' => date('Y-m-d H:i:s')],
        ['id' => 2, 'title' => 'The Art of Travel Photography', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))],
        ['id' => 3, 'title' => 'Healthy Recipes for Busy People', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))]
    ];
}

// Get featured post
$featuredPost = null;
if (count($posts) > 0) {
    $featuredPost = $posts[0];
} else {
    // Static featured post if no actual posts
    $featuredPost = [
        'id' => 1, 
        'title' => 'Welcome to Our Blog Platform', 
        'content' => 'This is a modern blog platform where you can share your thoughts and ideas with the world. Create an account today to start posting!',
        'created_at' => date('Y-m-d H:i:s'),
        'username' => 'Admin'
    ];
}

// Static popular tags
$popularTags = ['Technology', 'Travel', 'Food', 'Health', 'Lifestyle', 'Coding', 'Design', 'Photography'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Blog Platform</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Blog Platform</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../posts/create.php">Create Post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../auth/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../auth/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../auth/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Featured Post -->
        <div class="featured-post mb-5">
            <div class="row">
                <div class="col-md-8">
                    <span class="category-badge">Featured</span>
                    <h2><?php echo htmlspecialchars($featuredPost['title']); ?></h2>
                    <p>
                        <?php echo nl2br(htmlspecialchars(substr($featuredPost['content'], 0, 200) . '...')); ?>
                    </p>
                    <p class="text-muted">
                        Posted on <?php echo date('F j, Y', strtotime($featuredPost['created_at'])); ?>
                        <?php if (!empty($featuredPost['username'])): ?>
                            by <?php echo htmlspecialchars($featuredPost['username']); ?>
                        <?php endif; ?>
                    </p>
                    <a href="../posts/view.php?id=<?php echo $featuredPost['id']; ?>" class="btn">Read More</a>
                </div>
                <div class="col-md-4 d-none d-md-block">
                    <!-- Placeholder for featured image -->
                    <div style="height: 200px; background-color: rgba(255,255,255,0.3); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image fa-5x" style="color: rgba(255,255,255,0.5);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Search Box -->
                <div class="search-box mb-4">
                    <form method="GET" action="index.php">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for posts..." name="search">
                            <button type="submit" class="btn"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>

                <!-- Category Navigation -->
                <div class="mb-4">
                    <span class="mr-2"><strong>Categories:</strong></span>
                    <?php foreach ($categories as $category): ?>
                        <a href="index.php?category=<?php echo $category['id']; ?>" class="category-badge">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <h2 class="mb-4">Latest Blog Posts</h2>
                
                <?php if (count($posts) > 0): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="card mb-4 fade-in">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="author-info">
                                            <div class="author-avatar" style="background-color: #dee2e6; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user" style="color: #adb5bd;"></i>
                                            </div>
                                            <span class="author-name">
                                                <?php echo !empty($post['username']) ? htmlspecialchars($post['username']) : 'Anonymous'; ?>
                                            </span>
                                        </div>

                                        <h2 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                                        
                                        <!-- Category for posts -->
                                        <div class="mb-2">
                                            <span class="category-badge">
                                                <?php echo $categories[array_rand($categories)]['name']; ?>
                                            </span>
                                        </div>
                                        
                                        <p class="card-text text-muted">
                                            Posted on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                        </p>
                                        <p class="card-text">
                                            <?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 150) . '...')); ?>
                                        </p>
                                        <a href="../posts/view.php?id=<?php echo $post['id']; ?>" class="btn btn-custom-primary">Read More</a>
                                        
                                        <?php if (isLoggedIn() && (isset($post['user_id']) && $post['user_id'] == $_SESSION['user_id'])): ?>
                                            <a href="../posts/edit.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">Edit</a>
                                            <a href="../posts/delete.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4 d-none d-md-block">
                                        <!-- Placeholder for post image -->
                                        <div style="height: 150px; background-color: #f1f3f5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image fa-3x" style="color: #ced4da;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Static sample posts if no actual posts -->
                    <?php for ($i = 1; $i <= 3; $i++): ?>
                        <div class="card mb-4 static-placeholder fade-in">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="author-info">
                                            <div class="author-avatar" style="background-color: #dee2e6; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user" style="color: #adb5bd;"></i>
                                            </div>
                                            <span class="author-name">Sample Author</span>
                                        </div>

                                        <?php 
                                        $sampleTitles = [
                                            'Getting Started with Web Development', 
                                            'The Art of Travel Photography', 
                                            'Healthy Recipes for Busy People'
                                        ];
                                        $sampleContents = [
                                            'Web development is an exciting field that combines creativity with technical skills. This post explores the basics of HTML, CSS, and JavaScript to help beginners get started...',
                                            'Travel photography allows you to capture memories from your adventures. Learn about composition, lighting, and equipment to improve your travel photos...',
                                            'Eating healthy doesn\'t have to be time-consuming. These quick recipes are nutritious and delicious, perfect for busy weeknights...'
                                        ];
                                        ?>
                                        <h2 class="card-title"><?php echo $sampleTitles[$i-1]; ?></h2>
                                        
                                        <div class="mb-2">
                                            <span class="category-badge">
                                                <?php echo $categories[array_rand($categories)]['name']; ?>
                                            </span>
                                        </div>
                                        
                                        <p class="card-text text-muted">
                                            Posted on <?php echo date('F j, Y', strtotime('-' . $i . ' days')); ?>
                                        </p>
                                        <p class="card-text placeholder-text">
                                            <?php echo $sampleContents[$i-1]; ?>
                                        </p>
                                        <a href="#" class="btn btn-custom-primary">Read More</a>
                                    </div>
                                    <div class="col-md-4 d-none d-md-block">
                                        <!-- Placeholder for post image -->
                                        <div style="height: 150px; background-color: #f1f3f5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image fa-3x" style="color: #ced4da;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                    <div class="alert alert-info" role="alert">
                        No posts found. 
                        <?php if (isLoggedIn()): ?>
                            <a href="../posts/create.php">Create your first post!</a>
                        <?php else: ?>
                            <a href="../auth/login.php">Login</a> or <a href="../auth/register.php">Register</a> to create posts.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- About Blog -->
                <div class="sidebar-item">
                    <h4>About This Blog</h4>
                    <p>Welcome to our blog platform! This is a place where users can share their thoughts, experiences, and knowledge across various topics.</p>
                </div>
                
                <!-- Recent Posts -->
                <div class="sidebar-item">
                    <h4>Recent Posts</h4>
                    <ul class="list-unstyled">
                        <?php foreach ($recentPosts as $recentPost): ?>
                            <li class="mb-2">
                                <a href="<?php echo isset($recentPost['id']) ? '../posts/view.php?id=' . $recentPost['id'] : '#'; ?>">
                                    <?php echo htmlspecialchars($recentPost['title']); ?>
                                </a>
                                <br>
                                <small class="text-muted">
                                    <?php echo date('M j, Y', strtotime($recentPost['created_at'])); ?>
                                </small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Categories -->
                <div class="sidebar-item">
                    <h4>Categories</h4>
                    <ul class="list-unstyled">
                        <?php foreach ($categories as $category): ?>
                            <li class="mb-2">
                                <a href="index.php?category=<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Popular Tags -->
                <div class="sidebar-item">
                    <h4>Popular Tags</h4>
                    <div>
                        <?php foreach ($popularTags as $tag): ?>
                            <a href="#" class="category-badge mb-2"><?php echo htmlspecialchars($tag); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Us</h5>
                    <p>Our blog platform provides a space for users to share their ideas, stories, and knowledge with the world.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="../posts/create.php">Create Post</a></li>
                        <li><a href="../auth/login.php">Login</a></li>
                        <li><a href="../auth/register.php">Register</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="social-icons">
                        <a href="#" class="mr-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="mr-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="mr-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Blog Platform. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 