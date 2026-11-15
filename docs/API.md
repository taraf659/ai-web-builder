# API Documentation

## Overview

The AI Web Builder provides a RESTful API for AI-powered web development assistance. The API is designed to be simple, secure, and scalable.

## Base URL

```
https://your-domain.com/api
```

## Authentication

Currently, the API uses session-based authentication. Future versions will include token-based authentication.

### Session Authentication
- User must be logged in to access protected endpoints
- CSRF tokens required for state-changing operations
- Session cookies managed automatically by Laravel

## Endpoints

### 1. Chat API

**Endpoint**: `POST /api/chat`

**Purpose**: Send messages to the AI assistant and receive intelligent responses about web development.

#### Request

**Headers**:
```
Content-Type: application/json
X-CSRF-TOKEN: <csrf-token>
```

**Body**:
```json
{
  "message": "How do I create a responsive navbar in CSS?"
}
```

**Parameters**:
- `message` (required): The user's question or request (string, max 1000 characters)

#### Response

**Success Response (200 OK)**:
```json
{
  "success": true,
  "response": "To create a responsive navbar in CSS, you can use Flexbox or CSS Grid. Here's an example using Flexbox:\n\n```css\n.navbar {\n  display: flex;\n  justify-content: space-between;\n  align-items: center;\n  padding: 1rem;\n}\n\n@media (max-width: 768px) {\n  .navbar {\n    flex-direction: column;\n  }\n}\n```"
}
```

**Error Response (422 Unprocessable Entity)**:
```json
{
  "message": "The message field is required.",
  "errors": {
    "message": [
      "The message field is required."
    ]
  }
}
```

**Error Response (500 Internal Server Error)**:
```json
{
  "success": false,
  "message": "AI service temporarily unavailable. Please try again later."
}
```

#### Rate Limiting

- **Limit**: 60 requests per hour per user
- **Headers**: Standard Laravel rate limiting headers included

#### Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 401 | Unauthorized |
| 422 | Validation Error |
| 429 | Too Many Requests |
| 500 | Internal Server Error |

## API Response Format

All API responses follow a consistent JSON structure:

### Success Response
```json
{
  "success": true,
  "data": { ... },
  "message": "Optional success message"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": ["Error details"]
  }
}
```

## Code Examples

### JavaScript (Axios)

```javascript
// Using Axios
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

axios.post('/api/chat', {
  message: 'How do I use React hooks?'
}, {
  headers: {
    'X-CSRF-TOKEN': csrfToken
  }
})
.then(response => {
  console.log(response.data.response);
})
.catch(error => {
  console.error('Error:', error.response.data);
});
```

### PHP (Guzzle)

```php
use GuzzleHttp\Client;

$client = new Client([
  'base_uri' => 'https://your-domain.com',
  'cookies' => true, // For session management
]);

$response = $client->post('/api/chat', [
  'json' => [
    'message' => 'Explain Laravel Eloquent relationships'
  ],
  'headers' => [
    'X-CSRF-TOKEN' => $csrfToken
  ]
]);

$data = json_decode($response->getBody(), true);
echo $data['response'];
```

### Python (Requests)

```python
import requests

session = requests.Session()
# Login first to establish session

response = session.post('https://your-domain.com/api/chat', json={
  'message': 'How do I create a REST API with Node.js?'
}, headers={
  'X-CSRF-TOKEN': csrfToken
})

data = response.json()
print(data['response'])
```

### cURL

```bash
curl -X POST https://your-domain.com/api/chat \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{"message":"What are the best practices for database design?"}'
```

## AI Assistant Capabilities

The AI assistant is trained to provide expert guidance on:

### Frontend Technologies
- HTML5, CSS3, JavaScript (ES6+)
- React, Vue.js, Angular, Svelte
- Responsive design and mobile development
- CSS frameworks (Tailwind, Bootstrap)
- Frontend build tools (Vite, Webpack)

### Backend Technologies
- PHP (Laravel, Symfony)
- Node.js (Express, Fastify)
- Python (Django, Flask, FastAPI)
- Ruby (Ruby on Rails)
- Java (Spring Boot)
- Go, Rust, and other languages

### Database & APIs
- SQL databases (MySQL, PostgreSQL)
- NoSQL databases (MongoDB, Redis)
- REST API design
- GraphQL implementation
- API security and authentication

### DevOps & Deployment
- Docker containerization
- Cloud platforms (AWS, DigitalOcean, Vercel)
- CI/CD pipelines
- Performance optimization
- Security best practices

## Response Features

### Code Formatting
The AI responses include properly formatted code blocks:

```javascript
// Example React component
function Navbar({ user }) {
  return (
    <nav className="navbar">
      <div className="nav-brand">My App</div>
      {user && <div>Welcome, {user.name}!</div>}
    </nav>
  );
}
```

### Technology-Specific Advice
Responses are tailored to the mentioned technologies with appropriate frameworks and best practices.

### Fallback System
If the OpenAI API is unavailable, the system provides pre-defined helpful responses based on keywords.

## Security Considerations

### Input Validation
- All inputs are validated server-side
- Message length limited to 1000 characters
- HTML injection prevention

### Rate Limiting
- Prevents API abuse
- Graduated limits based on user type
- Clear error messages for limit exceeded

### Data Privacy
- No personal data stored in chat logs (current version)
- Secure API key management
- Encrypted data transmission (HTTPS required)

## Monitoring & Analytics

### Response Metrics
- Response time tracking
- Success/failure rates
- Popular topics and technologies

### Error Tracking
- Failed API calls logging
- User experience issues
- Performance bottlenecks

## Future API Enhancements

### Planned Features
- **Streaming Responses**: Real-time response streaming
- **Conversation History**: Persistent chat sessions
- **Code Execution**: Safe code execution environment
- **Project Context**: AI awareness of user projects
- **Multi-language Support**: Localized responses

### API Versioning
- Semantic versioning for API changes
- Backward compatibility maintenance
- Deprecation notices for old endpoints

## Testing the API

### Using Laravel Test Suite

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

### Manual Testing

1. Log in to the application
2. Open browser developer tools
3. Navigate to Network tab
4. Send a chat message
5. Inspect the API request and response

## Troubleshooting

### Common Issues

**CSRF Token Errors**
- Ensure CSRF token is included in request headers
- Token should be fetched from meta tag: `<meta name="csrf-token">`

**Rate Limiting**
- Wait for rate limit reset (1 hour)
- Consider upgrading to premium plan for higher limits

**AI Service Unavailable**
- Check OpenAI API status
- Verify API key configuration
- Fallback responses will be provided automatically

**Validation Errors**
- Ensure message is not empty
- Check message length (max 1000 characters)
- Verify proper JSON formatting

This API documentation provides comprehensive guidance for integrating with the AI Web Builder platform. For additional support or feature requests, please refer to the main project documentation.