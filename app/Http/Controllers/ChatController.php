<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected $openai;

    public function __construct()
    {
        $this->openai = \OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string',
        ]);

        $message = $request->input('message');
        $sessionId = $request->input('session_id') ?: session()->getId();

        // Save user message
        ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'user',
            'message' => $message,
        ]);

        try {
            // Use OpenAI for intelligent responses
            $response = $this->getOpenAIResponse($message);

            // Save AI response
            ChatMessage::create([
                'session_id' => $sessionId,
                'role' => 'ai',
                'message' => $response,
            ]);

            // Check if this is a website creation request and save as project
            if ($this->isWebsiteCreationRequest($message)) {
                $this->saveWebsiteProject($message, $response);
            }

            return response()->json([
                'success' => true,
                'response' => $response,
                'session_id' => $sessionId,
            ]);

        } catch (\Exception $e) {
            Log::error('Chat AI Error: '.$e->getMessage());

            // Fallback to simple responses if OpenAI fails
            $response = $this->generateFallbackResponse($message);

            // Save AI response
            ChatMessage::create([
                'session_id' => $sessionId,
                'role' => 'ai',
                'message' => $response,
            ]);

            return response()->json([
                'success' => true,
                'response' => $response,
                'session_id' => $sessionId,
            ]);
        }
    }

    private function getOpenAIResponse($message)
    {
        $systemPrompt = 'You are a CODE GENERATOR that responds ONLY with markdown code blocks.

When asked for any webpage or code:
- Generate appropriate HTML, CSS, and JavaScript for the requested page
- Use proper markdown code blocks: ```html, ```css, ```javascript
- NO text, NO explanations, NO descriptions
- ONLY code blocks

Example for login page:
```html
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<div class="login-form">
  <form>
    <input type="text" placeholder="Username">
    <input type="password" placeholder="Password">
    <button>Login</button>
  </form>
</div>
</body>
</html>
```

```css
.login-form { max-width: 300px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
input, button { display: block; width: 100%; margin: 10px 0; padding: 8px; }
button { background: blue; color: white; border: none; cursor: pointer; }
```

For ANY webpage request, provide the complete HTML, CSS, and JavaScript in separate code blocks.
NO TEXT OUTSIDE CODE BLOCKS.';

        try {
            $result = $this->openai->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $message,
                    ],
                ],
                'max_tokens' => 800,
                'temperature' => 0.3,
            ]);

            $response = $result->choices[0]->message->content;

            // Debug: log the response
            Log::info('AI Response: '.$response);

            return $response;

        } catch (\Exception $e) {
            Log::error('OpenAI API Error: '.$e->getMessage());
            throw $e;
        }
    }

    private function generateFallbackResponse($message)
    {
        // Fallback responses in case OpenAI fails
        $message = strtolower($message);

        if (str_contains($message, 'laravel') || str_contains($message, 'php')) {
            return "Laravel is a powerful PHP framework! For Laravel projects, you should use Artisan commands like 'php artisan make:model User' to create models, and follow MVC architecture. Would you like help with a specific Laravel feature like Eloquent, Blade templates, or routing?";
        }

        if (str_contains($message, 'react') || str_contains($message, 'vue') || str_contains($message, 'angular')) {
            return 'Frontend frameworks like React, Vue, and Angular are great for building interactive user interfaces! React uses components and hooks, Vue has a gentle learning curve, and Angular provides a complete framework. Which one are you working with?';
        }

        if (str_contains($message, 'node') || str_contains($message, 'javascript') || str_contains($message, 'express')) {
            return 'Node.js and Express are excellent for backend JavaScript development! You can build REST APIs, real-time applications, and more. Express makes routing and middleware simple. What kind of Node.js project are you building?';
        }

        if (str_contains($message, 'python') || str_contains($message, 'django') || str_contains($message, 'flask')) {
            return 'Python is versatile for web development! Django provides a full-featured framework with admin interface, while Flask is lightweight and flexible. Both work great for APIs and web applications. What Python framework interests you?';
        }

        if (str_contains($message, 'html') || str_contains($message, 'css')) {
            return 'HTML and CSS are the foundation of web development! HTML provides structure, CSS handles styling and layout. Modern CSS features like Flexbox and Grid make responsive design much easier. What specific styling or layout challenge are you facing?';
        }

        if (str_contains($message, 'database') || str_contains($message, 'sql') || str_contains($message, 'mysql') || str_contains($message, 'mongodb')) {
            return 'Database design is crucial for web applications! SQL databases like MySQL are great for structured data, while MongoDB excels with flexible schemas. Consider your data relationships and query patterns when choosing. What kind of data are you working with?';
        }

        if (str_contains($message, 'api') || str_contains($message, 'rest') || str_contains($message, 'graphql')) {
            return 'APIs connect your frontend and backend! REST is simple and widely used, GraphQL allows flexible queries. Both have their strengths - REST for simplicity, GraphQL for efficiency. What kind of API are you building?';
        }

        if (str_contains($message, 'hello') || str_contains($message, 'hi') || str_contains($message, 'مرحبا')) {
            return "Hello! I'm your AI web development assistant. I can help you with any web technology - from HTML/CSS to React, Node.js, Python, PHP, and more. What would you like to build today?";
        }

        if (str_contains($message, 'model') || str_contains($message, 'orm')) {
            return 'Object-Relational Mapping (ORM) makes database interactions easier! Different frameworks have their own ORMs - Laravel has Eloquent, Django has its built-in ORM, and there are libraries for other languages too. What database and framework are you using?';
        }

        if (str_contains($message, 'responsive') || str_contains($message, 'mobile')) {
            return 'Responsive design ensures your website works on all devices! Use CSS media queries, flexible layouts with Flexbox/Grid, and test on different screen sizes. Mobile-first design often works best. Need help with a specific responsive challenge?';
        }

        // Default fallback
        return "I'm here to help you with any web development technology! Whether it's frontend (HTML, CSS, JavaScript, React, Vue), backend (PHP, Node.js, Python, Ruby), databases, or deployment - I can assist. What specific technology or challenge are you working on?";
    }

    private function isWebsiteCreationRequest($message)
    {
        $message = strtolower($message);

        // Keywords that indicate website creation requests
        $websiteKeywords = [
            'create a website',
            'build a website',
            'make a website',
            'design a website',
            'website for',
            'html page',
            'web page',
            'landing page',
            'portfolio website',
            'business website',
            'personal website',
            'create html',
            'build html',
            'html code',
            'css code',
            'website template',
        ];

        foreach ($websiteKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function saveWebsiteProject($message, $response)
    {
        try {
            // Extract code from response
            $codes = $this->extractCodeFromResponse($response);

            Log::info('Website Project Save Attempt', [
                'message' => $message,
                'has_html' => ! empty($codes['html']),
                'has_css' => ! empty($codes['css']),
                'has_js' => ! empty($codes['js']),
                'html_length' => strlen($codes['html'] ?? ''),
                'css_length' => strlen($codes['css'] ?? ''),
                'js_length' => strlen($codes['js'] ?? ''),
            ]);

            if (empty($codes['html']) && empty($codes['css']) && empty($codes['js'])) {
                Log::info('No code found in response, skipping project save');

                return; // No code to save
            }

            // Generate project name from message
            $projectName = $this->generateProjectName($message);

            // For now, we'll create temporary projects (since we don't have user auth yet)
            // In production, this would use auth()->id()
            Project::create([
                'user_id' => 1, // Temporary - should be auth()->id() in production
                'name' => $projectName,
                'description' => 'AI-generated website project',
                'html_code' => $codes['html'],
                'css_code' => $codes['css'],
                'js_code' => $codes['js'],
                'technology_stack' => ['html', 'css', 'javascript'],
                'status' => 'active',
                'is_temporary' => true,
                'expires_at' => now()->addHours(24), // Expires in 24 hours
            ]);

            Log::info('Website project saved successfully', ['name' => $projectName]);

        } catch (\Exception $e) {
            Log::error('Project Save Error: '.$e->getMessage());
            // Don't throw error - just log it so chat still works
        }
    }

    private function extractCodeFromResponse($response)
    {
        $codes = [
            'html' => null,
            'css' => null,
            'js' => null,
        ];

        // Extract HTML code blocks
        preg_match_all('/```html\s*(.*?)\s*```/s', $response, $htmlMatches);
        if (! empty($htmlMatches[1])) {
            $codes['html'] = implode("\n\n", $htmlMatches[1]);
        }

        // Extract CSS code blocks
        preg_match_all('/```css\s*(.*?)\s*```/s', $response, $cssMatches);
        if (! empty($cssMatches[1])) {
            $codes['css'] = implode("\n\n", $cssMatches[1]);
        }

        // Extract JavaScript code blocks
        preg_match_all('/```javascript\s*(.*?)\s*```/s', $response, $jsMatches);
        if (! empty($jsMatches[1])) {
            $codes['js'] = implode("\n\n", $jsMatches[1]);
        }

        // Also check for generic code blocks that might contain HTML/CSS/JS
        preg_match_all('/```\s*(.*?)\s*```/s', $response, $genericMatches);
        foreach ($genericMatches[1] as $codeBlock) {
            $codeBlock = trim($codeBlock);
            if (str_contains($codeBlock, '<!DOCTYPE html>') || str_contains($codeBlock, '<html>')) {
                $codes['html'] = $codeBlock;
            } elseif (str_contains($codeBlock, '/*') || str_contains($codeBlock, '.class') || str_contains($codeBlock, '#id') || str_contains($codeBlock, '{') && str_contains($codeBlock, '}')) {
                $codes['css'] = $codeBlock;
            } elseif (str_contains($codeBlock, 'function') || str_contains($codeBlock, 'const') || str_contains($codeBlock, 'let') || str_contains($codeBlock, 'var')) {
                $codes['js'] = $codeBlock;
            }
        }

        // If no HTML found in code blocks, look for HTML tags directly in the response
        // This handles cases where AI provides HTML without proper markdown formatting
        if (empty($codes['html'])) {
            // Look for HTML content between potential HTML tags
            if (preg_match('/<!DOCTYPE html>(.*?)<\/html>/s', $response, $htmlMatch)) {
                $codes['html'] = $htmlMatch[0];
            } elseif (preg_match('/<html>(.*?)<\/html>/s', $response, $htmlMatch)) {
                $codes['html'] = $htmlMatch[0];
            } elseif (preg_match('/<body>(.*?)<\/body>/s', $response, $bodyMatch)) {
                // If we find a body tag, try to construct full HTML
                $headMatch = [];
                preg_match('/<head>(.*?)<\/head>/s', $response, $headMatch);
                $head = $headMatch[1] ?? '<title>Generated Website</title>';

                $codes['html'] = "<!DOCTYPE html>\n<html>\n<head>\n{$head}\n</head>\n<body>\n{$bodyMatch[1]}\n</body>\n</html>";
            }
        }

        // Additional fallback: Look for any content that looks like HTML/CSS/JS even without code blocks
        if (empty($codes['html']) && str_contains($response, '<')) {
            // Extract anything that looks like HTML tags
            preg_match_all('/<[^>]+>.*?<\/[^>]+>/s', $response, $tagMatches);
            if (! empty($tagMatches[0])) {
                $codes['html'] = implode("\n", $tagMatches[0]);
            }
        }

        if (empty($codes['css']) && (str_contains($response, '{') && str_contains($response, '}'))) {
            // Extract anything that looks like CSS rules - more specific pattern
            preg_match_all('/(\.[a-zA-Z][a-zA-Z0-9_-]*\s*\{[^}]*\}|#?[a-zA-Z][a-zA-Z0-9_-]*\s*\{[^}]*\})/s', $response, $cssMatches);
            if (! empty($cssMatches[0])) {
                $codes['css'] = implode("\n\n", $cssMatches[0]);
            }
        }

        return $codes;
    }

    private function generateProjectName($message)
    {
        $message = strtolower($message);

        // Try to extract meaningful name from message
        if (str_contains($message, 'portfolio')) {
            return 'Portfolio Website';
        } elseif (str_contains($message, 'business')) {
            return 'Business Website';
        } elseif (str_contains($message, 'landing')) {
            return 'Landing Page';
        } elseif (str_contains($message, 'blog')) {
            return 'Blog Website';
        } elseif (str_contains($message, 'ecommerce') || str_contains($message, 'shop')) {
            return 'E-commerce Website';
        } elseif (str_contains($message, 'restaurant')) {
            return 'Restaurant Website';
        } elseif (str_contains($message, 'personal')) {
            return 'Personal Website';
        } else {
            return 'AI Generated Website';
        }
    }

    public function getHistory(Request $request)
    {
        $sessionId = $request->input('session_id') ?: session()->getId();

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'session_id' => $sessionId,
        ]);
    }

    public function getSessions()
    {
        $sessions = ChatMessage::select('session_id')
            ->selectRaw('MIN(created_at) as first_message_at')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->selectRaw('COUNT(*) as message_count')
            ->groupBy('session_id')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($session) {
                // Get first user message for preview
                $firstMessage = ChatMessage::where('session_id', $session->session_id)
                    ->where('role', 'user')
                    ->orderBy('created_at', 'asc')
                    ->first();

                return [
                    'session_id' => $session->session_id,
                    'first_message_at' => $session->first_message_at,
                    'last_message_at' => $session->last_message_at,
                    'message_count' => $session->message_count,
                    'preview' => $firstMessage ? substr($firstMessage->message, 0, 100) : 'New Chat',
                ];
            });

        return response()->json([
            'success' => true,
            'sessions' => $sessions,
        ]);
    }
}
