# HR Management System (WORK IN PROGRESS)

NOTE: THE APPLICATION IS STILL IN DEVELOPMENT

A comprehensive Human Resources management system built with Laravel, designed to streamline HR operations, employee management, and organizational processes.

## Features

### User & Role Management
- Role-based access control with customizable permissions
- User profile management
- Department and job title organization
- Social authentication support

### Leave Management
- Leave request creation and tracking
- Multi-level approval workflow
- Department head approval system
- Leave history and statistics

### Document Management
- Document type configuration
- Secure document storage and management
- Permission-based document access
- Document version control

### Working Hours & Time Tracking
- Working hours logging
- Monthly hour requirements based on company policy
- Time tracking analytics and reporting

### Onboarding & Training
- Employee onboarding task management
- Certificate management and tracking
- Mandatory training assignment and verification

### Feedback & Communication
- Employee feedback system
- Department-based communication channels
- Facility needs request and tracking

### Surveys & Polls
- Create and distribute surveys
- Collect and analyze poll responses
- Mandatory participation tracking

### Career Development
- Job opportunity postings
- Career advancement tracking
- Application management
- Internal job market

### Notifications & Dashboards
- Real-time notification system
- Customizable user dashboard
- Important dates tracking (birthdays, anniversaries)
- Task and requirement reminders

## Technology Stack

- **Backend**: Laravel 12
- **Frontend**: TailwindCSS, Alpine.js
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze, Socialite
- **Authorization**: Spatie Permission
- **UI Components**: Custom Tailwind components
- **Containerization**: Docker & Docker Compose

## System Requirements

### Standard Installation
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 8.0+ or PostgreSQL 13+
- Web server (Nginx or Apache)

### Docker Installation
- Docker Engine 20.10+
- Docker Compose v2+

## Installation

You can set up this project using either the standard method or Docker.

### Option 1: Docker Setup (Recommended)

1. Clone the repository
   ```bash
   git clone https://github.com/yourusername/hr-management-system.git
   cd hr-management-system
   ```

2. Configure environment
   ```bash
   cp .env.example .env
   ```

3. Update the `.env` file with the following database settings:
   ```
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=hr_management
   DB_USERNAME=hr_user
   DB_PASSWORD=your_password
   ```

4. Start the Docker containers
   ```bash
   docker-compose up -d
   ```

5. Generate application key and run migrations
   ```bash
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate --seed
   ```

6. Access the application at `http://localhost`

7. For development with hot reloading:
   - Frontend is served at `http://localhost:5173` by the node container
   - Any changes to frontend files will be automatically reflected

8. Useful Docker commands:
   ```bash
   # View container logs
   docker-compose logs -f

   # Stop containers
   docker-compose down

   # Rebuild containers after Dockerfile or docker-compose.yml changes
   docker-compose up -d --build

   # Run Artisan commands
   docker-compose exec app php artisan [command]

   # Run Composer commands
   docker-compose exec app composer [command]

   # Run npm commands
   docker-compose exec node npm [command]
   ```

### Option 2: Standard Setup

1. Clone the repository
   ```bash
   git clone https://github.com/yourusername/hr-management-system.git
   cd hr-management-system
   ```

2. Install PHP dependencies
   ```bash
   composer install
   ```

3. Install and compile frontend assets
   ```bash
   npm install
   npm run build
   ```

4. Configure environment
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database in the `.env` file
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hr_management
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run migrations and seed the database
   ```bash
   php artisan migrate --seed
   ```

7. Start the development server
   ```bash
   php artisan serve
   ```

8. Access the application at `http://localhost:8000`

## Default Users

After seeding, the following test users are available:

- **Admin**: admin@example.com (Password: password)
- **HR Manager**: hr@example.com (Password: password)
- **Department Head**: manager@example.com (Password: password)
- **Employee**: employee@example.com (Password: password)

## Architecture

### Models

The system includes the following key models:

- **User**: Employee information and authentication
- **Department**: Organizational structure
- **JobTitle**: Employee positions
- **LeaveRequest**: Time-off requests
- **LeaveReason**: Categorization of absence types
- **Document/UserDocument**: Document management
- **WorkingHour**: Time tracking
- **FacilityNeed**: Resource requests
- **Poll/Survey**: Employee feedback mechanisms
- **Certificate**: Training and qualification tracking
- **CareerOpportunity**: Internal job postings
- **Notification**: User alerts

### Roles & Permissions

The system implements a comprehensive role-based access control system using Spatie's Laravel-Permission package:

- **Admin**: Complete system access
- **HR**: User management, document control, company policies
- **Department Head**: Team management, approval workflows
- **Employee**: Self-service functionality
- **User**: Basic access for non-employees (external applicants)

Custom permissions can be assigned to users independent of their roles for flexible access control.

### Routes & Controllers

Routes are organized by functionality and protected by middleware for appropriate access control:

- Admin routes (`/admin/*`) - Full system management
- User self-service routes - Personal information, leave requests, etc.
- Department head routes - Team management and approvals
- HR specific routes - Policy management, document control

### Views & UI

The application utilizes:
- Blade templates with component-based architecture
- Responsive Tailwind CSS design
- Alpine.js for interactive UI elements
- Custom dashboard layouts for different user roles

## Customization

### Adding New Roles

1. Use the admin interface to create new roles at `/admin/role-permissions/roles`
2. Assign appropriate permissions to the role
3. Assign the role to users as needed

### Custom Permissions

1. Create new permissions at `/admin/role-permissions/permissions`
2. Assign the permissions to roles or directly to users
3. Update controllers to check for the new permissions

### Adding New Document Types

Document types can be added through the admin interface at `/admin/document-types`

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For questions and support, please open an issue in the GitHub repository.
