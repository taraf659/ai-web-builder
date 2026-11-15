# UML Diagrams

## Overview

This document contains Unified Modeling Language (UML) diagrams for the AI Web Builder system, providing visual representations of the system's architecture, classes, and interactions.

## Class Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                           AI Web Builder                            │
├─────────────────────────────────────────────────────────────────────┤
│  + Laravel 12 Framework                                            │
│  + OpenAI GPT-3.5-turbo Integration                                │
│  + MySQL/PostgreSQL Database                                       │
└─────────────────────┬───────────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│                         Controllers                                │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                    ChatController                              │ │
│  ├─────────────────────────────────────────────────────────────────┤ │
│  │  - openai: OpenAI\Client                                       │ │
│  ├─────────────────────────────────────────────────────────────────┤ │
│  │  + __construct(openai: OpenAI\Client)                         │ │
│  │  + chat(request: Request): JsonResponse                       │ │
│  │  - getOpenAIResponse(message: string): string                 │ │
│  │  - generateFallbackResponse(message: string): string          │ │
│  └─────────────────────────────────────────────────────────────────┘ │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                   Controller (Base)                            │ │
│  ├─────────────────────────────────────────────────────────────────┤ │
│  │  + ValidatesRequests                                           │ │
│  │  + DispatchesJobs                                              │ │
│  └─────────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│                           Models                                   │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                       User                                     │ │
│  ├─────────────────────────────────────────────────────────────────┤ │
│  │  + HasFactory                                                  │ │
│  │  + Notifiable                                                  │ │
│  ├─────────────────────────────────────────────────────────────────┤ │
│  │  + fillable: array = ['name', 'email', 'password']            │ │
│  │  + hidden: array = ['password', 'remember_token']             │ │
│  │  + casts: array = ['email_verified_at' => 'datetime', ...]    │ │
│  └─────────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      Services & External                          │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                  OpenAI Service                                │ │
│  ├─────────────────────────────────────────────────────────────────┤ │
│  │  + API_KEY: string                                             │ │
│  │  + MODEL: string = 'gpt-3.5-turbo'                            │ │
│  ├─────────────────────────────────────────────────────────────────┤ │
│  │  + chat(messages: array, options: array): Response            │ │
│  │  + completions(prompt: string, options: array): Response      │ │
│  └─────────────────────────────────────────────────────────────────┘ │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                 Database Service                               │ │
│  ├─────────────────────────────────────────────────────────────────┤ │
│  │  + connection: PDO                                             │ │
│  ├─────────────────────────────────────────────────────────────────┤ │
│  │  + query(sql: string, params: array): Result                  │ │
│  │  + transaction(callable: function): mixed                      │ │
│  └─────────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────────┘
```

## Sequence Diagram - Chat Interaction

```
User Browser              Laravel App              OpenAI API              Database
     │                        │                        │                     │
     │   POST /api/chat       │                        │                     │
     │───message─────────────►│                        │                     │
     │                        │                        │                     │
     │                        │  Validate Request      │                     │
     │                        │◄───────────────────────┘                     │
     │                        │                        │                     │
     │                        │  Check Session         │                     │
     │                        │────────────────────────┼────────────────────►│
     │                        │                        │   Query Session      │
     │                        │◄────────────────────────┼─────────────────────┘
     │                        │                        │                     │
     │                        │  Process Message       │                     │
     │                        │────────────────────────►                     │
     │                        │  Build System Prompt   │                     │
     │                        │◄────────────────────────                      │
     │                        │                        │                     │
     │                        │  Call OpenAI API       │                     │
     │                        │────────────────────────►│                     │
     │                        │  Send Chat Request      │                     │
     │                        │                        │  Process Request     │
     │                        │                        │◄─────────────────────┘
     │                        │                        │                     │
     │                        │  Receive Response       │                     │
     │                        │◄────────────────────────┼─────────────────────┘
     │                        │                        │                     │
     │                        │  Format Response        │                     │
     │                        │◄────────────────────────                      │
     │                        │                        │                     │
     │                        │  Return JSON Response   │                     │
     │◄────────────────────────┼────────────────────────┼─────────────────────┘
     │                        │                        │                     │
     │   Display Response      │                        │                     │
     │◄────────────────────────┼────────────────────────┼─────────────────────┘
```

## Sequence Diagram - User Authentication

```
User Browser              Laravel App              Database                Session Store
     │                        │                        │                        │
     │   POST /login           │                        │                        │
     │───credentials─────────►│                        │                        │
     │                        │                        │                        │
     │                        │  Validate Input        │                        │
     │                        │◄───────────────────────┘                        │
     │                        │                        │                        │
     │                        │  Query User            │                        │
     │                        │────────────────────────►                        │
     │                        │  SELECT * FROM users    │                        │
     │                        │◄────────────────────────┼───────────────────────┘
     │                        │                        │                        │
     │                        │  Verify Password       │                        │
     │                        │◄───────────────────────┘                        │
     │                        │                        │                        │
     │                        │  Create Session        │                        │
     │                        │────────────────────────┼───────────────────────►│
     │                        │  Store Session Data     │                        │
     │                        │◄────────────────────────┼────────────────────────┘
     │                        │                        │                        │
     │                        │  Set Session Cookie     │                        │
     │                        │◄────────────────────────┼────────────────────────┘
     │                        │                        │                        │
     │                        │  Redirect to Home       │                        │
     │◄────────────────────────┼────────────────────────┼────────────────────────┘
     │                        │                        │                        │
     │   GET /home             │                        │                        │
     │───session cookie───────►│                        │                        │
     │                        │                        │                        │
     │                        │  Validate Session       │                        │
     │                        │────────────────────────┼───────────────────────►│
     │                        │  Retrieve Session       │                        │
     │                        │◄────────────────────────┼────────────────────────┘
     │                        │                        │                        │
     │                        │  Load User Data         │                        │
     │                        │────────────────────────►                        │
     │                        │  SELECT * FROM users    │                        │
     │                        │◄────────────────────────┼───────────────────────┘
     │                        │                        │                        │
     │                        │  Render Home Page       │                        │
     │                        │◄────────────────────────┼────────────────────────┘
     │                        │                        │                        │
     │   Display Home Page     │                        │                        │
     │◄────────────────────────┼────────────────────────┼────────────────────────┘
```

## Activity Diagram - AI Response Generation

```
Start: User sends message
     │
     ▼
Validate message input
     │
     ├─ Invalid ──► Return validation error
     │
     ▼
Check user authentication
     │
     ├─ Not authenticated ──► Return 401 error
     │
     ▼
Determine response strategy
     │
     ├─ OpenAI API available?
     │     │
     │     ├─ Yes ──► Call OpenAI API
     │     │           │
     │     │           ├─ Success ──► Process response
     │     │           │
     │     │           └─ API Error ──► Use fallback system
     │     │
     │     └─ No ───► Use fallback system
     │
     ▼
Generate fallback response
     │
     ├─ Laravel/PHP keywords ──► Laravel-specific response
     │
     ├─ React/Vue/Angular keywords ──► Frontend framework response
     │
     ├─ Node/Express keywords ──► Node.js response
     │
     ├─ Python/Django/Flask keywords ──► Python response
     │
     ├─ Database keywords ──► Database response
     │
     ├─ API keywords ──► API response
     │
     └─ Generic keywords ──► Universal web dev response
     │
     ▼
Format response with code blocks
     │
     ▼
Return JSON response
     │
End: Response delivered to user
```

## Component Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                        User Interface                              │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │        Navigation              │  │      Chat Interface      │   │
│  │  • Responsive Navbar          │  │  • Message Input         │   │
│  │  • Route-based Active States  │  │  • Response Display      │   │
│  │  • Mobile Menu                │  │  • Code Formatting       │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │      Authentication            │  │    Project Management    │   │
│  │  • Login/Register Forms       │  │  • Project CRUD          │   │
│  │  • Client-side Validation     │  │  • Code Snippet Storage  │   │
│  │  • Session Management         │  │  • Progress Tracking     │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
└─────────────────────┬───────────────────────────────────────────────┘
                      │ HTTP/AJAX
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      Application Layer                             │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │       Web Routes               │  │       API Routes         │   │
│  │  • GET /home, /projects       │  │  • POST /api/chat        │   │
│  │  • GET /login, /register      │  │  • Rate Limiting         │   │
│  │  • CSRF Protection            │  │  • Input Validation      │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │     Controllers                │  │     Middleware           │   │
│  │  • ChatController             │  │  • Authentication        │   │
│  │  • Request Handling           │  │  • CSRF Protection       │   │
│  │  • Response Formatting        │  │  • Session Management    │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
└─────────────────────┬───────────────────────────────────────────────┘
                      │ Database Queries
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│                        Data Layer                                  │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │       Eloquent Models          │  │     Query Builder        │   │
│  │  • User Model                 │  │  • Relationship Queries   │   │
│  │  • Mass Assignment            │  │  • Eager Loading         │   │
│  │  • Attribute Casting          │  │  • Raw SQL Queries       │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │      Migrations                │  │      Seeders             │   │
│  │  • Schema Definitions         │  │  • Test Data             │   │
│  │  • Version Control            │  │  • Factory Classes       │   │
│  │  • Rollback Support           │  │  • Relationships         │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
└─────────────────────┬───────────────────────────────────────────────┘
                      │ External Services
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│                     External Services                              │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │       OpenAI API               │  │     Development Tools    │   │
│  │  • GPT-3.5-turbo Model        │  │  • Laravel Pint          │   │
│  │  • Chat Completions           │  │  • Pest Testing          │   │
│  │  • Token Management           │  │  • Vite Build Tool       │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

## Use Case Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                      AI Web Builder Use Cases                      │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                          Actors                                │ │
│  ├─────────────────────────────────────────────────────────────────┤ │
│  │  • Developer: Technical user seeking code assistance          │ │
│  │  • Non-Technical User: Business user needing website guidance │ │
│  │  • System Administrator: Manages platform infrastructure      │ │
│  └─────────────────────────────────────────────────────────────────┘ │
└─────────────────────┬───────────────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│                        Use Cases                                  │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │    Authentication              │  │    AI Consultation       │   │
│  │  • Register Account           │  │  • Ask Development Qs     │   │
│  │  • Login to System            │  │  • Get Code Examples      │   │
│  │  • Reset Password             │  │  • Technology Guidance    │   │
│  │  • Logout                     │  │  • Best Practices         │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │   Project Management           │  │   Code Management        │   │
│  │  • Create Project             │  │  • Save Code Snippets     │   │
│  │  • View Projects              │  │  • Organize Code          │   │
│  │  • Update Project             │  │  • Search Code            │   │
│  │  • Delete Project             │  │  • Export Code            │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │   System Administration        │  │   User Management        │   │
│  │  • Monitor Performance        │  │  • View User Profiles     │   │
│  │  • Manage API Keys            │  │  • Update User Settings   │   │
│  │  • Configure System           │  │  • Delete User Accounts   │   │
│  │  • View Analytics             │  │  • Manage Permissions     │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

## State Diagram - User Session

```
┌─────────────┐
│   Guest     │
│  (Not Auth) │
└──────┬──────┘
       │ Visit Login/Register
       ▼
┌─────────────┐          Invalid Credentials
│  Logging   │◄─────────────────────────────────────┐
│    In      │                                      │
└──────┬──────┘                                      │
       │ Valid Credentials                           │
       ▼                                              │
┌─────────────┐          Session Timeout              │
│ Authenticated│◄─────────────────────────────────────┘
│   User       │
└──────┬──────┘
       │ Logout or Session Destroy
       ▼
┌─────────────┐
│   Guest     │
│  (Not Auth) │
└─────────────┘
```

## Deployment Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                      Production Environment                        │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │       Web Server               │  │      Database Server      │   │
│  │  • Nginx/Apache               │  │  • MySQL/PostgreSQL       │   │
│  │  • PHP-FPM                    │  │  • Connection Pooling     │   │
│  │  • SSL Termination            │  │  • Replication            │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │      Application Server        │  │      Cache Server         │   │
│  │  • Laravel Application        │  │  • Redis Cluster          │   │
│  │  • Queue Workers              │  │  • Session Storage        │   │
│  │  • Task Scheduler             │  │  • Application Cache      │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │     File Storage               │  │   External Services       │   │
│  │  • Static Assets              │  │  • OpenAI API             │   │
│  │  • User Uploads               │  │  • CDN (Optional)         │   │
│  │  • Backup Storage             │  │  • Email Service          │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
                      │ Load Balancer
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│                        User Clients                               │
├─────────────────────────────────────────────────────────────────────┤
│  • Desktop Browsers (Chrome, Firefox, Safari, Edge)               │
│  • Mobile Browsers (iOS Safari, Android Chrome)                   │
│  • Tablet Devices                                                  │
│  • API Clients (Postman, cURL, custom applications)               │
└─────────────────────────────────────────────────────────────────────┘
```

## Package Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                     Laravel Framework Packages                     │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │   Core Framework              │  │   Authentication          │   │
│  │  • Illuminate\Foundation      │  │  • Sanctum (Future)       │   │
│  │  • Illuminate\Http            │  │  • Breeze (Optional)     │   │
│  │  • Illuminate\Routing         │  │  • Jetstream (Optional)  │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │   Database & ORM              │  │   External Services       │   │
│  │  • Illuminate\Database        │  │  • OpenAI PHP Client      │   │
│  │  │  - Eloquent ORM           │  │  • Guzzle HTTP Client      │   │
│  │  │  - Query Builder          │  │  • Laravel Boost           │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │   Development Tools           │  │   Testing Framework       │   │
│  │  • Laravel Pint              │  │  • Pest PHP                │   │
│  │  • Laravel Sail              │  │  • PHPUnit                 │   │
│  │  • Laravel Tinker            │  │  • Mockery                 │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
└─────────────────────┬───────────────────────────────────────────────┘
                      │ Frontend Dependencies
                      ▼
┌─────────────────────────────────────────────────────────────────────┐
│                   Frontend Package Ecosystem                       │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │   Build Tools                  │  │   HTTP Client             │   │
│  │  • Vite                        │  │  • Axios                 │   │
│  │  • Laravel Vite Plugin         │  │  • Fetch API (Native)    │   │
│  │  • Concurrently                │  │  • XMLHttpRequest        │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────┐  ┌─────────────────────────┐   │
│  │   CSS Framework               │  │   Development Tools       │   │
│  │  • Tailwind CSS               │  │  • ESLint (Optional)      │   │
│  │  • PostCSS                    │  │  • Prettier (Optional)    │   │
│  │  • Autoprefixer               │  │  • Hot Module Reload      │   │
│  └─────────────────────────────────┘  └─────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

These UML diagrams provide a comprehensive visual representation of the AI Web Builder system's architecture, components, and interactions. They serve as valuable documentation for developers, architects, and stakeholders to understand the system's design and implementation.