# Client experience refresh

The public header now leads with Rent, Buy, New developments and Contact, with a
direct Publish a property button and one account menu. Professional login is
available inside that menu. Existing menu records are retained in the database;
the main header uses these four fixed links to avoid demo theme links and nested
page menus. Footer links remain available for supporting pages.

Homepage themes 1 and 3 share a new discovery section: a readable introduction,
rent/buy tabs, searchable Moroccan cities, property categories and a search button.
Budget and surface filters remain on the results page. The initial search does
not send artificial zero-price bounds when there are no listings. The original
hero image and configured title are retained. Mobile prioritizes the search form.

Results now explain how to search and offer reset/contact actions when no property
matches. An empty result set hides the otherwise empty map. French and Arabic
translations cover the new guidance and actions.

Admin, professional and agent dashboards offer shortcuts to add a property,
manage listings and read inquiries. Customer accounts offer property search,
saved properties and profile editing. Restricted administrator roles retain
their existing permission checks; owner shortcuts are shown only to the owner.
The sidebar uses translations instead of hardcoded English where applicable.

The visual layer is `public/assets/css/rafa-experience.css`, loaded with an asset
version in the three public layouts and three workspace layouts. No migration,
payment configuration or listing-data change is needed for this refresh.

The original professional signup, login, password-reset and directory templates
are restored from the supplied installable project. Account forms now have
visible labels and appropriate email/autocomplete inputs. InfinityFree routing
sends virtual `/vendor/` application URLs to Laravel while continuing to deny
real Composer dependency files and directories. This resolves the pre-existing
403 responses on professional signup/login.

Verification: changed Blade templates compile, the discovery section renders,
and shortcuts render for all four audiences with fixture data. Live browser
checks cover public French/Arabic pages, rent/buy search parameters, mobile
navigation, responsive overflow, administrator shortcuts and console errors.
Vendor, agent and customer authenticated workflows require their respective
accounts for a full production walkthrough.
