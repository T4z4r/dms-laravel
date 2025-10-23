# Document Management System (DMS)

A Laravel-based web application for managing documents, including file uploads, categorization, departmental organization, and digital signatures.

## Features

- **File Management**: Upload, view, and manage documents with support for various file types.
- **Categories**: Organize files into customizable categories for better structure.
- **Departments**: Assign files to specific departments for access control and organization.
- **Digital Signatures**: Add and manage digital signatures for document authentication.
- **User Authentication**: Secure login and registration system with role-based access.
- **File Sharing**: Share files with other users via email notifications.
- **Trash Management**: Soft delete and restore functionality for files.
- **Responsive Design**: Built with Bootstrap for a mobile-friendly interface.

## Installation

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and npm
- MySQL or another supported database

### Steps

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd dms-laravel
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   - Copy `.env.example` to `.env` and update the database credentials and other settings.
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Database Setup**
   - Create a database and update the `.env` file with your database details.
   - Run migrations to set up the database schema.
   ```bash
   php artisan migrate
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Start the Application**
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` in your browser.

## Usage

1. **Register/Login**: Create an account or log in to access the system.
2. **Upload Files**: Navigate to the Files section to upload documents.
3. **Organize**: Assign categories and departments to files for better management.
4. **Sign Documents**: Use the signature feature to add digital signatures.
5. **Share Files**: Share files with other users and receive email notifications.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any improvements or bug fixes.

1. Fork the project.
2. Create your feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
