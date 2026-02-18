# PHP Learning Project

## Overview
This project is designed to help you learn PHP, HTML, CSS, and MySQL by building a simple web application. The application demonstrates how to connect to a MySQL database, handle user input, and render dynamic content using PHP and HTML templates.

## Project Structure
```
php-learning-project
├── public
│   ├── index.php          # Main entry point of the application
│   ├── css
│   │   └── style.css      # CSS styles for the project
│   └── js
│       └── script.js      # JavaScript for client-side functionality
├── src
│   ├── Database.php       # Class for database connection and queries
│   ├── config.php         # Configuration settings (database credentials)
│   └── functions.php      # Utility functions for the project
├── templates
│   ├── header.html        # HTML markup for the header section
│   ├── footer.html        # HTML markup for the footer section
│   └── form.html          # HTML markup for user input form
├── .htaccess              # Server configuration for URL rewriting
└── README.md              # Documentation for the project
```

## Setup Instructions
1. **Install XAMPP**: Download and install XAMPP from the official website.
2. **Start Apache and MySQL**: Open the XAMPP Control Panel and start the Apache and MySQL services.
3. **Create Database**: Access phpMyAdmin (usually at `http://localhost/phpmyadmin`) and create a new database for the project.
4. **Configure Database**: Update the `src/config.php` file with your database credentials.
5. **Access the Application**: Place the `php-learning-project` folder in the `htdocs` directory of your XAMPP installation. Open your web browser and navigate to `http://localhost/php-learning-project/public/index.php`.

## Usage Guidelines
- Use the form provided in `templates/form.html` to submit user data.
- The application will process the input and interact with the MySQL database as defined in the `src/Database.php` and `src/functions.php` files.
- Customize the styles in `public/css/style.css` to change the appearance of the application.

## Learning Objectives
- Understand the basics of PHP and how it interacts with HTML.
- Learn how to connect to a MySQL database using PHP.
- Gain experience in writing CSS for styling web pages.
- Explore JavaScript for enhancing user experience on the client side.

Happy coding!