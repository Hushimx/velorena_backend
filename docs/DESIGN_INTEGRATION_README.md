# Design Integration with External API

This system allows users to browse and select designs from an external API and attach them to appointments. The designs are stored locally in the database and can be synced from the external API.

## Features

-   **External API Integration**: Connect to design services using API keys
-   **Design Browsing**: Search and filter designs by category
-   **Design Selection**: Users can select multiple designs for appointments
-   **Notes & Priority**: Add notes and set priority for each selected design
-   **Livewire Components**: Real-time design selection and management
-   **API Endpoints**: RESTful API for design management

## Setup

### 1. Run Migrations

```bash
php artisan migrate
```

This will create:

-   `designs` table - stores design information
-   `appointment_designs` table - pivot table linking appointments and designs

### 2. Seed Sample Data

```bash
php artisan db:seed --class=DesignSeeder
```

### 3. Configure API Key

The API key is configured in `app/Services/DesignApiService.php`. Update the `$baseUrl` variable to match your external API endpoint.

## Usage

### Livewire Components

#### DesignSelector Component

The `DesignSelector` component allows users to browse and select designs:

```php
@livewire('design-selector')
```

Features:

-   Search designs by keyword
-   Filter by category
-   Sync designs from external API
-   Select/deselect designs
-   Add notes to selected designs

#### BookAppointment Component

The updated `BookAppointment` component now includes design selection:

```php
@livewire('book-appointment')
```

The component automatically handles:

-   Design selection storage
-   Design notes
-   Design priority ordering
-   Linking designs to appointments

### API Endpoints

#### Public Design Routes

-   `GET /api/designs` - List all designs with pagination
-   `GET /api/designs/search?q={query}` - Search designs
-   `GET /api/designs/categories` - Get available categories
-   `GET /api/designs/{id}` - Get specific design details
-   `POST /api/designs/sync` - Sync designs from external API

#### Query Parameters

-   `per_page` - Number of results per page (default: 20)
-   `category` - Filter by category
-   `search` - Search query

### Artisan Commands

#### Sync Designs

```bash
# Sync all designs
php artisan designs:sync

# Sync specific number of designs
php artisan designs:sync --limit=50

# Sync designs by category
php artisan designs:sync --category=logo

# Search and sync designs
php artisan designs:sync --search="business card" --limit=25
```

## Database Structure

### Designs Table

```sql
CREATE TABLE designs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    external_id VARCHAR(255) UNIQUE,
    title VARCHAR(255),
    description TEXT,
    image_url VARCHAR(500),
    thumbnail_url VARCHAR(500),
    metadata JSON,
    category VARCHAR(255),
    tags VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Appointment Designs Pivot Table

```sql
CREATE TABLE appointment_designs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    appointment_id BIGINT UNSIGNED,
    design_id BIGINT UNSIGNED,
    notes TEXT,
    priority INT DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE(appointment_id, design_id)
);
```

## Models

### Design Model

```php
use App\Models\Design;

// Get active designs
$designs = Design::active()->get();

// Search designs
$designs = Design::search('business card')->get();

// Get designs by category
$designs = Design::byCategory('logo')->get();

// Get design tags
$tags = $design->tags_array;
```

### Appointment Model

```php
use App\Models\Appointment;

// Get appointment with designs
$appointment = Appointment::with('designs')->find($id);

// Get selected designs
$designs = $appointment->designs;

// Get design with pivot data
foreach ($appointment->designs as $design) {
    echo $design->pivot->notes;
    echo $design->pivot->priority;
}
```

## External API Integration

### DesignApiService

The service handles communication with external design APIs:

```php
use App\Services\DesignApiService;

$apiService = new DesignApiService();

// Fetch designs
$designs = $apiService->fetchDesigns(['limit' => 50]);

// Search designs
$results = $apiService->searchDesigns('business card');

// Get categories
$categories = $apiService->getCategories();

// Sync designs to database
$synced = $apiService->syncDesigns(100);
```

### API Configuration

Update the following in `DesignApiService`:

```php
private $baseUrl = 'https://your-api-endpoint.com/v1';
private $apiKey = 'your-api-key-here';
```

## Frontend Integration

### Basic Design Selection

```html
<div class="design-grid">
    @foreach($designs as $design)
    <div class="design-card">
        <img src="{{ $design->thumbnail_url }}" alt="{{ $design->title }}" />
        <h3>{{ $design->title }}</h3>
        <input
            type="checkbox"
            wire:model="selectedDesigns"
            value="{{ $design->id }}"
        />
        @if(in_array($design->id, $selectedDesigns))
        <textarea
            wire:model="designNotes.{{ $design->id }}"
            placeholder="Add notes..."
        ></textarea>
        @endif
    </div>
    @endforeach
</div>
```

### Design Modal

```html
@if($showDesignModal)
<div class="modal">
    <img
        src="{{ $selectedDesignForModal->image_url }}"
        alt="{{ $selectedDesignForModal->title }}"
    />
    <h2>{{ $selectedDesignForModal->title }}</h2>
    <p>{{ $selectedDesignForModal->description }}</p>
    <button
        wire:click="toggleDesignSelection({{ $selectedDesignForModal->id }})"
    >
        {{ in_array($selectedDesignForModal->id, $selectedDesigns) ? 'Remove' :
        'Add' }}
    </button>
</div>
@endif
```

## Customization

### Adding New Design Fields

1. Update the migration
2. Update the Design model's `$fillable` array
3. Update the DesignApiService mapping
4. Update the seeder if needed

### Custom API Integration

1. Extend or modify `DesignApiService`
2. Update API endpoint configuration
3. Modify data mapping in `syncDesign` method
4. Update validation rules if needed

## Troubleshooting

### Common Issues

1. **API Connection Failed**

    - Check API key and endpoint URL
    - Verify network connectivity
    - Check API rate limits

2. **Designs Not Syncing**

    - Check API response format
    - Verify database connection
    - Check migration status

3. **Images Not Loading**
    - Verify image URLs are accessible
    - Check CORS settings
    - Verify image format support

### Debug Commands

```bash
# Check design count
php artisan tinker
>>> App\Models\Design::count();

# Test API connection
php artisan designs:sync --limit=1

# Check database structure
php artisan migrate:status
```

## Security Considerations

-   API keys are stored in environment variables
-   Input validation on all API endpoints
-   SQL injection protection through Eloquent ORM
-   XSS protection through Blade templating
-   CSRF protection on forms

## Performance Optimization

-   Database indexing on frequently queried fields
-   Pagination for large design collections
-   Lazy loading of design relationships
-   Caching of API responses (implement as needed)
-   Image optimization and CDN usage
