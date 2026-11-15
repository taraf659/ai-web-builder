# Database Design & ERD

## Overview

The AI Web Builder uses a relational database design optimized for user management, session handling, and future extensibility for project management features.

## Database Schema

### Current Schema (v1.0)

The application currently uses 4 main tables with a focus on authentication and session management.

## Entity Relationship Diagram (ERD)

```
┌─────────────────────────────────────────────────────────────┐
│                          users                             │
├─────────────────────────────────────────────────────────────┤
│  id (PK)                 BIGINT UNSIGNED AUTO_INCREMENT    │
│  name                    VARCHAR(255)                      │
│  email                   VARCHAR(255) UNIQUE               │
│  email_verified_at       TIMESTAMP NULL                   │
│  password                VARCHAR(255)                      │
│  remember_token          VARCHAR(100) NULL                 │
│  created_at              TIMESTAMP                         │
│  updated_at              TIMESTAMP                         │
└─────────────────────────┬───────────────────────────────────┘
                          │ 1
                          │
                          │ *
┌─────────────────────────┴───────────────────────────────────┐
│                         sessions                           │
├─────────────────────────────────────────────────────────────┤
│  id (PK)                 VARCHAR(255)                       │
│  user_id (FK)            BIGINT UNSIGNED NULL              │
│  ip_address              VARCHAR(45) NULL                   │
│  user_agent              TEXT NULL                         │
│  payload                 LONGTEXT                          │
│  last_activity           INT                                │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          │
┌─────────────────────────┴───────────────────────────────────┐
│                   password_reset_tokens                    │
├─────────────────────────────────────────────────────────────┤
│  email (PK)              VARCHAR(255)                       │
│  token                   VARCHAR(255)                       │
│  created_at              TIMESTAMP NULL                    │
└─────────────────────────────────────────────────────────────┘
```

## Table Descriptions

### 1. Users Table

**Purpose**: Stores user account information and authentication data.

**Columns**:
- `id`: Primary key, auto-incrementing BIGINT
- `name`: User's full name (required)
- `email`: Unique email address for authentication (required)
- `email_verified_at`: Timestamp for email verification (nullable)
- `password`: Bcrypt-hashed password (required)
- `remember_token`: Token for "remember me" functionality (nullable)
- `created_at`: Record creation timestamp
- `updated_at`: Record last update timestamp

**Indexes**:
- PRIMARY KEY on `id`
- UNIQUE KEY on `email`

**Relationships**:
- One-to-Many with `sessions` table (user can have multiple sessions)

### 2. Sessions Table

**Purpose**: Manages user sessions for authentication and state persistence.

**Columns**:
- `id`: Primary key, session identifier (string)
- `user_id`: Foreign key to users table (nullable for guest sessions)
- `ip_address`: User's IP address (nullable)
- `user_agent`: Browser/client user agent string (nullable)
- `payload`: Serialized session data (encrypted)
- `last_activity`: Unix timestamp of last activity

**Indexes**:
- PRIMARY KEY on `id`
- INDEX on `user_id`
- INDEX on `last_activity`

**Relationships**:
- Many-to-One with `users` table

### 3. Password Reset Tokens Table

**Purpose**: Handles secure password reset functionality.

**Columns**:
- `email`: Primary key, user's email address
- `token`: Secure reset token (hashed)
- `created_at`: Token creation timestamp

**Indexes**:
- PRIMARY KEY on `email`

**Relationships**:
- References `users.email` (logical relationship, not enforced)

### 4. Cache Table (Laravel Default)

**Purpose**: Laravel's cache storage for performance optimization.

**Columns**:
- `key`: Cache key identifier
- `value`: Serialized cached data
- `expiration`: Cache expiration timestamp

## Database Design Principles

### Normalization
- **First Normal Form (1NF)**: All columns contain atomic values
- **Second Normal Form (2NF)**: No partial dependencies on composite keys
- **Third Normal Form (3NF)**: No transitive dependencies

### Performance Optimizations
- **Indexing Strategy**:
  - Primary keys on all tables
  - Unique constraint on user emails
  - Foreign key indexes for relationships
  - Performance indexes on frequently queried columns

- **Data Types**:
  - Appropriate sizing (VARCHAR lengths based on expected data)
  - TIMESTAMP for temporal data
  - BIGINT for primary keys (future scalability)

### Security Considerations
- **Password Storage**: Bcrypt hashing with salt
- **Session Security**: Encrypted payload storage
- **Token Security**: Hashed reset tokens with expiration

## Future Schema Extensions

### Planned Tables (v2.0)

```
┌─────────────────────────────────────────────────────────────┐
│                        projects                            │
├─────────────────────────────────────────────────────────────┤
│  id (PK)                 BIGINT UNSIGNED AUTO_INCREMENT    │
│  user_id (FK)            BIGINT UNSIGNED                   │
│  name                    VARCHAR(255)                      │
│  description             TEXT                              │
│  technology_stack        JSON                              │
│  status                  ENUM('active','completed','archived')
│  created_at              TIMESTAMP                         │
│  updated_at              TIMESTAMP                         │
└─────────────────────────┬───────────────────────────────────┘
                          │ 1
                          │
                          │ *
┌─────────────────────────┴───────────────────────────────────┐
│                       code_snippets                        │
├─────────────────────────────────────────────────────────────┤
│  id (PK)                 BIGINT UNSIGNED AUTO_INCREMENT    │
│  project_id (FK)         BIGINT UNSIGNED                   │
│  title                   VARCHAR(255)                      │
│  code                    LONGTEXT                          │
│  language                VARCHAR(50)                       │
│  created_at              TIMESTAMP                         │
│  updated_at              TIMESTAMP                         │
└─────────────────────────┬───────────────────────────────────┘
                          │ 1
                          │
                          │ *
┌─────────────────────────┴───────────────────────────────────┐
│                         chat_logs                          │
├─────────────────────────────────────────────────────────────┤
│  id (PK)                 BIGINT UNSIGNED AUTO_INCREMENT    │
│  user_id (FK)            BIGINT UNSIGNED                   │
│  project_id (FK)         BIGINT UNSIGNED NULL              │
│  message                 TEXT                              │
│  response                LONGTEXT                          │
│  created_at              TIMESTAMP                         │
└─────────────────────────────────────────────────────────────┘
```

### Extended ERD (Future State)

```
┌─────────────┐     ┌─────────────┐     ┌─────────────────┐
│    users    │─────│  projects   │─────│  code_snippets  │
└─────────────┘     └─────────────┘     └─────────────────┘
       │                   │                       │
       │                   │                       │
       └───────────────────┼───────────────────────┘
                           │
                           ▼
                   ┌─────────────┐
                   │  chat_logs  │
                   └─────────────┘
```

## Migration Strategy

### Current Migrations
1. `0001_01_01_000000_create_users_table.php`
   - Creates users, password_reset_tokens, and sessions tables
   - Establishes basic authentication infrastructure

2. `0001_01_01_000001_create_cache_table.php`
   - Laravel's default cache table for performance

3. `0001_01_01_000002_create_jobs_table.php`
   - Laravel's queue system (prepared for future features)

### Migration Best Practices
- **Version Control**: All schema changes tracked in migrations
- **Rollback Support**: All migrations include down() methods
- **Data Integrity**: Foreign key constraints and validation
- **Performance**: Minimal downtime during deployments

## Database Configuration

### Connection Settings
```php
// config/database.php
'default' => env('DB_CONNECTION', 'mysql'),

'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'ai_web_builder'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
    ],
],
```

### Environment Variables
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_web_builder
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Performance Monitoring

### Query Optimization
- **EXPLAIN Plans**: Analyze query execution
- **Index Usage**: Monitor index effectiveness
- **Slow Query Log**: Identify performance bottlenecks

### Monitoring Metrics
- **Connection Pool**: Database connection usage
- **Query Performance**: Response times and throughput
- **Storage Growth**: Table size and growth patterns
- **Backup Status**: Automated backup verification

## Backup & Recovery

### Backup Strategy
- **Daily Full Backups**: Complete database snapshots
- **Incremental Backups**: Hourly change tracking
- **Point-in-Time Recovery**: Granular restore capabilities

### Recovery Procedures
- **Automated Testing**: Regular backup restoration tests
- **Disaster Recovery**: Multi-region backup storage
- **Data Validation**: Post-recovery data integrity checks

This database design provides a solid foundation for the current authentication system while being extensible for future project management and AI interaction features.