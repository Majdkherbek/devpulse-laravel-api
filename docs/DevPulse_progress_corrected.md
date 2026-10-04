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

### Feature 4.1 Posts CRUD

Status: Pending

Topics:
- Create Post
- Update Post
- Delete Post
- Show Post
- List Posts
- Category Relationship
- Validation
- API Resources
- Testing

---

### Feature 4.2 Post Images

Status: Pending

Topics:
- Upload Image
- Replace Image
- Delete Image
- File Validation
- Storage
- Testing

---

### Feature 4.3 Pagination

Status: Pending

Topics:
- Pagination
- Sorting
- Filtering
- Performance

________________________________________________

## Phase 5 - Tags

### Feature 5.1 Tags CRUD

Status: Pending

Topics:
- Create Tag
- Update Tag
- Delete Tag
- List Tags
- Validation
- Testing

---

### Feature 5.2 Post Tags

Status: Pending

Topics:
- Attach Tags
- Sync Tags
- Detach Tags
- Many-to-Many Relationship
- Testing

________________________________________________

## Phase 6 - Comments

### Feature 6.1 Comments CRUD

Status: Pending

Topics:
- Create Comment
- Update Comment
- Delete Comment
- List Comments
- Validation
- Testing

---

### Feature 6.2 Comment Authorization

Status: Pending

Topics:
- Comment Ownership
- Policies
- Testing

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

## Phase 8 - API Quality

### Feature 8.1 API Resources

Status: Pending

Topics:
- Resource Collections
- Resource Responses
- Response Standardization

---

### Feature 8.2 Error Handling

Status: Pending

Topics:
- Validation Errors
- Authentication Errors
- Authorization Errors
- Exception Handling

---

### Feature 8.3 API Standards

Status: Pending

Topics:
- HTTP Status Codes
- REST Best Practices
- JSON Response Structure

________________________________________________

## Phase 9 - Testing

### Feature 9.1 Feature Testing

Status: Pending

### Feature 9.2 Unit Testing

Status: Pending

### Feature 9.3 API Testing

Status: Pending

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
