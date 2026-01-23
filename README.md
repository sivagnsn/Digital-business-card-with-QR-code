# Digital Business Card System

A web-based digital business card system that allows users to create and share professional vCards.

## Features

- Create and manage digital business cards
- Generate vCard files for easy contact sharing
- Dashboard for card management
- RESTful API for card operations
- MySQL database backend

## Setup Instructions

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Installation

1. Clone this repository
2. Copy `db_config.example.php` to `db_config.php`
3. Update `db_config.php` with your database credentials
4. Import the database schema:
   ```bash
   mysql -u your_user -p your_database < schema.sql
   ```
5. (Optional) Import sample users:
   ```bash
   mysql -u your_user -p your_database < import_users.sql
   ```
6. Create the `files` directory and set appropriate permissions:
   ```bash
   mkdir -p files
   chmod 755 files
   ```

## Project Structure

- `api/` - RESTful API endpoints
- `dashboard/` - Admin dashboard interface
- `files/` - Uploaded files and generated vCards
- `vcard.php` - vCard generation script
- `schema.sql` - Database schema
- `db_config.php` - Database configuration (not tracked in git)

## Database Configuration

The database configuration is stored in `db_config.php`. This file is excluded from version control for security. Use `db_config.example.php` as a template.

## API Endpoints

API endpoints are located in the `api/` directory. Refer to the individual files for endpoint documentation.

## License

[Add your license here]

## Contributing

[Add contribution guidelines here]
