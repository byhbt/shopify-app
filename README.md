# Intro
Build Shopify app using Laravel. 

### Tutorials:
- [https://codemason.io/blog/ultimate-guide-building-shopify-app-with-laravel](https://codemason.io/blog/ultimate-guide-building-shopify-app-with-laravel)

### Tools:
- [https://ngrok.com](https://ngrok.com)
For setting up a tunnel from local to Shopify for receiving webhooks notifications.

### Remaining issues:
1. Replace Guzzle by Httpplug.
2. Check User provider table.
3. Make Pricing package.
4. Use Graphql.
5. Remove all duplicated http calls. 

### .env

```
SHOPIFY_KEY=
SHOPIFY_SECRET=
SHOPIFY_REDIRECT=
SHOPIFY_WEBHOOK_URL=https://[id-from-ngrok].ngrok.io
```

``SHOPIFY_WEBHOOK_URL`` should be in https. 