# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Hotel booking platform built with CodeIgniter 2.1.4 (PHP MVC framework) that integrates with the Rezlive XML API for hotel search and booking functionality. Uses SQLite (via PDO) for city/country data storage.

## Common Commands

```bash
# Install PHP dependencies
composer install

# Run tests (PHPUnit)
./vendor/bin/phpunit

# The application is served via Apache/Nginx pointing to the project root
# Access at configured base_url (see application/config/config.php)
```

## Architecture

### MVC Structure (application/)
- **controllers/**: Request handlers
  - `Home.php` - Search page, city autocomplete endpoint
  - `Hotel.php` - Hotel details and room selection
  - `Booking.php` - Pre-booking confirmation
  - `Import.php` - Admin Excel import for city data
- **models/**: Data access layer
  - `City_model.php` - SQLite queries for city lookup
  - `Booking_model.php` - Session management for booking state
- **libraries/**: Business logic
  - `Rezlive_api.php` - API wrapper for Rezlive hotel service (XML request/response, file-based caching)
- **views/**: Templates using layouts/master.php with pages/ and partials/

### Key Design Patterns

**PRG (Post-Redirect-Get)**: Hotel and Booking controllers save POST data to session then redirect to GET. This prevents form resubmission.

**Session-Based State**: Booking data persists across pages via `Booking_model`. Keys managed centrally.

**API Wrapper**: All Rezlive API calls go through `Rezlive_api` library which handles XML building, file-based caching (1hr TTL), and error logging. Debug XML saved to `application/xml/`.

### Data Flow
1. **Search**: User enters city (autocomplete via AJAX to `home/autocomplete`) → Rezlive API search → hotel list
2. **Details**: Hotel selection → PRG redirect → Rezlive API for rooms → room selection
3. **Booking**: Room selection → PRG redirect → Rezlive pre-booking API → confirmation with booking key

### Database
SQLite via PDO at `application/database/database.sqlite` with tables:
- `cities` (id, name, city_code, country_code, country_name)
- `countries` (id, name, code)

City data imported via Excel using Import controller.

### Configuration
- `application/config/constants.php` - API credentials, default city/country, currency rate
- `application/config/database.php` - SQLite PDO connection, uses `$active_record = TRUE`
- `application/config/autoload.php` - Auto-loaded libraries: database, session, form_validation, rezlive_api
- `application/config/form_validation.php` - Validation rules for all forms

### Frontend
- Bootstrap 5, jQuery 3.6.1
- PickMeUp date picker (`assets/pickmeup/`)
- Custom JS in `assets/js/`: app.js, date-picker.js, trip-selector.js

### Currency
USD prices from API converted to NGN using fixed rate in constants (USD_TO_NGN_RATE). Display currency symbol: ₦

## CodeIgniter 2.1.4 Notes

- Uses Active Record instead of Query Builder (`$active_record = TRUE`)
- No `group_start()`/`group_end()` - use raw WHERE clauses for OR conditions
- Session config uses `sess_use_database`, `sess_match_useragent` instead of CI3's `sess_driver`
- File-based caching implemented in Rezlive_api (CI 2.1.4 doesn't have driver-based caching)
- Controllers require Composer autoloader for Carbon/PhpSpreadsheet dependencies
