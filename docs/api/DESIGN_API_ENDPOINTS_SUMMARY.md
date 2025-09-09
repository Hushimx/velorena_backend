# Design System API Endpoints Summary

This document provides a quick reference for all Design System API endpoints.

## Public Endpoints (No Authentication Required)

| Method | Endpoint                  | Description                                    |
| ------ | ------------------------- | ---------------------------------------------- |
| GET    | `/api/designs`            | List all designs with pagination and filtering |
| GET    | `/api/designs/search`     | Search designs by query                        |
| GET    | `/api/designs/categories` | Get available design categories                |
| GET    | `/api/designs/{design}`   | Get specific design details                    |
| POST   | `/api/designs/sync`       | Sync designs from external API                 |

## Authenticated Endpoints (Requires Bearer Token)

### Design Favorites

| Method | Endpoint                         | Description                       |
| ------ | -------------------------------- | --------------------------------- |
| POST   | `/api/designs/{design}/favorite` | Add design to user favorites      |
| DELETE | `/api/designs/{design}/favorite` | Remove design from user favorites |
| GET    | `/api/designs/favorites`         | Get user's favorite designs       |

### Design Collections

| Method | Endpoint                                                 | Description                    |
| ------ | -------------------------------------------------------- | ------------------------------ |
| POST   | `/api/designs/collections`                               | Create a new design collection |
| GET    | `/api/designs/collections`                               | Get user's design collections  |
| GET    | `/api/designs/collections/{collection}`                  | Get specific collection        |
| PUT    | `/api/designs/collections/{collection}`                  | Update collection              |
| DELETE | `/api/designs/collections/{collection}`                  | Delete collection              |
| POST   | `/api/designs/collections/{collection}/designs`          | Add design to collection       |
| DELETE | `/api/designs/collections/{collection}/designs/{design}` | Remove design from collection  |

### Design Integration with Appointments

| Method | Endpoint                                           | Description                    |
| ------ | -------------------------------------------------- | ------------------------------ |
| POST   | `/api/designs/{design}/appointments/{appointment}` | Link design to appointment     |
| DELETE | `/api/designs/{design}/appointments/{appointment}` | Unlink design from appointment |

### Design Integration with Orders

| Method | Endpoint                               | Description              |
| ------ | -------------------------------------- | ------------------------ |
| POST   | `/api/designs/{design}/orders/{order}` | Link design to order     |
| DELETE | `/api/designs/{design}/orders/{order}` | Unlink design from order |

### Design History

| Method | Endpoint               | Description                           |
| ------ | ---------------------- | ------------------------------------- |
| GET    | `/api/designs/history` | Get user's design interaction history |

## Quick Test Commands

### Test Public Endpoints

```bash
# List all designs
curl -X GET "http://localhost:8000/api/designs"

# Search designs
curl -X GET "http://localhost:8000/api/designs/search?q=business"

# Get categories
curl -X GET "http://localhost:8000/api/designs/categories"

# Get specific design
curl -X GET "http://localhost:8000/api/designs/1"
```

### Test Authenticated Endpoints

```bash
# Set your token
TOKEN="your-bearer-token-here"

# Add to favorites
curl -X POST "http://localhost:8000/api/designs/1/favorite" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"notes": "Love this design"}'

# Get favorites
curl -X GET "http://localhost:8000/api/designs/favorites" \
  -H "Authorization: Bearer $TOKEN"

# Create collection
curl -X POST "http://localhost:8000/api/designs/collections" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name": "My Collection", "description": "Test collection"}'

# Get collections
curl -X GET "http://localhost:8000/api/designs/collections" \
  -H "Authorization: Bearer $TOKEN"

# Get design history
curl -X GET "http://localhost:8000/api/designs/history" \
  -H "Authorization: Bearer $TOKEN"
```

## Database Tables Created

The following database tables were created to support the design system:

1. **design_favorites** - Stores user's favorite designs
2. **design_collections** - Stores user's design collections
3. **design_collection_items** - Stores items within collections

## Models Created

1. **DesignFavorite** - Model for design favorites
2. **DesignCollection** - Model for design collections
3. **DesignCollectionItem** - Model for collection items

## Features Implemented

✅ **Public Design Browsing**

-   List all designs with pagination
-   Search designs by query
-   Filter by category
-   Get design details

✅ **User Design Favorites**

-   Add/remove designs from favorites
-   Add notes to favorites
-   List user's favorite designs

✅ **User Design Collections**

-   Create/update/delete collections
-   Add/remove designs from collections
-   Public/private collections
-   Collection colors and descriptions

✅ **Design Integration**

-   Link designs to appointments
-   Link designs to orders
-   Add notes and priority to links

✅ **Design History**

-   Track all user design interactions
-   Unified view of favorites, collections, appointments, and orders

✅ **External API Integration**

-   Sync designs from external API
-   Search and filter from external sources

## Next Steps

1. **Testing**: Test all endpoints with Postman or similar tools
2. **Frontend Integration**: Integrate with the existing Livewire components
3. **Performance**: Add caching for frequently accessed designs
4. **Analytics**: Track design usage and popularity
5. **Recommendations**: Implement design recommendation system

