# DevPulse API - Architecture Reference

## Purpose

هذا الملف هو المرجع المعماري الرئيسي للمشروع. يحفظ تصميم النظام،
القرارات الهندسية، وقواعد التطوير.

## Project Identity

Name: DevPulse API

Type: RESTful Laravel API

Goal: بناء Backend احترافي قابل للتوسع يخدم Web و Mobile clients.

## Architecture Style

Modular Monolith

Reason: - مناسب للتعلم الاحترافي. - سهل الصيانة. - قابل للتوسع لاحقاً.

## System Flow

Client \| Laravel API \| Middleware \| Controller \| Service Layer \|
Models \| Database \| JSON Response

## Application Layers

### Routes

-   API endpoints
-   Versioning

### Middleware

-   Authentication
-   Rate Limiting
-   CORS

### Controllers

قاعدة: Thin Controllers

ممنوع وضع Business Logic داخل Controller.

### Form Requests

-   Validation
-   Input rules

### Services

مكان Business Logic.

Examples: - PostService - UserService

### Models

Entities: - User - Post - Category - Tag - Comment

### Resources

مسؤول عن JSON Response.

## Domain Relations

User: - hasMany Posts - hasMany Comments

Post: - belongsTo User - belongsTo Category - belongsToMany Tags -
hasMany Comments

Category: - hasMany Posts

Tag: - belongsToMany Posts

Comment: - belongsTo User - belongsTo Post

## Development Order

1.  Requirements
2.  Architecture
3.  Setup
4.  Database
5.  Authentication
6.  Authorization
7.  Core Modules
8.  Files
9.  Search
10. Testing
11. Documentation
12. Deployment

## Rules

كل Feature:

Requirement -\> Design -\> Database -\> Implementation -\> Testing -\>
Documentation

Rules: - لا كود قبل التصميم. - لا Logic داخل Controllers. - أي قرار
معماري يسجل.

## Architecture Decisions

ADR-001: Use Laravel Sanctum.

Reason: Token authentication مناسب للـ API.

ADR-002: Use Modular Monolith.

Reason: أفضل للتعلم والصيانة.

## Current State

Phase: 5 - Tags Module

Completed: User Registration, User Login, User Logout, Authenticated User, Authorization & Policies, Categories CRUD, Posts CRUD & Image Uploads, Tags CRUD & Many-to-Many Relationships (Phase 5 Complete! ✅)

Next: Phase 6 - Feature 6.1: Comments CRUD
