# French default and Arabic switching

French (`fr`, left-to-right) is the default website language. Visitors can select Arabic (`ar`, right-to-left) using the header language selector or its mobile-menu equivalent. Their explicit choice persists in the session. English remains available as an additional language.

The French interface dictionary covers every original interface key, plus the new homepage/account controls. The Arabic dictionary includes the newly added controls. French content records were added for homepage sections, menus, headings, categories, and existing listings. Original listing descriptions, personal names, addresses, and authored material without an explicit translation are preserved for editorial translation in the admin panel.

The previously malformed Arabic menu was repaired, with its original data backed up in `storage/app/arabic-menu-before-repair-<id>.json`. The missing `rtl-responsive.css` stylesheet is now included. Header templates also tolerate invalid menu JSON instead of throwing a server error.

For an existing deployment, upload the changes and run:

```sh
php artisan rafa:languages
php artisan optimize:clear
```

The command preserves existing French records on repeat runs. It copies language-specific blog categories before blog information and remaps their references. The bundled installer SQL already includes the new French records, default-language setting, and repaired Arabic menu. The optional `--export-starter` flag rebuilds that installer appendix from a prepared development database; it is intended for source packaging.

Validation covered French as the default for fresh visitors, real French property searches, switching to Arabic and back, persistence through multiple routes, RTL assets and menus, and layout widths 390, 768, 1280, and 1440 in both languages. No browser exceptions or failed local assets occurred during the final check.
