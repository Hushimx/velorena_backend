# Test Files Directory

This directory contains all test files and debugging scripts organized by category.

## Directory Structure

### `/api/`

Contains API testing scripts:

-   `test_appointments_api.php` - Test appointment API endpoints
-   `test_designs_api.php` - Test design API endpoints
-   `test_orders_api.php` - Test order API endpoints
-   `test_appointment_creation.php` - Test appointment creation functionality
-   `test_enhanced_appointment_creation.php` - Enhanced appointment creation tests
-   `test_design_selection.php` - Test design selection functionality
-   `test_design_display.php` - Test design display functionality
-   `test_design_selection_page.blade.php` - Blade template for design selection testing
-   `test_livewire_design_sync.php` - Test Livewire design synchronization
-   `test_smart_order_suggestion.php` - Test smart order suggestion features

### `/debug/`

Contains debugging scripts:

-   `debug_design_saving.php` - Debug script for design saving issues

### `/blade/`

Contains Blade template test files (currently empty)

## Usage

These files are for development and testing purposes only. They should not be deployed to production.

## Cleanup

To remove all test files:

```bash
rm -rf test-files/
```

## Notes

-   All test files have been moved from the root directory to maintain a clean project structure
-   These files can be safely deleted if no longer needed
-   Consider adding test files to `.gitignore` if they contain sensitive data
