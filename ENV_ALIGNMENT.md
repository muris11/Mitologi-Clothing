# Environment Variable Alignment Guide

This document maps backend (Laravel) environment variables to frontend (Next.js) equivalents.

## Quick Reference Table

| Backend Variable | Frontend Variable | Purpose |
|-----------------|-------------------|---------|
| `APP_URL` | `NEXT_PUBLIC_SITE_URL` | Site base URL |
| `APP_URL/api` | `NEXT_PUBLIC_API_URL` | API base URL |
| `AI_SERVICE_URL` | `NEXT_PUBLIC_AI_SERVICE_URL` | AI/ML Service URL |
| `NEXTJS_REVALIDATION_SECRET` | `REVALIDATION_SECRET` | Cache revalidation auth |
| `RECOMMENDER_API_KEY` | `RECOMMENDER_API_KEY` | AI recommendation API key |

## Development Configuration

### Backend (.env)
```
APP_URL=http://localhost:8011
AI_SERVICE_URL=http://127.0.0.1:5011/api
NEXTJS_REVALIDATION_SECRET=mitologi-revalidation-2024
RECOMMENDER_API_KEY=mitologi-secret-key-2024
```

### Frontend (nextjs-commerce/.env.local)
```
NEXT_PUBLIC_API_URL=http://localhost:8011/api
INTERNAL_API_URL=http://localhost:8011/api
NEXT_PUBLIC_AI_SERVICE_URL=http://127.0.0.1:5011
REVALIDATION_SECRET=mitologi-revalidation-2024
RECOMMENDER_API_KEY=mitologi-secret-key-2024
```

## Important Notes

### AI Service URL
- **Backend**: `AI_SERVICE_URL=http://127.0.0.1:5011/api`
- **Frontend**: `NEXT_PUBLIC_AI_SERVICE_URL=http://127.0.0.1:5011`
- ⚠️ Frontend uses **without** `/api` suffix - the endpoints are appended by the client code

### Revalidation Secret
Both must match exactly:
- Backend: `NEXTJS_REVALIDATION_SECRET=mitologi-revalidation-2024`
- Frontend: `REVALIDATION_SECRET=mitologi-revalidation-2024`

### Recommender API Key
Used for authenticating AI recommendation requests:
- Backend: `RECOMMENDER_API_KEY=mitologi-secret-key-2024`
- Frontend: `RECOMMENDER_API_KEY=mitologi-secret-key-2024`
- Sent as `X-API-Key` header in requests to AI service

## Port Summary

| Service | Port | URL Pattern |
|---------|------|---------------|
| Backend (Laravel) | 8011 | http://localhost:8011 |
| Frontend (Next.js) | 3011 | http://localhost:3011 |
| AI Service | 5011 | http://localhost:5011 |

## Production Configuration

For production, update all URLs to use your domain:

```
# Backend
APP_URL=https://adminmitologi.based.my.id
FRONTEND_URL=https://mitologiclothing.based.my.id

# Frontend
NEXT_PUBLIC_API_URL=https://adminmitologi.based.my.id/api
NEXT_PUBLIC_SITE_URL=https://mitologiclothing.based.my.id
NEXT_PUBLIC_AI_SERVICE_URL=https://apimitologi.based.my.id
```
