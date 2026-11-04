-- Apply branding to an existing installation without importing demo data.
START TRANSACTION;
UPDATE basic_settings SET
    website_title = 'RAFAIMMOBILIER',
    from_name = 'RAFAIMMOBILIER',
    logo = 'rafa-logo.png',
    logo_two = 'rafa-logo-white.png',
    footer_logo = 'rafa-logo-white.png',
    favicon = 'rafa-favicon.png',
    preloader = 'rafa-favicon.png',
    primary_color = 'C69E4C',
    secondary_color = '091F3A';
UPDATE footer_contents SET about_company = REPLACE(REPLACE(about_company, 'Estaty', 'RAFAIMMOBILIER'), 'estaty', 'RAFAIMMOBILIER');
UPDATE project_sections SET subtitle = REPLACE(subtitle, 'Estaty', 'RAFAIMMOBILIER');
UPDATE sliders SET title = REPLACE(REPLACE(title, 'Estaty', 'RAFAIMMOBILIER'), 'Estay', 'RAFAIMMOBILIER'), text = REPLACE(text, 'Estaty', 'RAFAIMMOBILIER');
UPDATE testimonials SET comment = REPLACE(REPLACE(comment, 'Estaty', 'RAFAIMMOBILIER'), 'estaty', 'RAFAIMMOBILIER');
COMMIT;
