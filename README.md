# Amailer

## Overview
Amailer is a robust and efficient mailing solution developed using PHP and MySQL, leveraging the powerful features of Laravel 10. It is specifically designed for sending bulk emails through various services like Amazon SES, MailGun, and others. The project integrates the Filament Admin Panel for a user-friendly administrative interface.

## Features
- **Bulk Email Sending**: Efficiently sends a large number of emails with high deliverability.
- **Multiple Email Services**: Supports various email sending services such as Amazon SES and MailGun.
- **User Management**: Manage users and permissions through the Filament Admin Panel.
- **Email Templates**: Create and manage email templates for different types of bulk emails.
- **Reporting and Analytics**: Track the performance of your email campaigns with detailed analytics.
- **Scalability**: Designed to scale with your growing business needs.

## Requirements
- PHP >= 8.0
- MySQL
- Laravel 10
- Composer

## Installation

1. Clone the repository:

```bash
git clone https://github.com/AshikDev/amailer.git
```

2. Navigate to the project directory:

```bash
cd amailer
```

3. Install dependencies using Composer:

```bash
composer install
```

4. Edit `.env` to set your database and mail service configurations.

5. Run migrations:

```bash
php artisan migrate
```

6. Start the server:

```bash
php artisan serve
```

## Configuration
To configure Amailer for different email services, update the respective configurations in the `.env` file. Ensure that you have the necessary API keys and credentials for the services you intend to use.

## Usage
After installation, navigate to the Filament Admin Panel to manage your email campaigns. You can create new email templates, set up bulk email lists, and monitor the performance of your campaigns.

## Contributing
I welcome contributions! Please feel free to fork the repository, make changes, and submit pull requests.

## Contact
For any queries or support, please contact [ashik.onlinex@gmail.com](mailto:ashik.onlinex@gmail.com).
