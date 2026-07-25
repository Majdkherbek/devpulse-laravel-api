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

# Current Status

Phase 1 Foundation:

Completed:
✓ Setup
✓ Environment
✓ Database basics
✓ Git basics
✓ Project Structure
✓ Application Flow
✓ Service Architecture


Next:

Phase 2:
Core Development

First tasks:
- Review existing Models.
- Review migrations.
- Verify relationships.
- Start API implementation.