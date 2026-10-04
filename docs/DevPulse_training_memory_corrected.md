# DevPulse Training Memory

## Project Context

Project:
DevPulse API

Purpose:
Professional Laravel 12 learning project.

Coach Role:
Guide the developer through professional Laravel architecture and workflow.

---

# Completed Learning

## Laravel Foundation

Completed:
- Laravel 12 installation using Composer.
- Composer workflow understood:
  - composer.json
  - composer.lock
  - vendor

- Laravel structure understood:
  - app/
  - config/
  - database/
  - routes/
  - storage/
  - artisan

---

# Database Knowledge

Understood:

Migration:
- Responsible for database schema.
- Creates tables and columns.
- up() creates changes.
- down() reverses changes.

Model:
- Represents database entities.
- Contains relationships.

Relationships:

Category -> Posts:
One To Many

Post -> Comments:
One To Many

Post <-> Tags:
Many To Many

Pivot:
post_tag

---

# Laravel Architecture

Application Flow:

Request
 ↓
Route
 ↓
Middleware
 ↓
Form Request
 ↓
Controller
 ↓
Service
 ↓
Model
 ↓
Database
 ↓
Resource
 ↓
Response


Responsibilities:

Controller:
- Receives request.
- Coordinates execution.
- Should remain lightweight.

Request:
- Validation rules.

Service:
- Business Logic.

Model:
- Data representation.
- Relationships.

Resource:
- API response formatting.

---


# Git Knowledge

Repository initialized.

Commits:

1.
Initial Laravel project setup

2.
Update project README

Understood:

Working Directory
↓
Staging Area
↓
Commit

Commands learned:

git init
git status
git add
git commit
git log

Branches postponed until needed.

---
# Authentication Knowledge

Authentication Flow:

Client
 ↓
Route
 ↓
Form Request
 ↓
Controller
 ↓
Service
 ↓
Model
 ↓
Database
 ↓
Response

Completed:

- Implemented User Registration.
- Implemented User Login.
- Created RegisterRequest.
- Created LoginRequest.
- Built AuthController.
- Built AuthService.
- Used Dependency Injection for services.
- Used User::create() for registration.
- Used User::where()->first() for user lookup.
- Learned Password Hashing using Hash::make().
- Learned Password Verification using Hash::check().
- Generated Sanctum Personal Access Tokens.
- Understood that each login creates a separate Personal Access Token.
- Understood the `personal_access_tokens` table and the relationship between a user and multiple tokens.
- Implemented Logout Current Device using the current access token.
- Implemented Logout All Devices by deleting all tokens belonging to the user.
- Understood Token Revocation.
- Tested registration, login, authenticated access, current-device logout, and all-devices logout using Bruno.

Concepts Learned:

Validation
- Responsible for validating request format only.

Business Logic
- Lives inside Services.
- Responsible for authentication decisions.

Controller
- Coordinates execution only.
- Returns HTTP responses.
- Contains no business logic.

Sanctum
- Generates Personal Access Tokens.
- Allows stateless API authentication.
- Each login creates a new token.

Error Handling

- Validation errors belong to Form Requests.
- Authentication errors belong to AuthService.
- Login should return generic "Invalid credentials" message.

# Current Status

Phase 2 Identity & Access:

Completed:
✓ Feature 2.1 User Registration
✓ Feature 2.2 User Login
✓ Feature 2.3 User Logout
✓ Feature 2.4 Authenticated User (UserResource & AuthController@user)

Next

Feature 2.5
Authorization & Policies

Upcoming Topics

- Policies
- Ownership Rules
- Admin Authorization
- Testing
