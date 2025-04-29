-- Create database
CREATE DATABASE IF NOT EXISTS blog_platform;
USE blog_platform;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create posts table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO posts (title, content, created_at, updated_at) VALUES
(
    'Welcome to the Blog Platform',
    'This is the first post on our new blogging platform. This platform allows you to create, edit, and delete blog posts.\n\nFeel free to explore and start creating your own content!',
    NOW(),
    NOW()
),
(
    'How to Use This Platform',
    'Using this platform is very simple. Here are the basic steps:\n\n1. To create a new post, click on "Create Post" in the navigation bar.\n2. To edit an existing post, click on the "Edit" button below the post.\n3. To delete a post, click on the "Delete" button and confirm your choice.\n\nStart blogging today!',
    NOW(),
    NOW()
); 