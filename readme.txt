=== BizCalendar Web ===
Contributors: setriosoft, thundorstorm
Tags: setrio bizmedica bizcalendar online appointments programari
Requires at least: 3.3
Tested up to: 6.6.1
Requires PHP: 5.3.0
Stable tag: 1.1.0.31
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Modul de programări online pentru clinicile medicale care folosesc BizMedica / Online appointments form for medical clinics using BizMedica software

== Description ==

= Romana =

Acest modul permite afisarea in orice site WordPress a unui formular prin care se pot face programari online pentru o clinica medicala care foloseste aplicatia BizMedica (http://www.setrio.ro/bizmedica/).

Pentru ca modulul sa functioneze corect, acesta transfera toate datele introduse in formular catre un serviciu web extern inclus in aplicatia BizMedica, care este gazduit pe serverul clinicii medicale. De asemenea, el preia date despre medici, specialitati medicale, intervale orare disponibile, etc. din acest serviciu. Toata comunicatia intre serverul WordPress si serviciul extern BizMedica se face criptat, prin protocolul HTTPS.

= English =

This plugin allows you to display a form on any WordPress site which allows you to make online appointments for any medical clinic using BizMedica software solutions (http://www.setrio.ro/bizmedica/).

In order for the correct function of the plugin, it transfers all the data entered into the form to an external web service included in the BizMedica application, which is hosted on the medical clinic server. It also downloads data about physicians, medical specialties, available time slots, etc. from this external service. All communication between the WordPress server and the BizMedica external service is encrypted through the HTTPS protocol.

== Installation ==

= Romana =

1. Copiați directorul `bizcalendar-web` în locația `/wp-content/plugins/`
2. Activați modulul prin intermediul meniului "Module" din WordPress
3. Folosiți opțiunea de meniu "BizCalendar" pentru a configura opțiunile modulului
4. Adăugați codul [bizcal] pe pagina sau postarea pe care doriți să apară formularul de programări

= English =

1. Upload `bizcalendar-web` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use "BizCalendar" admin menu option to configure the plugin
4. Add the shortcode [bizcal] on the desired page/post

== Frequently asked questions ==

= Is the plugin free? =

The plugin can be downloaded and installed for free on any server. However, it requires a valid username and password obtained from the medical clinic in order to allow connections to the BizMedica service.

== Screenshots ==
1. Selectare servicii si medic dorit / Select desired physician and services

== Changelog ==

= 1.1.0.31 =
  * RO: Fix requests, fix form change error
  * EN: Fix requests, fix form change error
= 1.1.0.30 =
  * RO: Fix timezone, fix log search
  * EN: Fix timezone, fix log search
= 1.1.0.29 =
  * RO: ?? Fix call
  * EN: ?? Fix call
= 1.1.0.28 =
  * RO: ?? Modificari pentru acceptare plugin
  * EN: ?? Changes for Plugin acceptance
= 1.1.0.27 =
  * RO: ?? Modificat tested upto
  * EN: ?? Changed tested upto
= 1.1.0.26 =
  * RO: ?? Modificari pentru acceptare plugin
  * EN: ?? Changes for Plugin acceptance
= 1.1.0.25 =
  * RO: Fix readme conform cerinte wordpress
  * EN: Fix readme by wordpress requirements
= 1.1.0.24 =
  * RO: Fix preplata medic-serviciu
  * EN: Fix physician-service onlinepay
= 1.1.0.23 =
  * RO: Fix preplata medic-serviciu
  * EN: Fix physician-service onlinepay
= 1.1.0.22 =
  * RO: Fix preplata medic-serviciu
  * EN: Fix physician-service onlinepay
= 1.1.0.21 =
  * RO: Iconite si loader ajutatoare pentru popup mobil
  * EN: Helper info icons an loader for mobile popup
= 1.1.0.20 =
  * RO: Fix texte pentru plata
  * EN: Fix Texts for payment
= 1.1.0.19 =
  * RO: Fix Pagina status plata
  * EN: Fix Payment status page
= 1.1.0.18 =
  * RO: Fix Logging
  * EN: Fix Logging
= 1.1.0.17 =
  * RO: Plata Netopia Mobilpay
  * EN: Netopia Mobilpay payments
= 1.1.0.16 =
  * RO: Actualizat link logo biz-medica logo
  * EN: Updated biz-medica logo link
= 1.1.0.15 =
  * RO: CC la eroare programare
  * EN: CC on appointment error
= 1.1.0.14 =
  * RO: extra info loguri
  * EN: logs extra
= 1.1.0.13 =
  * RO: loguri
  * EN: logs
= 1.1.0.12 =
  * RO: fix js pentru rezervare singura locatie
  * EN: fix for single location appointment
= 1.1.0.11 =
  * RO: fix pentru forbidden 403 call.php
  * EN: fix for forbidden 403 call.php
= 1.1.0.10 =
  * RO: fix pentru wp6.3
  * EN: fix for wp6.3
= 1.1.0.9 =
  * RO: fix pentru tabela loguri cu mesaj null
  * EN: fix for logs table null message
= 1.1.0.8 =
  * RO: fix for php8
  * EN: fix pentru php8
= 1.1.0.7 =
  * RO: fix purificator elementor ce modifica elementele vue
  * EN: fix elementor purifier altering vue elements
= 1.1.0.6 =
  * RO: fix lista medici per locatie aleasa
  * EN: fix physicians list in location
= 1.1.0.5 =
  * RO: validare telefon 10-15 cifre, email cu cel putin 2 litere domeniu principal
  * EN: phone validation 10-15 digits, email with at least 2 letters as main domain
= 1.1.0.4 =
  * RO: fix programare vue singura locatie
  * EN: fix vue single-location appointment
= 1.1.0.3 =
  * RO: culori disponibilitati in calendar, alegere orice locatie, medici in functie de specialitate, upgrade versiune vuetify, progres in pasi popup, fix atribute vue-html pentru anumite teme wp
  * EN: color-coded availabilities in calendar, any-location choice, physicians by speciality, upgraded vuetify, step progress in popup, fixed vue-html attributes for some wp themes
= 1.1.0.2 =
  * RO: fix ordine incarcare script recaptcha
  * EN: fix recaptcha script loading order
= 1.1.0.1 =
  * RO: fix admin redirect
  * EN: fix admin redirect
= 1.1.0.0 =
  * RO: fix data minima calendar
  * EN: minimim date datepicker fix
= 1.0.9.9 =
  * RO: cateva fixuri, aspect buton popup
  * EN: small fixes, popup button appearence
= 1.0.9.8 =
  * RO: cateva fixuri
  * EN: small fixes
= 1.0.9.7 =
  * RO: cateva fixuri, text personalizabil pentru modalitatea de plata
  * EN: small fixes, custom text for payment types
= 1.0.9.6 =
  * RO: cateva fixuri
  * EN: small fixes
= 1.0.9.5 =
  * RO: cateva fixuri
  * EN: small fixes
= 1.0.9.4 =
  * RO: optimizare incarcare nomenclatoare, cateva fixuri
  * EN: load-time optimisation for nomenclators, small fixes
= 1.0.9.3 =
  * RO: adaptari vue props
  * EN: vue props adaptations
= 1.0.9.2 =
  * RO: transformari texte titluri nomenclatoare, cateva fixuri
  * EN: nomenclator title text transform, small fixes
= 1.0.9.1 =
  * RO: cateva fixuri
  * EN: small fixes
= 1.0.9.0 =
  * RO: cateva fixuri
  * EN: small fixes
= 1.0.8.9 =
  * RO: customizare tema vue, texte implicite bife schimbate, maximum programari pe zi per ip
  * EN: vue theme customisation, default checkboxes texts altered, max appointments per day per ip
= 1.0.8.8 =
= 1.0.8.7 =
= 1.0.8.6 =
= 1.0.8.5 =
= 1.0.8.4 =
= 1.0.8.3 =
  * RO: cateva imbunatatiri si fixuri
  * EN: small features and fixes
= 1.0.8.2 =
  * RO: cateva corectii de css
  * EN: small css fixes
= 1.0.8.1 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.8.0 =
  * RO: implementat formular in vue-js, se utilizeaza shortcode-ul [bizcalv] sau se forteaza din setari pentru tagurile bizcal.
  * EN: implemented vue-js form, use the [bizcalv] shortcode or force vue display for all bizcal tags from settings.
= 1.0.7.15 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.7.14 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.7.13 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.7.12 =
  * RO: Fix pentru text email client
  * EN: Client email text fix
= 1.0.7.11 =
  * RO: Fix pentru browser Safari
  * EN: Safari bug-fixes
= 1.0.7.10 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.7.9 =
  * RO: fixat pastrarea fisierelor jquery-ui personalizate la actualizarile ulterioare. Dupa actualizare, daca utilizati Aspect jquery-ui personalizat, va rugam sa descarcati din nou tema in tabul Aspect jQuery UI din setarile modulului. Corectii css mici.
  * EN: fixed losing the custom jquery-ui css files upon plugin update. If you are using custom jquery-ui interface, please re-download the theme using the button inside the JQuery UI look tab in the plugin settings.
= 1.0.7.8 =
  * RO: posibilitate reordonare specialitati in selector (Tab aspect), corectii CSS implicit, cateva corectii de erori
  * EN: can reorder specialities in the selector (Appearance Tab), default CSS corrections, small bugfixes
= 1.0.7.7 =
  * RO: adăugarea unor texte suplimentare în zona de mesaje personalizabile
  * EN: add additional texts in customizable messages area
= 1.0.7.6 =
  * RO: modificare aspect elemente jQuery UI si selectoare din interfata modulului (Tab Aspect jQuery UI din setari).
        posibilitate adaugare CSS Personalizat (Tab CSS Personalizat din setari)
        bife Acorduri - implicit active (Termeni si conditii, GDPR, Newsletter) cu texte si link-uri personalizabile sau selectie pagina (Tab Acorduri din setari)
        pagina de programare cu succes care are acces unic permitand astfel link tracking, cu text personalizabil si link personalizabil sau selectie pagina (Tab Link Tracking din setari)
        fixuri interfata administrare
        mesaje noi customizabile
        majoritatea mesajelor curente au fost alterate pentru a suporta noile informatii si pentru a permite reordonarea informatiilor din interior. Textele vechi inca sunt suportate in forma lor curenta, dar este foarte recomandat sa le adaptati sau sa reveniti la textul original. Pentru a folosi textul original, eliminati complet continutul textului din casetele de editare dorite. Textul original se afla deasupra casetei de editare, copiati cu grija.
        clientul poate descarca programarea in format .ics sau .vcs pentru a-l inregistra in aplicatia calendar dorita.
  * EN: change the look of jQuery UI elements and selectors in the module interface (JQuery UI look tab in settings).
        possibility to add Custom CSS (Custom CSS Tab from settings)
        Agreement ticks - enabled by default (Terms and conditions, GDPR, Newsletter) with  customizable texts and links or page selection (Tabs Agreements in settings)
        Scheduling Success page that has unique access thus allowing link tracking, with customizable text and customizable link or page selection (Tab Link Tracking from settings)
        administration interface fixes
        new customizable messages
        most of the current messages have been altered to support the new information and to allow the reordering of the information inside. Old texts are still supported in their current form, but it is highly recommended to adapt them or return them to the original text. To use the original text, completely remove the text content from the desired edit boxes. The original text is above the edit box, copy it carefully.
        the client can download the schedule in .ics or .vcs format to register it in the desired calendar application.
= 1.0.7.5 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.7.4 =
  * RO: s-a remediat problema de transmitere a specialității medicale în cadrul programărilor gratuite
  * EN: we fixed an issue in witch the medical speciality could be incorrectly determined when making free appointments
= 1.0.7.3 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.7.2 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.7.1 =
  * RO: am îmbunătățit modalitatea de filtrare a serviciilor medicale disponibile
  * EN: we improved the way the medical services are being filtered
= 1.0.7.0 =
  * RO: am adăugat prețul serviciului în mail-ul de confirmare a programării
  * EN: we added the service price in the appointment confirmation e-mail body
= 1.0.6.9 =
  * RO: am eliminat modificarea din 1.0.6.8
  * EN: we reverted the changes in 1.0.6.8
= 1.0.6.8 =
  * RO: s-a remediat o problema de compatibilitate cu site-urile care foloseau jQuery 3.x
  * EN: we fixed a compatibility issue with websites using jQuery 3.x
= 1.0.6.7 =
  * RO: s-a remediat problema aparuta la validarea codului ReCaptcha in versiunea 1.0.6.6
  * EN: we fixed the issue introduced with version 1.0.6.6 regarding ReCaptcha code validation
= 1.0.6.6 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.6.5 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.6.4 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.6.3 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.6.2 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.6.1 =
  * RO: cateva corectii de erori
  * EN: small bugfixes
= 1.0.6.0 =
  * RO: s-a adaugat posibilitatea de a preselecta serviciul dorit in fereastra de programare
        s-a adaugat posibilitatea de a modifica ordinea de selectare a campurilor in fereastra de programare
  * EN: we added the posibility to preselect the medical service
        we added the posibility to change the display order for the appointment filters
= 1.0.5.1 =
  * RO: s-au făcut unele modificări de stil pentru compatibilitate cu temele instalate la anumiți clienți
  * EN: we made some changes to the stylesheet for compatibility with some templates our customers are using
= 1.0.5.0 =
  * RO: s-a adăugat posibilitatea de a selecta locația pentru care se face programarea (pentru clinicile cu mai multe locații)
  * EN: we added the possibility to select the location for which the appointment is done (for clinics with several locations)
= 1.0.4.1 =
  * RO: s-a remediat problema care apărea la utilizarea mai multor butoane de programare cu preselectare medic în aceeași pagină
  * EN: we fixed an issue when using multiple physician appointment buttons on the same page
= 1.0.4.0 =
  * RO: s-a adăugat posibilitatea de a adăuga mai multe butoane de programare pe aceeași pagină, cu posibilitatea de preselectare a specialității medicale și a medicului preferat
  * EN: we added the possibility to add more than one appointment button on the same page, and the possibility to preselect the medical specialty and the preferred physician
= 1.0.3.6 =
  * RO: s-au făcut unele modificări de stil pentru compatibilitate cu temele instalate la anumiți clienți
  * EN: we made some changes to the stylesheet for compatibility with some templates our customers are using
= 1.0.3.5 =
  * RO: s-au făcut unele modificări de stil pentru compatibilitate cu temele instalate la anumiți clienți
  * EN: we made some changes to the stylesheet for compatibility with some templates our customers are using
= 1.0.3.4 =
  * RO: s-au făcut unele modificări de stil pentru compatibilitate cu temele instalate la anumiți clienți
  * EN: we made some changes to the stylesheet for compatibility with some templates our customers are using
= 1.0.3.3 =
  * RO: s-a remediat o problemă în urmă căreia uneori se trimitea mail de confirmare programare, chiar dacă intervalul de timp solicitat era ocupat de o altă programare
  * EN: we fixed the issue with the module sometimes sending confirmation e-mail even if the requested time interval was occupied by another appointment
= 1.0.3.2 =
  * RO: s-au făcut unele modificări de stil pentru compatibilitate cu temele instalate la anumiți clienți
  * EN: we made some changes to the stylesheet for compatibility with some templates our customers are using
= 1.0.3.1 =
  * RO: s-a remediat problema de derulare a listei de intervale orare disponibile pe dispozitivele mobile
  * EN: we fixed the time intervals scrolling issue on mobile devices
= 1.0.3.0 =
  * RO: s-a adăugat posibilitatea de a alege medicul dorit atunci când se caută sloturi de timp disponible
        s-a adăugat posibilitatea de a deschide modulul de programari ca fereastră popup
		diverse optimizări și îmbunătățiri
  * EN: we added the possibility of choosing the desired doctor when looking for available time slots
		now it's possible to open the programming module as a popup window
		various fixes and improvements
= 1.0.2.2 =
  * RO: s-a remediat o problemă care putea duce în anumite cazuri la o durată mare de timp la încărcarea specialităților
  * EN: we fixed an issue which could lead to high delays when loading medical specialities
= 1.0.2.1 =
  * RO: s-a remediat o problemă la preluarea programărilor disponibile în cazul în care există multe cereri simultane către serviciul BizMedica
  * EN: we fixed an issue with downloading available appointments if there are many simultaneous requests to the BizMedica service
= 1.0.2.0 =
  * RO: s-a adăugat posibilitatea de a defini un număr minim de zile până la prima programare disponibilă în site
  * EN: we added the posibility to define a minimum period (in days) until the first appointment interval displayed on the form
= 1.0.1.5 =
  * small bugfixes
= 1.0.1.4 =
  * small bugfixes
= 1.0.1.3 =
  * small bugfixes
= 1.0.1.2 =
  * solve conflict with Bootstrap Datepicker
= 1.0.1.1 =
  * add max availabilities option
= 1.0.1.0 =
  * add physician picture and description
= 1.0.0.1 =
  * get payment types on speciality selection
= 1.0.0.0 =
  * Initial version

== Upgrade Notice ==

= 1.0.7.7 =
  RO: Am adăugat unele texte suplimentare în zona de mesaje personalizabile. Actualizați modulul dacă doriți această funcționalitate
  EN: We added some additional texts in customizable messages area. Please update if you need this feature
= 1.0.7.6 =
  RO: Am adăugat unele funcționalități suplimentare în plugin. Actualizați modulul dacă doriți vreuna dintre funcționalitățile de mai jos:
      * modificare aspect elemente jQuery UI si selectoare din interfata modulului (Tab Aspect jQuery UI din setari).
      * posibilitate adaugare CSS Personalizat (Tab CSS Personalizat din setari)
      * bife Acorduri - implicit active (Termeni si conditii, GDPR, Newsletter) cu texte si link-uri personalizabile sau selectie pagina (Tab Acorduri din setari)
      * pagina de programare cu succes care are acces unic permitand astfel link tracking, cu text personalizabil si link personalizabil sau selectie pagina (Tab Link Tracking din setari)
      * fixuri interfata administrare
      * mesaje noi customizabile
      * majoritatea mesajelor curente au fost alterate pentru a suporta noile informatii si pentru a permite reordonarea informatiilor din interior. Textele vechi inca sunt suportate in forma lor curenta, dar este foarte recomandat sa le adaptati sau sa reveniti la textul original. Pentru a folosi textul original, eliminati complet continutul textului din casetele de editare dorite. Textul original se afla deasupra casetei de editare, copiati cu grija.
      * clientul poate descarca programarea in format .ics sau .vcs pentru a-l inregistra in aplicatia calendar dorita.
  * EN: We added some new features to the plugin. Please update if you need any of the following features:
      * change the look of jQuery UI elements and selectors in the module interface (JQuery UI look tab in settings).
      * possibility to add Custom CSS (Custom CSS Tab from settings)
      * Agreement ticks - enabled by default (Terms and conditions, GDPR, Newsletter) with  customizable texts and links or page selection (Tabs Agreements in settings)
      * Scheduling Success page that has unique access thus allowing link tracking, with customizable text and customizable link or page selection (Tab Link Tracking from settings)
      * administration interface fixes
      * new customizable messages
      * most of the current messages have been altered to support the new information and to allow the reordering of the information inside. Old texts are still supported in their current form, but it is highly recommended to adapt them or return them to the original text. To use the original text, completely remove the text content from the desired edit boxes. The original text is above the edit box, copy it carefully.
      * the client can download the schedule in .ics or .vcs format to register it in the desired calendar application.
= 1.0.7.5 =
  RO: Această versiune remediază unele erori minore, actualizați dacă întâmpinați probleme cu versiunea curentă
  EN: This version fixes some minor bugs, upgrade if you have any issues with the current version
= 1.0.7.4 =
  RO: S-a remediat problema de transmitere a specialității medicale în cadrul programărilor gratuite. Actualizați modulul cât mai repede posibil.
  EN: We fixed an issue in witch the medical speciality could be incorrectly determined when making free appointments. Please update as soon as possible.
= 1.0.7.3 =
  RO: Această versiune remediază unele erori minore, actualizați dacă întâmpinați probleme cu versiunea curentă
  EN: This version fixes some minor bugs, upgrade if you have any issues with the current version
= 1.0.7.2 =
  RO: Această versiune remediază unele erori minore, actualizați dacă întâmpinați probleme cu versiunea curentă
  EN: This version fixes some minor bugs, upgrade if you have any issues with the current version
= 1.0.7.1 =
  RO: Am îmbunătățit modalitatea de filtrare a serviciilor medicale disponibile. Actualizați modulul dacă doriți aceste îmbunătățiri
  EN: We improved the way the medical services are being filtered. Please update if you need this feature
= 1.0.7.0 =
  RO: Am adăugat prețul serviciului în mail-ul de confirmare a programării. Actualizați modulul dacă doriți această funcționalitate
  EN: We added the service price in the appointment confirmation e-mail body. Please update if you need this feature
= 1.0.6.9 =
  RO: S-au eliminat modificarile din 1.0.6.8
  EN: We reverted the changes in 1.0.6.8
= 1.0.6.8 =
  RO: S-a remediat o problema de compatibilitate cu site-urile care foloseau jQuery 3.x. Actualizați dacă site-ul dumneavoastră utilizează această versiune de jQuery
  EN: We fixed a compatibility issue with websites using jQuery 3.x. Please update if your website uses this version of jQuery
= 1.0.6.7 =
  RO: Această versiune remediază o eroare gravă descoperită în versiunea 1.0.6.6, faceti actualizarea daca ați trecut la versiunea 1.0.6.6
  EN: This version fixes a major bug in version 1.0.6.6, you should upgrade as soon as possible if you installed the plugin version 1.0.6.6
= 1.0.6.6 =
  RO: Această versiune remediază unele erori minore, actualizați dacă întâmpinați probleme cu versiunea curentă
  EN: This version fixes some minor bugs, upgrade if you have any issues with the current version
= 1.0.6.5 =
  RO: Această versiune remediază unele erori descoperite în versiunea 1.0.6.0, faceti actualizarea daca ați trecut la versiunea 1.0.6.0
  EN: This version fixes a bug in version 1.0.6.0, you should upgrade as soon as possible
= 1.0.6.4 =
  RO: Această versiune remediază unele erori descoperite în versiunea 1.0.6.0, faceti actualizarea daca ați trecut la versiunea 1.0.6.0
  EN: This version fixes a bug in version 1.0.6.0, you should upgrade as soon as possible
= 1.0.6.3 =
  RO: Această versiune remediază unele erori descoperite în versiunea 1.0.6.0, faceti actualizarea daca ați trecut la versiunea 1.0.6.0
  EN: This version fixes a bug in version 1.0.6.0, you should upgrade as soon as possible
= 1.0.6.2 =
  RO: Această versiune remediază unele erori descoperite în versiunea 1.0.6.0, faceti actualizarea daca ați trecut la versiunea 1.0.6.0
  EN: This version fixes a bug in version 1.0.6.0, you should upgrade as soon as possible
= 1.0.6.1 =
  RO: Această versiune remediază unele erori descoperite în versiunea 1.0.6.0, faceti actualizarea daca ați trecut la versiunea 1.0.6.0
  EN: This version fixes a bug in version 1.0.6.0, you should upgrade as soon as possible
= 1.0.6.0 =
  RO: În această versiune s-au implementat modificările de mai jos, actualizați modulul dacă doriți aceste modificări:
      * S-a adaugat posibilitatea de a preselecta serviciul dorit in fereastra de programare
      * S-a adaugat posibilitatea de a modifica ordinea de selectare a campurilor in fereastra de programare
  EN: This version contains the following changes, please upgrade if you wish to implement them:
      * We added the posibility to preselect the medical service
      * We added the posibility to change the display order for the appointment filters
= 1.0.5.1 =
  RO: S-au făcut unele modificări de stil pentru compatibilitate cu temele instalate la anumiți clienți. Actualizați la această versiune dacă aveți probleme cu afișarea anumitor elemente in pagină.
  EN: We made some changes to the stylesheet for compatibility with some templates our customers are using. Please update if you are having display issues with some elements in our module.
= 1.0.5.0 =
  RO: s-a adăugat posibilitatea de a selecta locația pentru care se face programarea (pentru clinicile cu mai multe locații). Faceți actualizarea dacă doriți această funcționalitate.
  EN: we added the possibility to select the location for which the appointment is done (for clinics with several locations). Please update if you need this feature.
= 1.0.4.1 =
  RO: S-a remediat problema care apărea la utilizarea mai multor butoane de programare cu preselectare medic în aceeași pagină. Actualizați dacă folosiți deja versiunea 1.0.4.0.
  EN: We fixed an issue when using multiple physician programming buttons on the same page. This update is mandatory if you are using the version 1.0.4.0.
= 1.0.4.0 =
  RO: S-a adăugat posibilitatea de a adăuga mai multe butoane de programare pe aceeași pagină, cu posibilitatea de preselectare a specialității medicale și a medicului preferat. Actualizați modulul dacă doriți aceste functionalități.
  EN: We added the possibility to add more than one appointment button on the same page, and the possibility to preselect the medical specialty and the preferred physician. You should upgrade if you need these features.
= 1.0.3.6 =
  RO: S-au făcut unele modificări de stil pentru compatibilitate cu temele instalate la anumiți clienți. Actualizați la această versiune dacă aveți probleme cu afișarea anumitor elemente in pagină.
  EN: We made some changes to the stylesheet for compatibility with some templates our customers are using. Please update if you are having display issues with some elements in our module.
= 1.0.3.5 =
  RO: S-au făcut unele modificări de stil pentru compatibilitate cu temele instalate la anumiți clienți. Actualizați la această versiune dacă aveți probleme cu afișarea anumitor elemente in pagină.
  EN: We made some changes to the stylesheet for compatibility with some templates our customers are using. Please update if you are having display issues with some elements in our module.
= 1.0.3.4 =
  RO: S-au făcut unele modificări de stil pentru compatibilitate cu temele instalate la anumiți clienți. Actualizați la această versiune dacă aveți probleme cu afișarea anumitor elemente in pagină.
  EN: We made some changes to the stylesheet for compatibility with some templates our customers are using. Please update if you are having display issues with some elements in our module.
= 1.0.3.3 =
  RO: S-a remediat o problemă în urmă căreia uneori se trimitea mail de confirmare programare, chiar dacă intervalul de timp solicitat era ocupat de o altă programare. Vă rugăm să faceți actualizarea dacă ați întâmpinat asfel de probleme.
  EN: We fixed the issue with the module sometimes sending confirmation e-mail even if the requested time interval was occupied by another appointment. Please upgrade if you encountered such problems.
= 1.0.3.2 =
  RO: S-au făcut unele modificări de stil pentru compatibilitate cu temele instalate la anumiți clienți. Actualizați la această versiune dacă aveți probleme cu afișarea anumitor elemente in pagină.
  EN: We made some changes to the stylesheet for compatibility with some templates our customers are using. Please update if you are having display issues with some elements in our module.
= 1.0.3.1 =
  RO: S-a remediat problema de derulare a listei de intervale orare disponibile pe dispozitivele mobile. Actualizați dacă ați instalat deja versiunea 1.0.3.0.
  EN: We fixed the time intervals scrolling issue on mobile devices. Please upgrade if you already installed version 1.0.3.0.
= 1.0.3.0 =
  RO: În această versiune s-au implementat modificările de mai jos, actualizați modulul dacă doriți aceste modificări:
      * S-a adăugat posibilitatea de a alege medicul dorit atunci când se caută sloturi de timp disponible
      * S-a adăugat posibilitatea de a deschide modulul de programari ca fereastră popup (prin folosirea shortcode-ului [bizcal_popup])
	  * Diverse optimizări și îmbunătățiri
  EN: This version contains the following changes, please upgrade if you wish to implement them:
	  *	We added the possibility of choosing the desired doctor when looking for available time slots
	  * Now it's possible to open the programming module as a popup window
	  *	Various fixes and improvements
= 1.0.2.2 =
  RO: Această versiune include o optimizare, faceți actualizarea dacă întâmpinați întârzieri la încărcarea specializărilor medicale
  EN: This version includes an optimization when loading medical specialities, please update if you are experiencing long delays when loading the page
= 1.0.2.1 =
This version fixes an issue with appointment downloading, update as soon as possible
= 1.0.2.0 =
  RO: În această versiune s-a adăugat posibilitatea de a defini un număr minim de zile până la prima programare disponibilă în site,
    faceți actualizarea doar dacă aveți nevoie de această facilitate
  EN: In this version we added the posibility to define a minimum period (in days) until the first appointment interval displayed on the form,
    please update if you require this functionality
= 1.0.1.5 =
This version fixes a number of security issues, you should upgrade as soon as possible
= 1.0.1.4 =
This version fixes a bug in version 1.0.1.0, you should upgrade as soon as possible
= 1.0.1.3 =
This version fixes a bug in version 1.0.1.0, you should upgrade as soon as possible
= 1.0.1.2 =
This version solves a conflict with Bootstrap themes that use the Bootstrap Datepicker control. You should upgrade immediately if you have such a theme.
= 1.0.1.1 =
This version enables you to set a maximum number of appointments intervals displayed to the user, in order to avoid fragmenting the appointments. Upgrade if you desire this feature.
= 1.0.1.0 =
This version enables you to add a picture and a small description for each physician. Upgrade if you desire this feature.
= 1.0.0.1 =
Upgrade immediately, the plugin will stop working without this update.
