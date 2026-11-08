# Morocco market

The market profile uses the HCP RGPH 2024 territorial reference: 12 regions and
1,503 communes with French and Arabic names. Communes with repeated names are
disambiguated using their province. Geographic codes provide stable import keys.
Source: https://www.hcp.ma/file/242341/

`php artisan rafa:morocco` imports/upserts this reference without deleting existing
locations, listings, users, or memberships. An enabled profile limits location
choices and listings to Morocco. Switching the profile off in General Settings
restores access to previously stored international locations. Existing monetary
amounts are preserved; no automatic currency conversion is performed.

The Moroccan setup uses MAD/DH, Africa/Casablanca, +212, and square metres.
General Settings exposes the profile switch and the usual currency/timezone fields.
New projects also select a Moroccan commune; their country and region are derived
on the server. Existing projects require a locality before entering the Moroccan
catalogue. Original records are retained.

## Validation review scope

PHP/Laravel: `MoroccoMarket`, the property/project FormRequests and project
controllers validate user-supplied country/region/city identifiers. Relevant ASVS
sections are V4 (existing authenticated role routes) and V5 (identifier validation,
parameterized database queries, and escaped Blade labels). HCP import data is a
separate administrative trust boundary. No new public write route is introduced.
Regression checks must reject a city from another region/country and verify that
existing records remain unchanged after repeated imports.
