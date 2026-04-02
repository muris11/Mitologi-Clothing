# Mitologi Clothing

<p align="center">
  <img src="https://img.shields.io/badge/Flutter-3.11+-blue?logo=flutter" alt="Flutter">
  <img src="https://img.shields.io/badge/Next.js-16-black?logo=next.js" alt="Next.js">
  <img src="https://img.shields.io/badge/Laravel-12-red?logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/Python-3.12+-yellow?logo=python" alt="Python">
  <img src="https://img.shields.io/badge/Tests-Passing-green" alt="Tests">
  <img src="https://img.shields.io/badge/License-MIT-green" alt="License">
</p>

<p align="center">
  <strong>E-commerce platform with ML-powered recommendations</strong><br>
  Multi-service monorepo with Next.js storefront, Laravel backend, Flutter mobile app, and Python ML service
</p>

## Services Architecture

```
                    ┌─────────────────────────────────────┐
                    │         User Device               │
                    └─────────────┬───────────────────────┘
                                  │
          ┌───────────────────────┼───────────────────────┐
          │                       │                       │
          ▼                       ▼                       ▼
┌─────────────────────┐ ┌─────────────────────┐ ┌─────────────────────┐
│   Next.js Web       │ │   Flutter Mobile    │ │   Admin Dashboard   │
│   (Port 3000)       │ │   (iOS/Android)     │ │   (Laravel)         │
│                     │ │                     │ │                     │
│  ├─ Product catalog │ │  ├─ Native app      │ │  ├─ Product mgmt    │
│  ├─ Cart & checkout │ │  ├─ Cart & orders     │ │  ├─ Order tracking  │
│  ├─ User auth       │ │  ├─ Offline support │ │  ├─ Analytics       │
│  └─ SEO optimized   │ │  └─ Push notifs     │ │  └─ Content mgmt    │
└──────────┬──────────┘ └──────────┬──────────┘ └─────────────────────┘
           │                         │
           └─────────────────────────┘
                           │
           ┌───────────────┴───────────────┐
           │                               │
           ▼                               ▼
┌─────────────────────────┐    ┌─────────────────────────┐
│   Laravel Backend       │◄──►│   ML Recommendation     │
│   (Port 8000)           │    │   Service (Port 5000)   │
│                         │    │                         │
│  ├─ REST API            │    │  ├─ Naive Bayes model   │
│  ├─ Database (MySQL)    │    │  ├─ User behavior       │
│  ├─ Payments (Midtrans) │    │  ├─ Product similarity  │
│  ├─ Queue (Redis)       │    │  └─ Auto-retraining     │
│  └─ Admin panel         │    │                         │
└─────────────────────────┘    └─────────────────────────┘
```

## Quick Start

### Prerequisites

- **Node.js** 20+ with pnpm
- **PHP** 8.2+ with Composer
- **Flutter** 3.11+ with Dart SDK
- **Python** 3.12+
- **MySQL** 8.0+ or **PostgreSQL** 14+
- **Redis** (optional, for caching)

### Development Setup

```bash
# 1. Clone the repository
git clone https://github.com/muris11/Mitologi-Clothing.git
cd Mitologi-Clothing

# 2. Start Laravel Backend
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
composer run dev          # Runs Laravel + queue + Vite

# 3. Start Python ML Service (Terminal 2)
cd ../recommendation-service
pip install -r requirements.txt
python server.py          # Runs on port 5000

# 4. Start Next.js Frontend (Terminal 3)
cd ../nextjs-commerce
pnpm install
cp .env.example .env.local
pnpm dev                  # Runs on port 3000

# 5. Run Flutter Mobile (Terminal 4)
cd ../mobile
flutter pub get
flutter run --dart-define=MITOLOGI_API_BASE_URL=http://10.0.2.2:8000
```

## Project Structure

```
Mitologi-Clothing/
├── nextjs-commerce/          # Next.js 16 storefront
│   ├── app/                  # App Router (RSC)
│   ├── components/           # React components
│   ├── lib/                  # API clients, utils
│   └── public/               # Static assets
│
├── backend/                  # Laravel 12 API
│   ├── app/
│   │   ├── Http/Controllers/ # API controllers
│   │   ├── Models/           # Eloquent models
│   │   └── Services/         # Business logic
│   ├── database/
│   │   ├── migrations/       # Schema migrations
│   │   └── seeders/          # Test data
│   └── routes/
│       └── api.php           # API routes
│
├── mobile/                   # Flutter 3.x app
│   ├── lib/
│   │   ├── screens/          # UI screens
│   │   ├── providers/        # State management
│   │   ├── services/         # API services
│   │   └── config/           # Theme, API config
│   └── test/                 # Widget & unit tests
│
└── recommendation-service/   # Python ML service
    ├── app.py                # Flask API
    ├── model.py              # ML model logic
    ├── train_job.py          # Training pipeline
    └── requirements.txt      # Dependencies
```

## Tech Stack

### Frontend (Next.js Commerce)
- **Framework:** Next.js 16 with App Router
- **Styling:** Tailwind CSS 4.1 + OKLCH colors
- **Build Tool:** Turbopack
- **State:** React Server Components + SWR
- **Payments:** Midtrans Snap
- **Features:** ISR, Image optimization, Skeleton loading

### Backend (Laravel)
- **Framework:** Laravel 12 (PHP 8.2+)
- **API:** RESTful with Sanctum auth
- **Database:** MySQL/PostgreSQL with Eloquent
- **Queue:** Redis for background jobs
- **Testing:** PHPUnit with in-memory SQLite
- **Features:** Repository pattern, Service layer, Caching

### Mobile (Flutter)
- **Framework:** Flutter 3.11+ (Dart)
- **State:** Provider + ChangeNotifier
- **Navigation:** GoRouter with deep linking
- **HTTP:** Custom ApiService wrapper
- **Storage:** flutter_secure_storage (encrypted)
- **Features:** Skeleton loading, Animations, Responsive design

### ML Service (Python)
- **Framework:** Flask with CORS
- **ML:** scikit-learn (Naive Bayes)
- **Training:** Automated daily retraining
- **Features:** User recommendations, Product similarity

## Testing

### Run All Tests

```bash
# Laravel Backend
cd backend
composer run test              # 52 tests

# Python Service
cd recommendation-service
python -m pytest               # 25 tests

# Next.js Frontend
cd nextjs-commerce
pnpm test                      # Unit tests
pnpm test:e2e                  # Playwright E2E

# Flutter Mobile
cd mobile
flutter test                   # 16 widget tests
flutter analyze                # Static analysis
```

### Test Coverage

| Service | Tests | Status |
|---------|-------|--------|
| Laravel Backend | 52 | ✅ Passing |
| Python Service | 25 | ✅ Passing |
| Flutter Mobile | 16 | ✅ Passing |
| Next.js | TBD | 🔄 In Progress |

## API Documentation

### Authentication

All protected endpoints require Bearer token:

```http
Authorization: Bearer {token}
```

### Endpoints

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/products` | GET | List all products | No |
| `/api/products/{slug}` | GET | Product details | No |
| `/api/auth/login` | POST | User login | No |
| `/api/auth/register` | POST | User registration | No |
| `/api/cart` | GET/POST | Cart operations | Yes |
| `/api/checkout` | POST | Process checkout | Yes |
| `/api/orders` | GET | User order history | Yes |
| `/api/recommendations` | GET | Personalized products | Yes |

See [API.md](API.md) for complete documentation.

## Environment Variables

### Next.js (.env.local)
```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api
NEXT_PUBLIC_MIDTRANS_CLIENT_KEY=your_midtrans_key
REVALIDATION_SECRET=your_secret
```

### Laravel (.env)
```env
APP_URL=http://localhost:8000
DB_DATABASE=mitologi
DB_USERNAME=root
DB_PASSWORD=secret
MIDTRANS_SERVER_KEY=your_server_key
AI_SERVICE_URL=http://localhost:5000
```

### Flutter (dart-define)
```bash
flutter run --dart-define=MITOLOGI_API_BASE_URL=http://10.0.2.2:8000
```

### Python (.env)
```env
FLASK_ENV=development
PORT=5000
API_KEY=your_api_key
```

## Deployment

### Production Checklist

- [ ] Environment variables configured
- [ ] SSL certificates installed
- [ ] Database migrations run
- [ ] Queue workers started
- [ ] ML model trained
- [ ] CDN configured for images
- [ ] Monitoring enabled

### Docker (Optional)

```bash
# Build all services
docker-compose up --build

# Or individually
docker-compose up backend
docker-compose up nextjs
docker-compose up ml-service
```

## Code Quality

### Standards

| Service | Linter | Formatter | Coverage |
|---------|--------|-----------|----------|
| Next.js | ESLint | Prettier | 80%+ |
| Laravel | Pint | Pint | 80%+ |
| Flutter | flutter_lints | dart format | 80%+ |
| Python | flake8 | black | 80%+ |

### Pre-commit Hooks

```bash
# Install hooks
pre-commit install

# Run manually
pre-commit run --all-files
```

## Security

- ✅ Encrypted auth tokens (flutter_secure_storage)
- ✅ API key authentication between services
- ✅ CSRF protection on forms
- ✅ SQL injection prevention (parameterized queries)
- ✅ XSS protection (React/Flutter auto-escaping)
- ✅ Rate limiting on API endpoints
- ✅ HTTPS in production

## Features

### E-commerce
- ✅ Product catalog with categories & collections
- ✅ Cart with guest checkout support
- ✅ Multiple payment methods (Midtrans)
- ✅ Order tracking & history
- ✅ User wishlist

### ML Recommendations
- ✅ Personalized product recommendations
- ✅ "Frequently bought together"
- ✅ User behavior tracking
- ✅ Auto-retraining (daily)

### Mobile App
- ✅ Native iOS & Android
- ✅ Offline cart persistence
- ✅ Push notifications
- ✅ Deep linking
- ✅ Responsive design

### Admin
- ✅ Product management
- ✅ Order fulfillment
- ✅ Content management (CMS)
- ✅ Analytics dashboard

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Commit Convention

We use [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` New feature
- `fix:` Bug fix
- `docs:` Documentation
- `style:` Formatting
- `refactor:` Code restructuring
- `test:` Tests
- `chore:` Maintenance

## Roadmap

### Q2 2026
- [ ] Real-time inventory updates
- [ ] Multi-language support (EN/ID)
- [ ] Dark mode

### Q3 2026
- [ ] PWA support
- [ ] Advanced analytics
- [ ] A/B testing framework

### Q4 2026
- [ ] International shipping
- [ ] Multi-vendor marketplace
- [ ] AI chatbot support

## License

This project is licensed under the MIT License - see [LICENSE](LICENSE) file.

## Support

- 📧 Email: support@mitologiclothing.com
- 💬 Discord: [Join our server](https://discord.gg/mitologi)
- 📖 Documentation: [docs.mitologiclothing.com](https://docs.mitologiclothing.com)

## Acknowledgments

- [Laravel](https://laravel.com) - Backend framework
- [Next.js](https://nextjs.org) - Frontend framework
- [Flutter](https://flutter.dev) - Mobile framework
- [scikit-learn](https://scikit-learn.org) - ML library
- [Midtrans](https://midtrans.com) - Payment gateway

---

<p align="center">
  Made with ❤️ by Mitologi Clothing Team
</p>
