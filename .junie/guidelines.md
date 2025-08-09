# Pantono Product Module

## Project Overview

This is a composer module to be used as one part of an overall project. This module is for managing products in an
ecommerce context.

The general concept of this module is that all products have versions. Each version will have it's own state, e.g. a
newer version of a product may be awaiting review which would mean the previous version is currently live.

No endpoints will be used as part of this project as it is a module by which methods will be called to then transform
accordingly.

## Project Structure

- `conf` - Configuration files, services and event listeners are defined here
- `src` - Main project source code
- `migrations` - Database migrations folder
- `vendor` - External modules

## Database migrations

Database migrations are stored in the migrations directory. This will contain a full history of all schema changes
required for products.
