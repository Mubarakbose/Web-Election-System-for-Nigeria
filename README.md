# INEC Election Management System

A comprehensive web-based election management system built for the Independent National Electoral Commission (INEC) of Nigeria. This system provides a complete digital infrastructure for conducting democratic elections with three distinct portals for different user types.

## 🎯 Features

### Voter Portal

- **Unified Login System**: Single entry point with role-based authentication
- **Location-Based Filtering**: Automatic filtering by State, LGA, and Constituencies
- **Card-Based Voting Interface**: Modern, intuitive voting experience with real-time state transitions
- **Vote Confirmation**: Clear visual feedback for successful vote submission
- **Results Viewing**: Public access to election results by position
- **Profile Management**: Update voter information and credentials

### Admin Portal

- **Contestant Management**: Add, update, delete, and view all contestants
- **Polling Staff Management**: Complete staff registration and management
- **Polling Unit Management**: Configure and manage polling units
- **Results Control**: Set result visibility (Public/Private) per contestant
- **Pagination**: Browse large datasets efficiently (configurable items per page)
- **Security Info**: Monitor system access and activity

### Polling Staff Portal

- **Voter Registration**: Add new voters with cascading location filters
- **Voter Authentication**: Verify voter identity and eligibility
- **State-Based Filtering**: Federal and State Constituencies automatically filter by selected state
- **Voter Search**: Quick lookup by Voter ID, name, or phone number
- **Voter Updates**: Modify voter information and credentials
- **Messaging**: Send SMS notifications to voters

## 🏗️ System Architecture

### Technology Stack

- **Frontend**: HTML5, CSS3, Bootstrap, JavaScript
- **Backend**: PHP 8.2
- **Database**: MySQL 8.0
- **Email**: PHPMailer
- **Containerization**: Docker

### Database Schema

- `users`: Admin and Polling Staff credentials
- `voter`: Registered voters with location details
- `contestant`: Election candidates with positions and votes
- Position hierarchy: President → Governor → Senator → Member

## 🚀 Installation

### Prerequisites

- Docker Desktop (recommended)
- OR XAMPP/WAMP/LAMP stack with PHP 8.2+ and MySQL 8.0+
- Composer (for dependency management)

### Docker Installation (Recommended)

1. Clone the repository:

```bash
git clone <repository-url>
cd FYP
```

2. Start the containers:

```bash
docker-compose up -d
```

3. Access the application:

- **Main Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081

4. Default credentials:

- **Database**: root/root
- **Admin**: (configure in database)
- **Polling Staff**: (configure in database)

### Manual Installation (XAMPP/WAMP)

1. Clone/download to your web server root:

```bash
# For XAMPP
C:\xampp\htdocs\FYP

# For WAMP
C:\wamp64\www\FYP
```

2. Import the database:

```bash
mysql -u root -p < Database/inec.sql
```

3. Install PHP dependencies:

```bash
composer install
```

4. Configure database connection in `Connections/localhost.php`:

```php
define('DB_SERVER', 'localhost');
define('DB_NAME', 'inec');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
```

5. Configure email settings in `Connections/email_config.php`

6. Access the application:

```
http://localhost/FYP/Index.php
```

## 📁 Project Structure

```
FYP/
├── Admin/                          # Admin portal
│   ├── CSS Files/                  # Admin-specific styles
│   ├── Scripts/                    # AJAX handlers
│   ├── Helpers/                    # Helper classes
│   ├── Services/                   # Business logic
│   ├── ContestantsImages/          # Uploaded contestant photos
│   ├── StaffsImages/               # Uploaded staff photos
│   └── *.php                       # Admin pages
├── PollingStaff/                   # Polling Staff portal
│   ├── PStaff CSS/                 # Staff-specific styles
│   ├── Scripts/                    # AJAX handlers
│   ├── VotersImages/               # Uploaded voter photos
│   └── *.php                       # Staff pages
├── Voter/                          # Voter portal
│   ├── CSS Style/                  # Voter-specific styles
│   ├── Scripts/                    # AJAX handlers
│   └── *.php                       # Voter pages
├── Connections/                    # Database configuration
│   ├── db.php                      # Database abstraction layer
│   ├── localhost.php               # DB connection settings
│   └── email_config.php            # Email settings
├── Login Scripts/                  # Authentication
│   └── UnifiedLogin.php            # Single login handler
├── Database/                       # Database schema
│   └── inec.sql                    # Database dump
├── includes/                       # Shared components
│   ├── AdminConstants.php          # Configuration constants
│   ├── ErrorHandler.php            # Error handling
│   ├── FlashMessage.php            # Session messages
│   └── Pagination.php              # Pagination logic
├── vendor/                         # Composer dependencies
├── Index.php                       # Main entry point (unified login)
├── Results.php                     # Presidential results
├── GovResults.php                  # Gubernatorial results
├── SenResults.php                  # Senatorial results
├── MemResults.php                  # Representative results
├── Results-AllInOne.php            # All results in one page
├── HowToVote.html                  # Voter guide
├── docker-compose.yml              # Docker configuration
├── Dockerfile                      # Docker image definition
└── README.md                       # This file
```

## 🔐 Security Features

- **Password Encryption**: Bcrypt hashing for all user passwords
- **Session Management**: Secure session handling with timeout
- **SQL Injection Protection**: Prepared statements throughout
- **Access Control**: Role-based access control for all portals
- **CSRF Protection**: Token-based form validation
- **File Upload Validation**: Image type and size validation
- **Error Handling**: Centralized error logging and user-friendly messages

## 📊 Database Tables

### `users` Table

- Stores Admin and Polling Staff credentials
- Columns: `UserID`, `UserName`, `Password`, `PhoneNumber`, `UserType`, `FirstName`, `LastName`, `UnitID`

### `voter` Table

- Stores registered voter information
- Columns: `VoterID`, `UserName`, `Password`, `Email`, `Phone`, `FirstName`, `LastName`, `State`, `LGA`, `FedConstituency`, `StateConstituency`, `AccessLevel`

### `contestant` Table

- Stores election candidates
- Columns: `ContestantID`, `Name`, `Position`, `State`, `FedConstituency`, `StateConstituency`, `Party`, `Image`, `Votes`, `ResultMode`

## 🎨 UI/UX Highlights

- **Responsive Design**: Mobile-friendly interface across all portals
- **Cascading Dropdowns**: State → LGA/Constituencies for better UX
- **Flash Messages**: Color-coded success/warning notifications
- **Card-Based Voting**: Visual voting interface with state transitions
- **Pagination**: Smooth navigation through large datasets
- **Public/Private Badges**: Visual indicators for result visibility

## 🛠️ Development

### Running in Development Mode

```bash
# Start Docker containers
docker-compose up

# View logs
docker-compose logs -f web

# Stop containers
docker-compose down
```

### Database Management

Access phpMyAdmin at http://localhost:8081 to:

- View and edit database tables
- Import/export data
- Run SQL queries
- Monitor database performance

## 📝 Usage

### For Voters

1. Navigate to `http://localhost/FYP/Index.php`
2. Select "Voter" and login with credentials
3. View assigned voting location
4. Cast votes for each position
5. View election results

### For Polling Staff

1. Navigate to `http://localhost/FYP/Index.php`
2. Select "Polling Staff" and login
3. Register new voters
4. Authenticate voters on election day
5. Send SMS notifications

### For Administrators

1. Navigate to `http://localhost/FYP/Index.php`
2. Select "Admin" and login
3. Manage contestants, staff, and polling units
4. Control result visibility
5. Monitor system activity

## 🔧 Configuration

### Pagination Settings

Edit `includes/AdminConstants.php`:

```php
const ITEMS_PER_PAGE = 10; // Number of items per page
```

### Email Settings

Edit `Connections/email_config.php`:

```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
```

## 🐛 Troubleshooting

### Database Connection Issues

- Verify `Connections/localhost.php` settings
- Ensure MySQL service is running
- Check database credentials

### Login Issues

- Verify `PhoneNumber` column exists in `users` table
- Check `Email` and `Phone` columns in `voter` table
- Clear browser cache and sessions

### Image Upload Issues

- Verify folder permissions (777 for upload directories)
- Check PHP `upload_max_filesize` and `post_max_size`
- Ensure image directories exist

## 📜 License

This project is developed for educational purposes.

## 👥 Contributors

Developed as a Final Year Project (FYP) for election management.

## 📞 Support

For issues and questions, please open an issue in the repository.

---

**Note**: This system is designed for educational and demonstration purposes. For production deployment, ensure proper security audits, SSL certificates, and compliance with local election regulations.
