# Electric Bill Tracker

A comprehensive web application for tracking electric bills, monitoring consumption, and managing billing records.

## Features

- **User Authentication**: Secure login and registration system
- **Dashboard**: Real-time bill estimation and meter reading updates
- **Bills History**: Complete CRUD operations for bill records with print and export capabilities
- **Usage Analytics**: Visual bar chart showing monthly consumption trends
- **Account Management**: Update personal information and billing address
- **Dark/Light Mode**: Toggle between themes for comfortable viewing

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Libraries**: 
  - Bootstrap Icons
  - Chart.js (for usage charts)
  - html2canvas (for image export)
  - Inter Font (Google Fonts)

## Installation

### Prerequisites

1. XAMPP, WAMP, MAMP, or any PHP server with MySQL
2. PHP 7.4 or higher
3. MySQL 5.7 or higher

### Setup Steps

1. **Clone or download** the project to your web server directory:
   ```
   For XAMPP: C:\xampp\htdocs\electric-bill-tracker
   For WAMP: C:\wamp64\www\electric-bill-tracker
   ```

2. **Create the database**:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the `database.sql` file, OR
   - The database will be auto-created when you first access the application

3. **Configure database** (if needed):
   - Edit `config/database.php`
   - Update the following variables:
     ```php
     $host = 'localhost';
     $dbname = 'electric_db';
     $username = 'root';
     $password = '';
     ```

4. **Add your logo**:
   - Replace `assets/images/logo.png` with your actual logo image
   - Recommended size: 72x72 pixels for sidebar, 180x180 for auth pages

5. **Start your server** and access the application:
   ```
   http://localhost/electric-bill-tracker/
   ```

## File Structure

```
electric-bill-tracker/
├── api/
│   ├── auth.php        # Authentication endpoints
│   ├── bills.php       # Bills CRUD operations
│   ├── meter.php       # Meter reading operations
│   └── users.php       # User account operations
├── assets/
│   ├── css/
│   │   └── styles.css  # All styling (light & dark mode)
│   ├── images/
│   │   ├── logo.png    # Application logo
│   │   └── logo.svg    # SVG version of logo
│   └── js/
│       └── app.js      # All JavaScript functionality
├── config/
│   └── database.php    # Database configuration
├── index.html          # Login page (landing)
├── register.html       # Registration page
├── dashboard.html      # Main dashboard
├── bills.html          # Bills history management
├── usage.html          # Usage analytics
├── account.html        # Account settings
├── database.sql        # Database schema
└── README.md           # This file
```

## Usage Guide

### Bill Calculation Formula

The dashboard uses the following formulas:

1. **Monthly Consumption**: `Current Reading - Previous Reading = kWh Used`
2. **Cost per kWh**: `Total Bill Amount / kWh Used`
3. **Estimated Bill**: `kWh Used × Cost per kWh`

### Adding Bills

1. Navigate to **Bills History**
2. Fill in the form:
   - Select the billing month from dropdown
   - Enter consumption in kWh
   - Enter total cost in Pesos
   - Set the due date
3. Click **Save Bill**

### Viewing Usage Charts

1. Navigate to **Usage**
2. View the bar chart showing monthly consumption
3. Add more bills to see trend data

### Account Management

1. Navigate to **Account**
2. Click **Edit** to modify personal information or billing address
3. Click **Save** to confirm changes

## Demo Mode

The application works without a PHP backend using localStorage for demonstration:
- Data is stored in browser's localStorage
- Perfect for testing and development
- No server required for basic functionality

## Browser Support

- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## License

This project is for educational purposes.

## Support

For issues or questions, please review the code comments or contact the developer.
