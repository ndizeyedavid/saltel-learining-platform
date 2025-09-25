-- Add login-related columns to users table
ALTER TABLE users 
ADD COLUMN remember_token VARCHAR(64) NULL,
ADD COLUMN remember_expires TIMESTAMP NULL,
ADD COLUMN last_login TIMESTAMP NULL;

-- Add indexes for better performance
CREATE INDEX idx_users_remember_token ON users(remember_token);
CREATE INDEX idx_users_last_login ON users(last_login);
