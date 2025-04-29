<?php
/**
 * Database connection function
 * @return PDO database connection
 */
function getDbConnection() {
    try {
        $db = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
            DB_USER,
            DB_PASS
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

/**
 * Register a new user
 * @param string $username Username
 * @param string $password Password
 * @param string $email Email
 * @return bool|string True on success, error message on failure
 */
function registerUser($username, $password, $email) {
    try {
        // Validate inputs
        if (empty($username) || empty($password) || empty($email)) {
            return "All fields are required";
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }
        
        // Check if username or email already exists
        $db = getDbConnection();
        $stmt = $db->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return "Username or email already exists";
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $db->prepare('
            INSERT INTO users (username, password, email, created_at) 
            VALUES (:username, :password, :email, NOW())
        ');
        
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return "Registration failed: " . $e->getMessage();
    }
}

/**
 * Login a user
 * @param string $username Username
 * @param string $password Password
 * @return bool|string True on success, error message on failure
 */
function loginUser($username, $password) {
    try {
        // Validate inputs
        if (empty($username) || empty($password)) {
            return "Username and password are required";
        }
        
        // Get user
        $db = getDbConnection();
        $stmt = $db->prepare('SELECT id, username, password FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return "Invalid username or password";
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        return true;
    } catch (PDOException $e) {
        return "Login failed: " . $e->getMessage();
    }
}

/**
 * Check if user is logged in
 * @return bool True if logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Log out the current user
 */
function logoutUser() {
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    session_destroy();
}

/**
 * Get all blog posts, ordered by creation date (newest first)
 * @return array Array of blog posts
 */
function getAllPosts() {
    try {
        $db = getDbConnection();
        $stmt = $db->query('
            SELECT p.*, u.username 
            FROM posts p 
            LEFT JOIN users u ON p.user_id = u.id 
            ORDER BY p.created_at DESC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get a single blog post by ID
 * @param int $id Post ID
 * @return array|false Post data or false if not found
 */
function getPostById($id) {
    try {
        $db = getDbConnection();
        $stmt = $db->prepare('
            SELECT p.*, u.username 
            FROM posts p 
            LEFT JOIN users u ON p.user_id = u.id 
            WHERE p.id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Create a new blog post
 * @param string $title Post title
 * @param string $content Post content
 * @return bool True on success, false on failure
 */
function createPost($title, $content) {
    try {
        $db = getDbConnection();
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        $stmt = $db->prepare('
            INSERT INTO posts (user_id, title, content, created_at, updated_at) 
            VALUES (:user_id, :title, :content, NOW(), NOW())
        ');
        
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Update an existing blog post
 * @param int $id Post ID
 * @param string $title Updated title
 * @param string $content Updated content
 * @return bool True on success, false on failure
 */
function updatePost($id, $title, $content) {
    try {
        $db = getDbConnection();
        $stmt = $db->prepare('
            UPDATE posts 
            SET title = :title, content = :content, updated_at = NOW() 
            WHERE id = :id AND (user_id = :user_id OR :is_admin = 1)
        ');
        
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] ? 1 : 0;
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':is_admin', $isAdmin, PDO::PARAM_INT);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Delete a blog post
 * @param int $id Post ID
 * @return bool True on success, false on failure
 */
function deletePost($id) {
    try {
        $db = getDbConnection();
        
        // Only allow post owners or admins to delete posts
        $stmt = $db->prepare('
            DELETE FROM posts 
            WHERE id = :id AND (user_id = :user_id OR :is_admin = 1)
        ');
        
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] ? 1 : 0;
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':is_admin', $isAdmin, PDO::PARAM_INT);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
} 