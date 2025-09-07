# External Design API Configuration

This document explains how to configure the External Design API endpoints for accessing FreeAPI services.

## Environment Configuration

Add the following environment variables to your `.env` file:

```env
# External Design API Configuration
FREEPIK_API_KEY=your_freepik_api_key_here
FREEPIK_BASE_URL=https://api.freepik.com/v1
```

## Configuration Details

### FREEPIK_API_KEY

-   **Required**: Yes
-   **Description**: Your FreeAPI API key for accessing their design services
-   **Example**: `FPSX853eec3a2a8b4fe1da2d17e3e27114b3`
-   **Security**: Keep this key secure and never expose it to the frontend

### FREEPIK_BASE_URL

-   **Required**: No (has default value)
-   **Description**: Base URL for the FreeAPI service
-   **Default**: `https://api.freepik.com/v1`
-   **Example**: `https://api.freepik.com/v1`

## Service Configuration

The configuration is automatically loaded from the `config/services.php` file:

```php
'freepik' => [
    'api_key' => env('FREEPIK_API_KEY'),
    'base_url' => env('FREEPIK_BASE_URL', 'https://api.freepik.com/v1'),
],
```

## Security Best Practices

1. **Never expose the API key** to the frontend or client-side code
2. **Use environment variables** to store sensitive configuration
3. **Add the .env file to .gitignore** to prevent accidental commits
4. **Use different API keys** for development and production environments
5. **Implement rate limiting** on your API endpoints to prevent abuse

## Testing Configuration

To test if your configuration is working correctly:

1. **Check environment variables**:

    ```bash
    php artisan tinker
    >>> config('services.freepik.api_key')
    >>> config('services.freepik.base_url')
    ```

2. **Test API endpoints**:

    ```bash
    # Test search endpoint
    curl "http://your-domain.com/api/external/designs/search?q=test&limit=5"

    # Test categories endpoint
    curl "http://your-domain.com/api/external/designs/categories"
    ```

3. **Check logs** for any API errors:
    ```bash
    tail -f storage/logs/laravel.log
    ```

## Troubleshooting

### Common Issues

1. **"Failed to fetch designs from external API"**

    - Check if `FREEPIK_API_KEY` is set correctly
    - Verify the API key is valid and active
    - Check if the base URL is correct

2. **"API request failed"**

    - Check your internet connection
    - Verify the external API service is available
    - Check the API key permissions

3. **"Validation failed"**
    - Ensure all required parameters are provided
    - Check parameter formats (e.g., hex colors, numeric values)

### Debug Mode

Enable debug mode in your `.env` file to see detailed error messages:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## Production Considerations

1. **Use HTTPS** for all API communications
2. **Implement caching** to reduce external API calls
3. **Set up monitoring** for API usage and errors
4. **Configure rate limiting** to prevent abuse
5. **Use a CDN** for serving design images if possible

## API Key Management

### Development

-   Use a development API key with limited permissions
-   Set up separate environment files for different developers

### Production

-   Use a production API key with appropriate permissions
-   Monitor API usage and costs
-   Set up alerts for unusual usage patterns

### Backup

-   Keep backup API keys in case of key rotation
-   Document the process for updating API keys

## Example Configuration Files

### .env (Development)

```env
APP_ENV=local
APP_DEBUG=true
FREEPIK_API_KEY=dev_key_here
FREEPIK_BASE_URL=https://api.freepik.com/v1
```

### .env (Production)

```env
APP_ENV=production
APP_DEBUG=false
FREEPIK_API_KEY=prod_key_here
FREEPIK_BASE_URL=https://api.freepik.com/v1
```

## Support

If you encounter issues with the configuration:

1. Check the Laravel logs in `storage/logs/laravel.log`
2. Verify your environment variables are loaded correctly
3. Test the external API directly with your API key
4. Check the FreeAPI documentation for any changes to their API
