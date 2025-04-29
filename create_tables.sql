-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `created_at` DATETIME NOT NULL
);

-- Add user_id column to posts table if it doesn't exist
ALTER TABLE `posts` ADD COLUMN IF NOT EXISTS `user_id` INT;
ALTER TABLE `posts` ADD CONSTRAINT IF NOT EXISTS `fk_user_post` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL; 