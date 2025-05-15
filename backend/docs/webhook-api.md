# Webhook API Documentation

This document explains how to use the webhook API for creating content programmatically from external sources.

## Overview

The webhook API provides a way for external systems to create content in the CMS without requiring user authentication. Instead, it uses API key authentication to validate requests.

## Authentication

All webhook endpoints are protected by API key authentication. You must include a valid API key in your requests using one of the following methods:

### 1. Using the X-API-Key Header (Recommended)

```bash
curl -X POST "https://your-api.com/api/v1/webhook/articles" \
  -H "X-API-Key: your_api_key_here" \
  -H "Content-Type: application/json" \
  -d '{"title": "Article Title", "content": "Article content..."}'
```

### 2. Using a Query Parameter

```bash
curl -X POST "https://your-api.com/api/v1/webhook/articles?api_key=your_api_key_here" \
  -H "Content-Type: application/json" \
  -d '{"title": "Article Title", "content": "Article content..."}'
```

## Available Endpoints

### Create Article

Creates a new article in the CMS.

**URL**: `/api/v1/webhook/articles`

**Method**: `POST`

**Authentication**: API Key required

**Request Body**:

```json
{
  "title": "Article Title",
  "slug": "article-slug",
  "content": "Article content goes here...",
  "abstract": "A short summary of the article",
  "thumbnail": "https://example.com/image.jpg",
  "category_id": 1,
  "status": "draft",
  "tags": [1, 2, 3],
  "source": "External System Name",
  "external_id": "external-system-id-123"
}
```

**Required Fields**:
- `title`: The article title (string, max 255 characters)
- `content`: The article content (string)

**Optional Fields**:
- `slug`: Custom URL slug (string, max 255 characters, must be unique)
- `abstract`: Short summary (string)
- `thumbnail`: URL to thumbnail image (string, max 255 characters)
- `category_id`: ID of the category (integer, must exist in the database)
- `status`: Article status (string, one of: "draft", "published", "archived")
- `tags`: Array of tag IDs (must exist in the database)
- `source`: Name of the external source (string, max 255 characters)
- `external_id`: ID from the external system (string, max 255 characters)

**Response**:

```json
{
  "_metadata": {
    "success": true
  },
  "result": {
    "message": "Article created successfully",
    "article": {
      "id": 123,
      "title": "Article Title",
      "slug": "article-slug",
      "category_id": 1,
      "thumbnail": "https://example.com/image.jpg",
      "abstract": "A short summary of the article",
      "content": "Article content goes here...",
      "status": "draft",
      "tags": [1, 2, 3],
      "created_at": "2023-06-01 12:34:56",
      "updated_at": "2023-06-01 12:34:56"
    }
  }
}
```

## Error Responses

If the request is invalid or fails for any reason, the API will return an appropriate error response:

### Invalid API Key

```json
{
  "_metadata": {
    "success": false
  },
  "result": {
    "message": "Invalid or missing API key"
  }
}
```

### Validation Error

```json
{
  "_metadata": {
    "success": false
  },
  "result": {
    "message": "The given data was invalid.",
    "errors": {
      "title": ["The title field is required."],
      "content": ["The content field is required."]
    }
  }
}
```

## Setting Up the Webhook User

To use the webhook API, you need to set up a webhook user and generate an API key. This can be done using the following commands:

```bash
# Create the webhook user
php artisan app:create-webhook-user

# Generate an API key
php artisan api:key:generate
```

The webhook user (`ainoshaai@gmail.com`) will be created with super_admin role, allowing it to perform all operations in the CMS. 
