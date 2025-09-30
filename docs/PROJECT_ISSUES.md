# Project Issues Audit

Below is a summary of high-impact problems discovered while reviewing the current code base.

## 1. Autoloader references a missing models directory
- `index.php` registers an autoloader that looks for classes under `core/`, `controllers/`, `models/`, `middleware/`, and `services/`.
- The repository does not contain a `models/` directory, so any attempt to load a model class will fail immediately.
- Evidence: `index.php` autoloader configuration and the absence of the directory when listing the project root.

## 2. Employee dashboard renders with undefined data
- `EmployeeController::profile()` calls `render('dashboard', …)` but never defines `$recentActivities`, so the dashboard view receives an undefined variable.
- `views/dashboard.php` also references `$user['user_name']` even though the view is rendered without a `$user` variable for most requests, producing notices and falling back to `__('common.user')` only after the notice is triggered.
- These notices surface whenever an employee opens their profile or dashboard.

## 3. Suggested content pulls exam records and generates broken links
- `EmployeeController::getSuggestedContent()` queries the `exams` table and returns exam IDs.
- The dashboard view assumes the records are pieces of content and builds URLs such as `/content/view/{id}`, so every suggested item points to a non-existent content page.
- This makes the “Suggested content” widget unusable and confuses users with 404 errors.

## 4. Reports export uses a non-existent column
- The reports controller constructs the points export SQL as `COALESCE(action, '')`, but the schema defines the column as `action_type`.
- Executing the reports page with the “points” section therefore throws a database error because `points_log.action` does not exist.

## 5. Notifications link points to an undefined route
- The dashboard view includes a “View all notifications” link that directs users to `/notifications`.
- `routes.php` has no route definition for `/notifications`, so the link always returns 404 even though a dedicated notifications view exists.

These issues should be prioritised because they either break core navigation flows or raise errors in normal usage.
