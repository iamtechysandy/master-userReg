Universal User Registration System
Overview
This is a versatile user registration system that can be easily integrated into any web project. It provides user authentication, email verification, password reset functionality, and role management features.

Features
User registration with email verification
Password hashing for enhanced security
Password reset functionality
Role-based access control (RBAC) with three roles: Admin, Instructor, and User
User management interface for Admins
Bootstrap 5 (BS5) styling for a modern and responsive design
Prerequisites
PHP 7.0 or higher
MySQL database
Installation
Clone the repository to your local machine:
bash
Copy code
git clone https://github.com/iamtechysandy/master-userReg.git
Import the database schema (database.sql) into your MySQL database.
Update the database configuration in include/db_config.php with your database credentials.
Configure your SMTP server settings in the appropriate files (signup_process.php, reset_password.php, send_reset_link.php, etc.) for sending verification and reset emails.
Usage
Access the registration page (signup.php) to create a new user account.
After registration, the user will receive an email with a verification link to verify their email address.
Users can request a password reset if they forget their password.
Admins can manage users, including updating user roles, in the Admin dashboard (admin/home.php).
Access control based on user roles is implemented throughout the application.
Screenshots



Contributing
Contributions are welcome! Please feel free to fork this repository, make changes, and submit a pull request.

License
This project is licensed under the MIT License.
