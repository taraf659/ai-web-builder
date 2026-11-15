# Deployment Guide

## Overview

This guide covers the deployment of AI Web Builder to production environments. The application is built with Laravel 12 and designed for scalable deployment across various hosting platforms.

## Prerequisites

### System Requirements
- **PHP**: 8.2 or higher
- **Web Server**: Nginx or Apache with PHP-FPM
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Node.js**: 18.0+ (for asset compilation)
- **Composer**: Latest version
- **SSL Certificate**: Required for HTTPS

### Environment Setup
- **Domain**: Registered domain name
- **DNS**: Properly configured DNS records
- **SSL**: Valid SSL certificate (Let's Encrypt recommended)
- **Backup**: Automated backup solution

## Deployment Options

### 1. Traditional Server Deployment

#### Ubuntu/Debian Server Setup

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd unzip curl

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Configure MySQL
sudo mysql_secure_installation

# Create database
sudo mysql -u root -p
CREATE DATABASE ai_web_builder;
CREATE USER 'webuser'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON ai_web_builder.* TO 'webuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Application Deployment

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/your-repo/ai-web-builder.git
cd ai-web-builder

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Environment configuration
cp .env.example .env
php artisan key:generate

# Edit .env file with production values
nano .env

# Run migrations
php artisan migrate --force

# Set proper permissions
sudo chown -R www-data:www-data /var/www/ai-web-builder
sudo chmod -R 755 /var/www/ai-web-builder
sudo chmod -R 775 /var/www/ai-web-builder/storage
```

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;

    # SSL Configuration
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384;

    root /var/www/ai-web-builder/public;
    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

#### SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install snapd
sudo snap install core; sudo snap refresh core
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot

# Get SSL certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Set up auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### 2. Docker Deployment

#### Docker Compose Setup

```yaml
# docker-compose.yml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: ai-web-builder-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - app-network

  nginx:
    image: nginx:alpine
    container_name: ai-web-builder-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx/conf.d/:/etc/nginx/conf.d/
      - ./docker/nginx/ssl/:/etc/nginx/ssl/
    networks:
      - app-network
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: ai-web-builder-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ai_web_builder
      MYSQL_USER: webuser
      MYSQL_PASSWORD: secure_password
      MYSQL_ROOT_PASSWORD: root_password
    volumes:
      - dbdata:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/my.cnf
    networks:
      - app-network

  redis:
    image: redis:alpine
    container_name: ai-web-builder-redis
    restart: unless-stopped
    networks:
      - app-network

volumes:
  dbdata:
    driver: local

networks:
  app-network:
    driver: bridge
```

#### Dockerfile

```dockerfile
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
```

### 3. Cloud Platform Deployment

#### Heroku Deployment

```json
// composer.json (add to scripts)
{
  "scripts": {
    "post-install-cmd": [
      "php artisan migrate --force"
    ]
  }
}
```

```yaml
# Procfile
web: vendor/bin/heroku-php-apache2 public/
```

```bash
# Deploy to Heroku
heroku create your-app-name
heroku addons:create heroku-postgresql:hobby-dev
heroku config:set APP_KEY=$(php artisan key:generate --show)
git push heroku main
```

#### AWS EC2 Deployment

```bash
# Launch EC2 instance
aws ec2 run-instances \
  --image-id ami-0abcdef1234567890 \
  --count 1 \
  --instance-type t3.micro \
  --key-name your-key-pair \
  --security-groups web-server-sg

# Connect and setup
ssh -i your-key.pem ec2-user@your-instance-ip

# Install dependencies (similar to Ubuntu setup above)
# Configure Nginx, PHP, MySQL
# Deploy application
```

#### DigitalOcean App Platform

```yaml
# .do/app.yaml
name: ai-web-builder
services:
- name: web
  source_dir: /
  github:
    repo: your-username/ai-web-builder
    branch: main
  run_command: heroku-php-apache2
  environment_slug: php
  instance_count: 1
  instance_size_slug: basic-xxs
  envs:
  - key: APP_KEY
    type: SECRET
    value: ${app_key}
databases:
- name: db
  engine: PG
  version: "13"
  size: basic
```

## Environment Configuration

### Production .env File

```env
APP_NAME="AI Web Builder"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_web_builder
DB_USERNAME=webuser
DB_PASSWORD=secure_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# OpenAI Configuration
OPENAI_API_KEY=your-openai-api-key
```

## Performance Optimization

### Laravel Optimization

```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear caches for updates
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Database Optimization

```sql
-- Add indexes for performance
CREATE INDEX idx_sessions_user_id ON sessions(user_id);
CREATE INDEX idx_sessions_last_activity ON sessions(last_activity);

-- Optimize tables
OPTIMIZE TABLE users, sessions, password_reset_tokens;
```

### Frontend Optimization

```bash
# Build optimized assets
npm run build

# Analyze bundle size
npm install --save-dev webpack-bundle-analyzer
npx webpack-bundle-analyzer dist/static/js/*.js
```

## Monitoring & Maintenance

### Application Monitoring

```bash
# Laravel Telescope (optional)
composer require laravel/telescope
php artisan telescope:install
php artisan migrate

# Health checks
php artisan make:command HealthCheck
```

### Log Management

```bash
# Configure log rotation
# /etc/logrotate.d/laravel
/var/www/ai-web-builder/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        systemctl reload nginx
    endscript
}
```

### Backup Strategy

```bash
# Database backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u webuser -p ai_web_builder > /backups/db_backup_$DATE.sql

# File backup
tar -czf /backups/files_backup_$DATE.tar.gz /var/www/ai-web-builder

# Clean old backups (keep last 30 days)
find /backups -name "*.sql" -mtime +30 -delete
find /backups -name "*.tar.gz" -mtime +30 -delete
```

### Security Hardening

```bash
# Disable root login
sudo sed -i 's/#PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config
sudo systemctl restart sshd

# Configure firewall
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw --force enable

# PHP security
# php.ini
expose_php = Off
display_errors = Off
log_errors = On
```

## Scaling Strategies

### Horizontal Scaling

```nginx
# Load balancer configuration
upstream backend {
    server app1.example.com:9000;
    server app2.example.com:9000;
    server app3.example.com:9000;
}

server {
    listen 80;
    server_name your-domain.com;

    location / {
        proxy_pass http://backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### Database Scaling

```yaml
# Read/write splitting
services:
  db-master:
    # Primary database
  db-slave-1:
    # Read replica 1
  db-slave-2:
    # Read replica 2
```

### CDN Integration

```bash
# Upload static assets to CDN
aws s3 sync public/build s3://your-cdn-bucket --delete

# Update asset URLs in Laravel
# config/app.php
'asset_url' => env('ASSET_URL', 'https://your-cdn-url.com'),
```

## Troubleshooting

### Common Issues

**503 Service Unavailable**
```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Check application logs
tail -f /var/www/ai-web-builder/storage/logs/laravel.log
```

**Database Connection Errors**
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

**Permission Issues**
```bash
# Fix storage permissions
sudo chown -R www-data:www-data /var/www/ai-web-builder/storage
sudo chmod -R 775 /var/www/ai-web-builder/storage
```

**SSL Certificate Issues**
```bash
# Renew Let's Encrypt certificate
sudo certbot renew

# Check certificate validity
openssl x509 -in /etc/letsencrypt/live/your-domain.com/cert.pem -text -noout
```

## Rollback Strategy

### Database Rollbacks

```bash
# Rollback last migration
php artisan migrate:rollback

# Rollback multiple migrations
php artisan migrate:rollback --step=3

# Reset all migrations
php artisan migrate:reset
```

### Application Rollbacks

```bash
# Git-based rollback
git log --oneline -10
git revert <commit-hash>
git push origin main

# Deploy previous version
git checkout <previous-tag>
npm run build
php artisan migrate
```

This deployment guide provides comprehensive instructions for deploying AI Web Builder across various hosting environments while ensuring security, performance, and maintainability.