# AI Web Builder

## Overview

AI Web Builder is a comprehensive web development assistant platform built with Laravel 12 that leverages artificial intelligence to help developers and non-technical users create websites and applications using any technology stack. The platform provides intelligent code suggestions, architecture guidance, and best practices across all major web development technologies.

## Features

### 🤖 AI-Powered Development Assistant
- **Universal Technology Support**: Provides expert guidance for all major web technologies including:
  - Frontend: HTML, CSS, JavaScript, React, Vue.js, Angular, Svelte
  - Backend: PHP (Laravel), Node.js (Express), Python (Django/Flask), Ruby (Rails), Java, Go
  - Databases: MySQL, PostgreSQL, MongoDB, Redis
  - APIs: REST, GraphQL, WebSockets
- **Intelligent Code Generation**: Context-aware code suggestions and implementations
- **Best Practices Guidance**: Modern development patterns and architectural advice

### 🎨 Modern User Interface
- **Responsive Design**: Works seamlessly across desktop, tablet, and mobile devices
- **Glassmorphism Effects**: Modern UI with backdrop blur and transparency
- **Dark/Light Theme Support**: Adaptive color schemes
- **Interactive Chat Interface**: Real-time AI conversations with code formatting

### 🏗️ Project Management
- **Project Organization**: Create and manage multiple web development projects
- **Progress Tracking**: Monitor development milestones and tasks
- **Code Storage**: Save and organize generated code snippets

### 🔧 Developer Tools
- **Code Formatting**: Integrated code syntax highlighting and formatting
- **Error Handling**: Comprehensive error management and user feedback
- **Performance Optimized**: Fast loading times and efficient resource usage

## Technology Stack

### Backend
- **Laravel 12**: PHP framework with modern architecture
- **OpenAI GPT-3.5-turbo**: AI-powered code generation and assistance
- **MySQL/PostgreSQL**: Primary database with session management
- **Redis**: Caching and session storage (optional)

### Frontend
- **HTML5/CSS3**: Semantic markup and modern styling
- **JavaScript (ES6+)**: Interactive user interface
- **Axios**: HTTP client for API communication
- **Vite**: Fast build tool and development server
- **Tailwind CSS**: Utility-first CSS framework

### Development Tools
- **Composer**: PHP dependency management
- **NPM**: JavaScript package management
- **Laravel Pint**: Code formatting and style enforcement
- **Pest**: PHP testing framework
- **Laravel Sail**: Docker development environment

## System Requirements

- **PHP**: 8.2 or higher
- **Node.js**: 18.0 or higher
- **Composer**: Latest version
- **NPM**: Latest version
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **OpenAI API Key**: Required for AI functionality

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd ai-web-builder
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure OpenAI API**
   - Add your OpenAI API key to `.env`:
   ```
   OPENAI_API_KEY=your_api_key_here
   ```

6. **Database setup**
   ```bash
   php artisan migrate
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start development server**
   ```bash
   php artisan serve
   npm run dev
   ```

## Usage

### For Developers
1. **Access the platform** at `http://localhost:8000`
2. **Register/Login** to create an account
3. **Start a conversation** with the AI assistant
4. **Describe your project** and technology preferences
5. **Receive code suggestions** and architectural guidance
6. **Save projects** for future reference

### For Non-Technical Users
1. **Describe your website vision** in plain English
2. **Specify technology preferences** (or let AI recommend)
3. **Get step-by-step guidance** through the development process
4. **Receive code snippets** ready for implementation

## Project Structure

```
├── app/                    # Laravel application code
│   ├── Http/Controllers/   # Controllers
│   └── Models/            # Eloquent models
├── database/              # Database migrations and seeds
├── public/                # Public assets
├── resources/             # Views and frontend assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── views/            # Blade templates
├── routes/                # Route definitions
├── tests/                 # Test files
└── docs/                  # Documentation
```

## API Documentation

### Chat API
- **Endpoint**: `POST /api/chat`
- **Payload**: `{"message": "Your development question"}`
- **Response**: AI-generated response with code suggestions

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Testing

```bash
# Run PHP tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test tests/Feature/ExampleTest.php
```

## Code Quality

```bash
# Format code
vendor/bin/pint

# Check code style
vendor/bin/pint --test
```

## Deployment

### Production Setup
1. Configure production environment variables
2. Set up database and run migrations
3. Build assets: `npm run build`
4. Configure web server (Apache/Nginx)
5. Set up SSL certificate
6. Configure caching (Redis recommended)

### Docker Deployment
```bash
# Using Laravel Sail
./vendor/bin/sail up -d
```

## Security

- CSRF protection on all forms
- Input validation and sanitization
- Secure password hashing
- Session management
- Rate limiting on API endpoints

## Performance

- **Asset optimization**: Vite bundling and minification
- **Database optimization**: Eloquent query optimization
- **Caching**: Redis for session and cache storage
- **CDN ready**: Static assets can be served via CDN

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

- **Documentation**: See `/docs` folder for detailed guides
- **Issues**: Report bugs and request features on GitHub
- **Discussions**: Join community discussions for help and ideas

## Roadmap

- [ ] Multi-language support
- [ ] Code execution environment
- [ ] Project templates
- [ ] Team collaboration features
- [ ] Advanced AI models integration
- [ ] Mobile app companion

---

**Built with ❤️ using Laravel 12 and modern web technologies**