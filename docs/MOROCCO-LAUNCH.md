# Lancement au Maroc ? RAFAIMMOBILIER

## Installation du profil

Le code du site se trouve dans `github-estaty`. Les autres dossiers sont des
copies installables ou des exports et ne sont pas automatiquement synchronis?s.
Sauvegarder la base et les fichiers avant toute mise ? jour du site existant.
Dans Administration > Param?tres de base > Param?tres g?n?raux, cliquer sur
? Configurer les villes du Maroc ?. Cette action authentifi?e importe les localit?s
et active les r?glages sans terminal.

Autre possibilit?, depuis le dossier du site, avec ses d?pendances Composer et sa configuration :

```sh
php artisan rafa:morocco --apply-defaults --enable
php artisan config:clear
php artisan view:clear
```

Cette commande ajoute les 12 r?gions et 1 503 communes du r?f?rentiel HCP RGPH
2024, avec les noms fran?ais et arabes. Les communes incluent les localit?s rurales ;
ce r?f?rentiel ne repr?sente pas tous les quartiers ni tous les douars.
Elle conserve les annonces et les montants existants. V?rifier les prix avant
activation : changer la devise en MAD ne convertit pas les anciens montants.
Les annonces internationales conserv?es sont masqu?es par le profil Maroc.
Les anciens projets doivent recevoir une commune pour appara?tre dans ce catalogue.
Les choix de villes utilisent les communes ; les provinces servent ? distinguer
les noms identiques, sans ajouter un nouvel ?cran de s?lection des provinces.

Le profil configure MAD/DH, les surfaces en m? et Africa/Casablanca. Les formulaires
affichent un exemple de t?l?phone +212 et un code postal de cinq chiffres.
Ces indications de saisie ne remplacent pas une v?rification des coordonn?es.
Ne pas inventer d'adresse, de t?l?phone, d'identifiants l?gaux ou de coordonn?es bancaires.

## Informations ? renseigner dans l'administration

- Raison sociale, nom commercial, adresse r?elle, t?l?phone +212, WhatsApp et email.
- Identifiants de l'entreprise applicables (ICE, RC, IF) et responsable du site.
- Tarifs d'abonnement en MAD, dur?e, prestations incluses, renouvellement et annulation.
- Mentions l?gales, conditions d'utilisation, confidentialit? et conditions de vente
  adapt?es ? l'activit? r?elle. Faire valider les documents avant publication.
- Politique de mod?ration des annonces, v?rification des professionnels et traitement
  des signalements ; supprimer ou d?sactiver les contenus de d?monstration apr?s examen.
- Textes et menus fran?ais/arabe, affichage RTL et emails dans chaque langue.

## Paiement, email et h?bergement

Choisir un prestataire compatible avec l'entreprise et les r?glements en MAD,
puis obtenir ses identifiants marchands. Aucun contrat marchand ni paiement r?el
n'est cr?? par ce changement. Ne pas activer un moyen de paiement avant d'avoir test?
un abonnement, une notification de paiement et un remboursement.
Configurer un fournisseur SMTP et tester inscription, r?cup?ration de mot de passe
et demandes de contact avec une vraie bo?te de r?ception.
Configurer domaine, HTTPS, sauvegardes et restauration ; utiliser APP_DEBUG=false,
une cl? APP_KEY priv?e et un mot de passe administrateur unique en production.
V?rifier la licence commerciale du logiciel et la provenance de ses d?pendances.

## D?marches de l'entreprise et donn?es personnelles

La cr?ation de l'entreprise et les formalit?s correspondantes se pr?parent sur
[DirectEntreprise](https://www.directentreprise.ma/).
Pour les comptes, demandes de contact et coordonn?es des annonceurs, d?terminer
les formalit?s CNDP applicables, les destinataires et les ?ventuels transferts ?
l'?tranger selon l'h?bergement et les fournisseurs choisis. Pr?voir les informations
? la collecte et les modalit?s d'exercice des droits avant ouverture au public.
R?f?rences officielles : [CNDP ? FAQ](https://www.cndp.ma/faq/) et
[CNDP ? textes et lois](https://www.cndp.ma/textes-et-lois/).
Ces d?marches d?pendent de l'entreprise et ne sont pas effectu?es par le code.

## Recette avant ouverture

1. Rechercher Casablanca, Rabat et une petite commune en fran?ais puis en arabe.
2. Publier et modifier une annonce depuis les comptes administrateur, agence et agent.
3. V?rifier r?gion/commune, MAD, m?, photos, carte, contact et affichage sur mobile.
4. V?rifier qu'une commune d'une autre r?gion est refus?e et que les annonces
   internationales ne sont pas visibles dans le catalogue Maroc.
5. Tester les emails, les offres et les paiements configur?s, puis une restauration.

Test d'int?gration isol?, avec PHP pdo_sqlite et les d?pendances Composer install?es :

```sh
php tests/Integration/morocco-market.php
```

Le test cr?e une base SQLite en m?moire ; il ne modifie pas la base du site.
Il contr?le le nombre de r?gions/communes, les libell?s bilingues, une seconde
importation, la r?paration des libell?s manquants, une langue ajout?e et les r?gles
de validation des localisations. Les parcours navigateur, le paiement et la mise
en production restent ? v?rifier sur l'h?bergement cible.
