-- Add user_id column to posts table if it doesn't exist
ALTER TABLE posts ADD COLUMN user_id INT;
-- Add foreign key constraint
ALTER TABLE posts ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL; 