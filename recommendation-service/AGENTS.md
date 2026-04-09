# recommendation-service

## Identity
- Small Flask service serving product/user recommendations and training endpoints.
- Consumed by Laravel through `App\Services\RecommendationService`.

## Entry Points
- `app.py` - Flask app, routes, API key guard, health endpoint
- `server.py` - Waitress server + scheduled training thread
- `model.py` - recommender implementation
- `train_job.py` - scheduled retraining job

## Commands
```bash
pip install -r requirements.txt
python app.py          # Flask dev server (port 5000)
python server.py       # Production (Waitress + scheduler)
```

## Where To Look
| Task | Location | Notes |
|------|----------|-------|
| API routes | `app.py` | Flask route definitions |
| Model logic | `model.py` | Recommendation algorithm |
| Training | `train_job.py` | Scheduled retraining |
| Production server | `server.py` | Waitress + threading |
| Model artifact | `recommender_model.pkl` | Trained model (binary) |

## Conventions
- Authenticated endpoints expect `X-API-Key`; keep Laravel and Flask key expectations aligned.
- Prefer extending `parse_limit` and shared helpers in `app.py` instead of duplicating request validation.
- `server.py` is the production-style runner because it starts Waitress and scheduled retraining.
- Health and training behavior must stay compatible with `backend/app/Services/RecommendationService.php` and `backend/app/Http/Controllers/Admin/ReportController.php`.
- Use type hints on all function signatures.
- Return consistent JSON responses with proper HTTP status codes.

## Code Style
- **Imports**: Standard library first, third-party second, local last; alphabetical within groups
- **Formatting**: PEP 8 (4 spaces, 88 char line length)
- **Types**: Python 3.12+ type hints, use `Optional[]`, `List[]`, `Dict[]` from `typing`
- **Naming**: snake_case functions/variables, PascalCase classes, UPPER_CASE constants
- **Error Handling**: Try/catch with specific exceptions, return JSON error responses
- **Documentation**: Docstrings for public functions (Google style)

## Anti-Patterns
- Do not commit logic changes based on `__pycache__/` output or `logs.txt` contents alone.
- Do not hardcode additional secrets; use env vars and keep `.env.example` aligned.
- Do not treat `recommender_model.pkl` as editable source.
- Do not skip error handling on external API calls.
- Do not block the main thread with long-running operations.

## Environment Variables
```bash
API_KEY=your-secret-key
FLASK_ENV=development
```

## Pre-PR Checks
```bash
python -m py_compile app.py server.py model.py train_job.py
```

## Cross-Service Notes
- Laravel polls `/health`, `/recommendations/*`, and `/train` via `backend/app/Services/RecommendationService.php`.
- Admin report endpoints in Laravel also export training data and trigger retraining around this service.
- Default port: 5011
