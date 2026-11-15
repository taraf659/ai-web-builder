# Code Documentation

## Overview

This document provides detailed documentation of the AI Web Builder codebase, including class structures, methods, and implementation details.

## Application Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── ChatController.php    # AI chat functionality
│   └── Middleware/               # HTTP middleware (future)
├── Models/
│   └── User.php                  # User authentication model
├── Providers/
│   └── AppServiceProvider.php    # Application service provider
└── Services/                     # Business logic services (future)
```

## Controllers

### ChatController

**Location**: `app/Http/Controllers/ChatController.php`

**Purpose**: Handles AI-powered chat interactions with OpenAI integration and fallback responses.

#### Properties

```php
protected $openai;  // OpenAI client instance
```

#### Methods

##### `__construct()`
```php
public function __construct()
```
**Purpose**: Initialize the OpenAI client instance.

**Parameters**: None

**Returns**: void

##### `chat(Request $request)`
```php
public function chat(Request $request): JsonResponse
```
**Purpose**: Process chat messages and return AI responses.

**Parameters**:
- `$request` (Request): HTTP request containing message data

**Validation Rules**:
- `message`: required, string, max 1000 characters

**Response Format**:
```json
{
  "success": true,
  "response": "AI-generated response with code examples"
}
```

**Error Handling**:
- Validation errors return 422 status
- OpenAI API failures trigger fallback responses
- All errors return JSON format

##### `getOpenAIResponse(string $message)`
```php
private function getOpenAIResponse(string $message): string
```
**Purpose**: Generate responses using OpenAI GPT-3.5-turbo.

**Parameters**:
- `$message` (string): User's input message

**System Prompt**: Comprehensive prompt covering all web technologies

**Returns**: AI-generated response string

**Error Handling**: Throws exceptions on API failures

##### `generateFallbackResponse(string $message)`
```php
private function generateFallbackResponse(string $message): string
```
**Purpose**: Provide fallback responses when OpenAI API is unavailable.

**Parameters**:
- `$message` (string): User's input message

**Keyword Detection**:
- Laravel/PHP: Framework-specific guidance
- React/Vue/Angular: Frontend framework advice
- Node.js/Express: Backend JavaScript guidance
- Python/Django/Flask: Python web development
- HTML/CSS: Frontend markup and styling
- Database: SQL/NoSQL recommendations
- API: REST/GraphQL guidance
- Generic: Universal web development advice

**Returns**: Contextual response string

## Models

### User Model

**Location**: `app/Models/User.php`

**Purpose**: Eloquent model for user authentication and profile management.

#### Traits
- `HasFactory`: Enables model factories for testing
- `Notifiable`: Provides notification functionality

#### Fillable Attributes
```php
protected $fillable = [
    'name',      // User's full name
    'email',     // Unique email address
    'password',  // Bcrypt-hashed password
];
```

#### Hidden Attributes
```php
protected $hidden = [
    'password',        // Never expose passwords
    'remember_token',  // Hide remember tokens
];
```

#### Casts
```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
```

#### Relationships
- **Sessions**: One-to-many (user can have multiple sessions)
- **Future**: Projects, chat logs, code snippets

## Views

### Blade Templates

#### Layout Structure

##### `layouts/app.blade.php`
**Purpose**: Main application layout with navigation and common elements.

**Sections**:
- `title`: Dynamic page titles
- `content`: Main page content
- `styles`: Additional CSS (optional)
- `scripts`: Additional JavaScript (optional)

**Components**:
- Top navigation with brand and menu
- Main content area
- Footer (future)

##### `home.blade.php`
**Purpose**: Landing page with chat interface and features overview.

**Sections**:
- Hero section with welcome message
- Chat interface with message history
- Features showcase
- Call-to-action buttons

**JavaScript Features**:
- Real-time chat with typing indicators
- Message formatting with code highlighting
- AJAX communication with backend
- Error handling and user feedback

##### `login.blade.php`
**Purpose**: User authentication form.

**Features**:
- Email/password login
- Client-side validation
- Remember me option
- Error message display
- Redirect after successful login

##### `register.blade.php`
**Purpose**: User registration form.

**Features**:
- Username, email, password registration
- Password confirmation
- Client-side validation
- Terms acceptance (future)
- Automatic login after registration

##### `projects.blade.php`
**Purpose**: Project management interface.

**Features**:
- Project grid display
- Empty state with call-to-action
- Create new project functionality
- Project cards with metadata

## Routes

### Web Routes

**Location**: `routes/web.php`

```php
// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

// Protected routes (future authentication middleware)
Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/projects', function () {
    return view('projects');
})->name('projects');

Route::get('/help', function () {
    return view('help');
})->name('help');

// API routes
Route::post('/api/chat', [ChatController::class, 'chat'])->name('api.chat');
```

### Route Groups (Future)

```php
// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('projects', ProjectController::class);
});

// API routes with rate limiting
Route::middleware(['api', 'throttle:60,1'])->group(function () {
    Route::post('/api/chat', [ChatController::class, 'chat']);
    Route::apiResource('projects', ProjectApiController::class);
});
```

## Frontend Assets

### JavaScript

#### `resources/js/app.js`
**Purpose**: Main JavaScript application entry point.

**Features**:
- Bootstrap application
- Initialize components
- Global event handlers
- Utility functions

#### Chat Functionality

```javascript
// Chat message handling
function sendMessage(message) {
    return fetch('/api/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayMessage(data.response, 'ai');
            formatCodeBlocks();
        }
    });
}

// Code block formatting
function formatCodeBlocks() {
    document.querySelectorAll('pre code').forEach(block => {
        hljs.highlightBlock(block);
    });
}
```

### CSS

#### `resources/css/styles.css`
**Purpose**: Main stylesheet with modern design system.

**Key Classes**:

##### Layout
- `.top-nav`: Main navigation bar
- `.nav-container`: Navigation container
- `.main-container`: Main content wrapper
- `.modern-card`: Card component with glassmorphism

##### Components
- `.btn-primary`: Primary action buttons
- `.btn-secondary`: Secondary action buttons
- `.input-field`: Form input styling
- `.message`: Chat message bubbles

##### Utilities
- `.form-title`: Form headings with gradient text
- `.page-title`: Page headings
- `.text-center`: Center alignment
- `.hidden`: Hide elements

##### Responsive Design
```css
/* Mobile-first responsive breakpoints */
@media (max-width: 768px) {
    .nav-container {
        flex-direction: column;
    }
}

@media (min-width: 769px) {
    .projects-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
}
```

## Database Migrations

### User Migration

**Location**: `database/migrations/0001_01_01_000000_create_users_table.php`

**Schema**:
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

**Additional Tables**:
- `password_reset_tokens`: Password reset functionality
- `sessions`: User session management
- `cache`: Laravel caching system
- `jobs`: Queue system (prepared for future features)

## Configuration Files

### Application Configuration

**Location**: `config/app.php`

**Key Settings**:
```php
'name' => env('APP_NAME', 'AI Web Builder'),
'env' => env('APP_ENV', 'production'),
'debug' => (bool) env('APP_DEBUG', false),
'url' => env('APP_URL', 'http://localhost'),
'timezone' => 'UTC',
'locale' => 'en',
```

### Database Configuration

**Location**: `config/database.php`

**Connections**:
- MySQL/PostgreSQL for primary data
- Redis for caching and sessions
- SQLite for testing

## Environment Configuration

### `.env` Structure

```env
# Application
APP_NAME="AI Web Builder"
APP_ENV=local
APP_KEY=base64:generated-key
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_web_builder
DB_USERNAME=root
DB_PASSWORD=

# Cache & Sessions
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# OpenAI
OPENAI_API_KEY=your-api-key

# Mail (future)
MAIL_MAILER=log
```

## Testing

### Test Structure

```
tests/
├── Feature/
│   ├── ExampleTest.php          # Feature tests
│   └── ChatApiTest.php          # API endpoint tests
└── Unit/
    ├── ExampleTest.php          # Unit tests
    └── ChatControllerTest.php   # Controller logic tests
```

### Example Test

```php
// tests/Feature/ChatApiTest.php
public function test_chat_api_returns_successful_response()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/chat', [
            'message' => 'Hello, AI assistant!'
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'response'
        ]);
}
```

## Security Implementation

### CSRF Protection
- All forms include CSRF tokens
- AJAX requests send X-CSRF-TOKEN header
- Automatic token regeneration

### Input Validation
- Server-side validation on all inputs
- Laravel validation rules
- Sanitized output in Blade templates

### Authentication Security
- Bcrypt password hashing
- Secure session management
- Remember token functionality
- Account lockout protection (future)

### API Security
- Rate limiting (60 requests/hour)
- Input sanitization
- Error message sanitization
- Request logging

## Performance Optimizations

### Laravel Optimizations
```bash
# Production optimizations
php artisan config:cache    # Cache configuration
php artisan route:cache     # Cache routes
php artisan view:cache      # Cache views
php artisan optimize        # General optimization
```

### Database Optimizations
- Indexed foreign keys
- Eager loading for relationships
- Query result caching
- Connection pooling

### Frontend Optimizations
- Vite bundling and minification
- Asset versioning
- Lazy loading (future)
- CDN integration (future)

## Error Handling

### Global Exception Handler

**Location**: `app/Exceptions/Handler.php`

**Features**:
- Custom error pages
- Logging configuration
- API error responses
- User-friendly messages

### Error Logging

```php
// Log different error levels
Log::emergency('System is down');
Log::alert('Immediate action required');
Log::critical('Critical system error');
Log::error('Runtime error');
Log::warning('Warning condition');
Log::notice('Normal but significant');
Log::info('Informational message');
Log::debug('Debug information');
```

## Future Enhancements

### Planned Components

#### Project Management
```php
// app/Models/Project.php
class Project extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'technology_stack',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function codeSnippets()
    {
        return $this->hasMany(CodeSnippet::class);
    }
}
```

#### Advanced AI Features
- Conversation history
- Code execution environment
- Multi-language support
- Project context awareness

#### API Enhancements
- GraphQL integration
- WebSocket real-time features
- File upload capabilities
- Advanced filtering and pagination

This comprehensive code documentation provides developers with detailed insights into the AI Web Builder's implementation, architecture, and functionality.