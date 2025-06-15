# AccreHub

AccreHub is a web-based document management system specifically designed for academic accreditation processes. It provides a centralized platform for organizing, storing, and retrieving accreditation-related documents efficiently.

## Features

- **Document Management**: Upload, organize, and manage accreditation documents
- **Smart Search**: Quick search functionality with filters for Areas, Parameters, and Years
- **User Role Management**: Different access levels for administrators and faculty members
- **Real-time Notifications**: Keep users informed about new document uploads and changes
- **Activity Logging**: Track all document-related activities
- **PDF Preview**: Built-in preview for PDF documents

## Tech Stack

- **Framework**: Laravel 10
- **Frontend**: Blade Templates, JavaScript
- **Admin Panel**: Filament 3
- **Database**: MySQL
- **PDF Handling**: PDF.js
- **Authentication**: Laravel Breeze

## Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/AccreHub.git
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Set up database
```bash
php artisan migrate
php artisan db:seed
```

5. Link storage
```bash
php artisan storage:link
```

6. Start the development server
```bash
php artisan serve
npm run dev
```

## Usage

1. Login with administrator credentials
2. Create Areas and Parameters
3. Upload and organize documents
4. Use the search functionality to find specific documents
5. Monitor activities through the admin dashboard

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

[MIT License](LICENSE.md)

## Developer

Joel Rayton 
https://senpaijoeru05.github.io/My-Portfolio
