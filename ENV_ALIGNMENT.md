# Environment Variable Alignment Guide

This document maps backend (Laravel) environment variables to frontend (Next.js) equivalents.

## 🌐 Production URLs (Currently Active)

| Service | URL |
|---------|-----|
| Frontend (Next.js) | https://mitologiclothing.based.my.id |
| Backend (Laravel) | https://adminmitologi.based.my.id |
| AI Service (Flask) | https://apimitologi.based.my.id |

## Quick Reference Table

| Backend Variable | Frontend Variable | Production Value |
|-----------------|-------------------|------------------|
| `APP_URL` | `NEXT_PUBLIC_SITE_URL` | `https://mitologiclothing.based.my.id` |
| `APP_URL/api` | `NEXT_PUBLIC_API_URL` | `https://adminmitologi.based.my.id/api` |
| `AI_SERVICE_URL` | `NEXT_PUBLIC_AI_SERVICE_URL` | `https://apimitologi.based.my.id` |
| `NEXTJS_REVALIDATION_SECRET` | `REVALIDATION_SECRET` | `mitologi-revalidation-2024` |
| `RECOMMENDER_API_KEY` | `RECOMMENDER_API_KEY` | `mitologi-secret-key-2024` |

## Production Configuration

### Backend (.env)
```
APP_URL=https://adminmitologi.based.my.id
FRONTEND_URL=https://mitologiclothing.based.my.id
AI_SERVICE_URL=https://apimitologi.based.my.id
NEXTJS_REVALIDATION_SECRET=mitologi-revalidation-2024
RECOMMENDER_API_KEY=mitologi-secret-key-2024
```

### Frontend (nextjs-commerce/.env)
```
NEXT_PUBLIC_SITE_URL=https://mitologiclothing.based.my.id
NEXT_PUBLIC_API_URL=https://adminmitologi.based.my.id/api
INTERNAL_API_URL=https://adminmitologi.based.my.id/api
NEXT_PUBLIC_AI_SERVICE_URL=https://apimitologi.based.my.id
REVALIDATION_SECRET=mitologi-revalidation-2024
RECOMMENDER_API_KEY=mitologi-secret-key-2024
```

## Development Configuration

Untuk development, Anda bisa pilih:

### Option 1: Gunakan Production API (Recommended)
Jalankan NextJS locally tapi pakai API production:
```
NEXT_PUBLIC_API_URL=https://adminmitologi.based.my.id/api
INTERNAL_API_URL=https://adminmitologi.based.my.id/api
NEXT_PUBLIC_AI_SERVICE_URL=https://apimitologi.based.my.id
NEXT_PUBLIC_SITE_URL=http://localhost:3011
```

### Option 2: Gunakan Localhost (Jalankan semua service)
```
NEXT_PUBLIC_API_URL=http://localhost:8011/api
INTERNAL_API_URL=http://localhost:8011/api
NEXT_PUBLIC_AI_SERVICE_URL=http://localhost:5011
NEXT_PUBLIC_SITE_URL=http://localhost:3011
```

## Important Notes

### AI Service URL Format
- **Backend**: `AI_SERVICE_URL=https://apimitologi.based.my.id`
- **Frontend**: `NEXT_PUBLIC_AI_SERVICE_URL=https://apimitologi.based.my.id`
- ⚠️ Production URL **tidak perlu** `/api` suffix - endpoints appended by client

### Revalidation Secret
Both must match exactly:
- Backend: `NEXTJS_REVALIDATION_SECRET=mitologi-revalidation-2024`
- Frontend: `REVALIDATION_SECRET=mitologi-revalidation-2024`

### Recommender API Key
Used for authenticating AI recommendation requests:
- Sent as `X-API-Key` header in requests to AI service
- Must match between backend and frontend

## Service Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        PRODUCTION                           │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│   ┌──────────────────────┐                                  │
│   │   NextJS Frontend    │ https://mitologiclothing.based.my.id│
│   │   Port: 3011         │                                  │
│   └──────────┬───────────┘                                  │
│              │                                               │
│              ▼                                               │
│   ┌──────────────────────┐        ┌──────────────────────┐  │
│   │   Laravel Backend    │◀────▶│   Flask AI Service    │  │
│   │   adminmitologi.     │        │   apimitologi.        │  │
│   │   based.my.id        │        │   based.my.id         │  │
│   └──────────────────────┘        └──────────────────────┘  │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

## File Locations

- **Production Config**: `nextjs-commerce/.env`
- **Development Config**: `nextjs-commerce/.env.local`
- **Template**: `nextjs-commerce/.env.example`
- **Backend Config**: `backend/.env`
- **AI Service Config**: `recommendation-service/.env.production`

## Verification Commands

```bash
# Test Backend API
curl https://adminmitologi.based.my.id/api/site-settings

# Test AI Service
curl https://apimitologi.based.my.id/health

# Test Frontend (after build)
curl https://mitologiclothing.based.my.id
```
