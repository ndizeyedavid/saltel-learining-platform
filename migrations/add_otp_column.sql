-- Add OTP column to users table
ALTER TABLE users 
ADD COLUMN otp VARCHAR(6) NULL,
ADD COLUMN otp_expires_at TIMESTAMP NULL,
ADD COLUMN is_verified BOOLEAN DEFAULT FALSE;

-- Add index for faster OTP lookups
CREATE INDEX idx_users_otp ON users(otp);
CREATE INDEX idx_users_email_otp ON users(email, otp);
