# WP Agents - Demo

In this demo, we’ll build a simple agent that analyzes WordPress post content and automatically assigns the most relevant **category** and **tags**.

The agent connects to an LLM provider, processes post data received through WordPress hooks, and returns structured metadata ready to be applied to the post — showing how **WP Agents** can bridge AI reasoning with real WordPress actions.

Download the plugin and activate it to test. You need to install [WP Agents](https://github.com/santerref/wp-agents) plugin first.

Make sure to define your OpenAI API Key in `wp-config.php` first:

```php
define( 'OPENAI_API_KEY', 'sk-' );
```

Create a post without category and tags and see what happens after you hit save.

<img width="250" height="500" alt="Screenshot 2025-10-19 at 6 41 27 PM" src="https://github.com/user-attachments/assets/62254931-92f8-4ae8-88aa-1a82f23eb524" />
