# Summary of Recent Changes

- Added a `TranslationService` that proxies Google Translate, caches results, and chunks long bodies so database content is localized according to the active site language before rendering. This service is reused by controllers when preparing responses. [See `services/TranslationService.php`](../services/TranslationService.php).
- Updated `EmployeeController` to fetch real statistics, activity feeds, notifications, and suggested content while translating user-facing fields to the visitor's locale. [See `controllers/EmployeeController.php`](../controllers/EmployeeController.php).
- Updated `ContentController` to translate featured cards, detailed content bodies, and search results that originate in Arabic so English pages show localized text. [See `controllers/ContentController.php`](../controllers/ContentController.php).
- Added a dedicated `NotificationsController` with list, read, unread checks, and delete endpoints protected by route guards to expose localized notification data. [See `controllers/NotificationsController.php`](../controllers/NotificationsController.php).
- Corrected the reports export to use the existing `action_type` column, preventing SQL errors when generating the points report. [See `controllers/ReportsController.php`](../controllers/ReportsController.php).
- Documented outstanding functional gaps discovered during review in `docs/PROJECT_ISSUES.md`.
