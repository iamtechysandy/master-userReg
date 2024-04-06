CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Password should be hashed for security
    role ENUM('admin', 'instructor', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    email_verified BOOLEAN NOT NULL DEFAULT FALSE, -- Indicates whether the email is verified
    verification_code VARCHAR(255), -- Stores the verification code sent to the user
    reset_token VARCHAR(255), -- Stores the reset token for password reset
    reset_token_expiry TIMESTAMP -- Expiry timestamp for the reset token
);
