# bilete-evenimente-cluj

# Cluj Events – Aplicație de bilete pentru evenimente în Cluj-Napoca 🎟️

Cluj Events este o aplicație web realizată pentru disciplina **PHP OOP**, cu scopul de a facilita achiziția de bilete la evenimente din Cluj-Napoca: concerte, teatru, operă, meciuri de fotbal, patinaj artistic etc.

Aplicația permite utilizatorilor să caute evenimente, să filtreze după categorie, să adauge bilete în coș, să plaseze comenzi și să primească email de confirmare după plată.

---

## 🧰 Stack tehnic

- **Backend:** PHP 8+, programare orientată pe obiecte (OOP)
- **Frontend:** HTML5, CSS3, Bootstrap 5
- **Bază de date:** MySQL 
- **Server local:** WAMP (dezvoltare)
- **Integrare plăți:** Stripe (pe un branch dedicat)
- **Email:** funcția `mail()` printr-un serviciu `MailService`

---

## ✨ Funcționalități principale

### Pentru vizitatori (guest)
- Vizualizare listă de evenimente
- Căutare după nume / cuvinte cheie
- Filtrare după categorie (Concert, Fotbal, Operă, Patinaj, Teatru etc.)
- Sortare după dată sau preț
- Pagina de detaliu a evenimentului (descriere, dată, oră, locație, imagine)

### Pentru utilizatori autentificați
- Înregistrare și autentificare (Login / Register)
- Adăugare bilete în coș
- Modificare cantitate în coș
- Plasarea unei comenzi
- Vizualizare istoric comenzi în pagina **„Contul meu”**
- Primirea unui email de confirmare după ce comanda este marcată ca *„paid”*

### Admin (rol `admin`)
- Link dedicat **ADMIN** în navbar (vizibil doar pentru admin)
- Listare evenimente în zona de administrare (`admin/events_list.php`)
- Gestionare produse/evenimente (în funcție de ce a fost implementat în proiect)

### Email & pagini statice
- **MailService**
  - trimite email de confirmare a comenzii către utilizator (detalii comandă + bilete)
  - trimite mesajele din formularul de **Contact** către un email al echipei
- **Contact:** formular simplu (nume, email, mesaj)
- **Despre noi:** pagină cu informații despre proiect și echipă

---

## 🗃️ Structura proiectului (simplificată)

```text
bilete-evenimente-cluj/
├── classes/
│   ├── db_controller.php
│   ├── product_repository.php
│   ├── cart_service.php
│   ├── order_service.php
│   ├── authService.php
│   ├── mail_service.php
│   └── (alte servicii / repository-uri)
├── config/
│   └── config.php           # setări DB, constante proiect
├── database/
│   └── bilete_evenimente_cluj.sql   # script pentru baza de date (nume orientativ)
├── public/
│   ├── index.php            # pagina principală (Evenimente)
│   ├── event.php            # detaliu eveniment
│   ├── cart.php             # coș de cumpărături
│   ├── my_account.php       # comenzi utilizator
│   ├── about.php            # pagină "Despre noi"
│   ├── contact.php          # formular de contact
│   ├── login.php, register.php
│   ├── payment_success.php  # după plată reușită
│   ├── admin/
│   │   └── events_list.php  # listă evenimente pentru admin
│   └── assets/
│       ├── css/style.css
│       ├── images/          # logo, poze evenimente, poze echipă
│       └── favicon/
├── index.php                # proxy care redirecționează către public/index.php
└── README.md                # acest fișier
