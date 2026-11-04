# RAFAIMMOBILIER branding

The application name, installer, documentation, starter database, and logo accessibility text use RAFAIMMOBILIER.

## Brand assets

- `public/assets/img/rafa-logo.png`: transparent navy and gold horizontal logo.
- `public/assets/img/rafa-logo-white.png`: transparent white and gold logo for dark backgrounds.
- `public/assets/img/rafa-favicon.png`: navy and gold house icon, also used for the preloader.
- Installer favicon variants are in `public/installer/img/favicon/`.

The logos were created with the built-in image generation tool. Design prompt: “RAFAIMMOBILIER real estate logo; architectural R monogram combining roofline and doorway; geometric sans serif wordmark; navy and warm gold; transparent background.” The footer variant uses white lettering.

## Installation

For a new installation, the bundled installer database includes the new title, logos, preloader, brand colors, and starter content. The original database has not been imported or reset as part of this rebrand.

For an existing installation, upload these files and run `public/installer/rafa-branding.sql` against the existing database, then run `php artisan optimize:clear`. The SQL changes branding fields only; it does not reset accounts, properties, or payment configuration. Existing contact details can be edited in Basic Settings.

Historical screenshots in the documentation are reference images. Third-party package names and author attribution remain their original names.

Runtime and browser verification requires a configured database and a completed installation.
