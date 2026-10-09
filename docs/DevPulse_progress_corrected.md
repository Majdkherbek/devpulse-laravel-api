# DevPulse API - Training Progress

## Goal

الوصول إلى مستوى Laravel Backend Engineer احترافي من خلال بناء مشروع
حقيقي.

## Phase 0 - Analysis

### Lesson 0.1 Requirement Analysis

Status: Completed

Completed:
- Reviewed Client Request.
- Reviewed SRS.
- Reviewed Requirement Analysis.
- Identified Actors.
- Identified Features.
- Identified Business Rules.
- Confirmed Project Scope.
- Approved SRS as Source of Truth.

---

### Lesson 0.2 Domain Modeling

Status: Completed

Completed:
- Identified all domain entities.
- Reviewed relationships between entities.
- Validated the ERD against project requirements.
- Distinguished Domain Model from Database Design.
- Understood Category vs Tag responsibilities.

---

### Lesson 0.3 API Architecture

Status: Completed

Completed:
- Understood REST principles.
- Reviewed API Versioning strategy (`/api/v1`).
- Studied Laravel Request Lifecycle.
- Understood the responsibility of each layer:
  - Routes
  - Middleware
  - Form Requests
  - Controllers
  - Services
  - Models
  - API Resources
- Confirmed Thin Controller architecture.
- Confirmed Service Layer as the location for Business Logic.

---

Phase 0 Status: ✅ Completed

Next Phase:
➡️ Phase 1 - Foundation

Knowledge Check:
- ✓ Can explain Domain Model.
- ✓ Can identify entity relationships.
- ✓ Understands Request Lifecycle.
_____________________________________________
# Phase 1 - Foundation

## Lesson 1.1 Project Setup

Status: Completed

Completed:

### Laravel Installation & Environment
- Created Laravel 12 project using Composer.
- Explored Laravel project structure.
- Understood the purpose of:
  - app/
  - config/
  - vendor/
  - artisan
- Understood Composer workflow:
  - composer.json
  - composer.lock
  - vendor
- Configured the .env file.
- Connected the application to MySQL database.
- Executed initial migrations successfully.
- Verified the application runs correctly.

### Database Foundation
- Understood Laravel Migration concepts:
  - Purpose of migrations.
  - up() and down() methods.
  - Database schema versioning.
- Understood the difference between:
  - Migration responsibility.
  - Model responsibility.
- Understood database relationships:
  - One To Many.
  - Many To Many.
  - Foreign keys.
  - Pivot tables.
- Reviewed DevPulse database design:
  - Category → Posts.
  - Post → Comments.
  - Post ↔ Tags.

### Git Foundation
- Initialized Git repository.
- Understood:
  - Repository concept.
  - .git directory.
  - Working Directory.
  - Staging Area.
  - Commit history.
- Learned Git workflow:
  - git status
  - git add
  - git commit
  - git log
- Created initial project commits:
  - Initial Laravel project setup.
  - Update project README.
- Understood the purpose of commits as project history checkpoints.

Knowledge Check:

✓ Understands Laravel project structure.
✓ Understands Composer dependency management.
✓ Understands migrations and database versioning.
✓ Understands difference between Migration and Model.
✓ Understands basic database relationships.
✓ Understands Git repository workflow.
✓ Can create and read project commits.

---

Lesson 1.2 Project Structure

Part 1: Laravel Layers
Status: Completed

Completed:
✓ app directory
✓ Models
✓ Controllers
✓ Requests
✓ Resources
✓ Policies
✓ Database relationship placement


Part 2: Application Flow

Status: Completed

Completed:

✓ Understood Laravel request lifecycle.
✓ Understood application entry point.
✓ Understood Route responsibility.
✓ Understood Middleware role.
✓ Understood Controller responsibility.
✓ Understood Validation flow using Form Requests.
✓ Understood Resource transformation.
✓ Understood separation of responsibilities between layers.

Knowledge Check:

✓ Can explain request flow from Client to Response.
✓ Can explain Middleware purpose.
✓ Can explain why Validation is separated.
✓ Can explain the role of API Resources.


Part 3: Service Architecture
Status: Completed

Topics:
✓ Service layer
✓ Business logic separation
✓ When to use Services

Current Phase:

Phase 1 - Foundation

Topics:

✓ Laravel setup
✓ Environment
✓ Git
✓ Database
✓ Application Architecture
________________________________________________
________________________________________________

## Phase 2 - Identity & Access

## Feature 2.1 User Registration

Status: Completed

Topics:
- ✅ Registration Flow
- ✅ Register Endpoint
- ✅ Form Request Validation
- ✅ Password Hashing
- ✅ User Creation
- ✅ Sanctum Token Generation
- ✅ API Response
- ✅ Testing

---
### Feature 2.2 User Login

Status: Completed

Topics:
- ✅ Authentication Flow
- ✅ Login Endpoint
- ✅ Credentials Validation
- ✅ Token Creation
- ✅ Invalid Credentials Handling
- ✅ Testing

---

### Feature 2.3 User Logout

Status: Completed

- ✅ Logout Current Device
- ✅ Logout All Devices
- ✅ Token Revocation
- ✅ Testing

---

### Feature 2.4 Authenticated User

Status: Completed

Topics:
- ✅ Protected Routes
- ✅ auth:sanctum Middleware
- ✅ Current Authenticated User using UserResource
- ✅ Testing in Bruno (200 OK & 401 Unauthorized)

---

### Feature 2.5 Authorization

Status: Completed

Topics:
- ✅ Policies (PostPolicy & CommentPolicy)
- ✅ Ownership Rules ($user->id === $model->user_id)
- ✅ Admin Authorization ($user->is_admin override via before() method)
- ✅ Testing

________________________________________________

## Phase 3 - Categories

### Feature 3.1 Categories CRUD

Status: Completed

Topics:
- ✅ Create Category (Admin Only)
- ✅ Update Category (Admin Only)
- ✅ Delete Category (Admin Only)
- ✅ List Categories & Show Category (Public)
- ✅ Validation (StoreCategoryRequest & UpdateCategoryRequest)
- ✅ API Resources (CategoryResource)
- ✅ Auto-slug generation on Model

________________________________________________

## Phase 4 - Posts

### Feature 4.1 Posts CRUD & Images

Status: Completed

Topics:
- ✅ Create Post (Authenticated Users)
- ✅ Update Post (Owner / Admin Only via Policy)
- ✅ Delete Post (Owner / Admin Only via Policy)
- ✅ Show Post & List Posts with Pagination (Public)
- ✅ Upload, Replace & Delete Post Images (Storage::disk('public'))
- ✅ Filtering & Search (by category_id & title search)
- ✅ Validation (StorePostRequest & UpdatePostRequest)
- ✅ API Resources (PostResource & PostCollection)

________________________________________________

## Phase 5 - Tags

### Feature 5.1 Tags CRUD & Post Tags

Status: Completed

Topics:
- ✅ Create Tag (Admin Only)
- ✅ Update Tag (Admin Only)
- ✅ Delete Tag (Admin Only)
- ✅ List Tags & Show Tag (Public)
- ✅ Validation (StoreTagRequest & UpdateTagRequest)
- ✅ API Resources (TagResource & TagCollection)
- ✅ Many-to-Many Relationship (post_tag pivot table & sync/attach)

________________________________________________

## Phase 6 - Comments

### Feature 6.1 Comments CRUD & Authorization

Status: Completed

Topics:
- ✅ Create Comment on Post (Authenticated Users)
- ✅ Update Comment (Owner / Admin Only via CommentPolicy)
- ✅ Delete Comment (Owner / Admin Only via CommentPolicy)
- ✅ List Comments for a Post (Public Read)
- ✅ Validation (StoreCommentRequest & UpdateCommentRequest)
- ✅ API Resources (CommentResource)
- ✅ Comment Ownership & Policies

________________________________________________

## Phase 7 - Search

### Feature 7.1 Search & Filtering

Status: Pending

Topics:
- Search
- Filtering
- Sorting
- Pagination
- Performance
- Testing

________________________________________________

## Phase 8 - API Quality & Error Handling

### Feature 8.1 API Quality & Global Exception Handling

Status: Completed

Topics:
- ✅ Standardized API Resources & Collections
- ✅ Global Exception Handling in bootstrap/app.php
- ✅ 404 Resource Not Found JSON Handler
- ✅ 401 Unauthenticated & 403 Forbidden Handlers
- ✅ 422 Unprocessable Content Validation Errors
- ✅ HTTP Status Codes & REST Best Practices

________________________________________________

## Phase 9 - Automated Testing

### Feature 9.1 Feature & Unit Testing

Status: Completed

Topics:
- ✅ Auth Feature Tests (Register, Login, User Profile)
- ✅ Category Feature Tests (Public List, Admin Create, 403 Normal User)
- ✅ Post Feature Tests (Create, Update Policy 403/200, Search & Filter)
- ✅ Comment Feature Tests (Public List, Create, Policy 403 Unauthorized Edit)
- ✅ Tag Feature Tests (Public List, Admin Create, 403 Normal User)
- ✅ SQLite In-Memory Database Testing (Fast 1-second execution)

________________________________________________

## Phase 10 - Deployment

### Production Deployment

Status: Pending

Topics:
- Production Environment
- Configuration
- Cache
- Queues
- Logging
- Optimization


### Trainer Notes
## Completed

- Completed User Registration feature.
- Completed User Login feature.
- Completed User Logout feature.
- Completed Authenticated User feature (Feature 2.4).
- Fixed double password hashing in AuthService register method.
- Created UserResource for explicit field mapping (id, name, email, created_at).
- Refactored `/api/user` route from Closure to AuthController@user inside protected Sanctum middleware group.
- Verified Current Device Logout using Bruno.
- Verified Logout All Devices using multiple Sanctum tokens and Bruno.
- Verified Token Revocation by reusing revoked tokens and receiving `401 Unauthorized`.
- Verified Authenticated User endpoint using Bruno with Bearer Token (200 OK & 401 Unauthorized).
- Understood the difference between Validation and Business Logic.
- Learned Dependency Injection in Controllers.
- Learned Hash::make() vs Hash::check().
- Learned Sanctum Token generation.
- Tested API endpoints using Bruno.
