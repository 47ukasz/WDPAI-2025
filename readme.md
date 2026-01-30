# Dokumentacja

## 1. Diagram ERD

![Diagram przedstawiający encje w bazie danych. Baza zawiera 4 tabele: items, users, users_roles oraz roles](documentation/bazadanych_erd.png)

_Link do pliku (drawio): https://drive.google.com/file/d/1ysdqVV394UtxE319yc_0wSo8_fpxgLD1/view?usp=sharing_

## 2. Screeny aplikacji

### 2.1 Rejestracja

![alt text](documentation/rejestracja.png)

![alt text](documentation/walidacja_rejestracji.png)

### 2.2 Logowanie

![alt text](documentation/logowanie.png)

### 2.3 Strona główna

![alt text](documentation/strona_glowna.png)

![alt text](documentation/strona_glowna_2.png)

<img src="documentation/strona_glowna_mobile.png" width="200" height="400">

### 2.5 Formularz ogłoszenia

![alt text](documentation/formularz_ogloszenia.png)

![alt text](documentation/formularz_ogloszenia_walidacja.png)

<img src="documentation/formularz_ogloszenia_mobile.png" width="200" height="400">

### 2.6 Strona użytkownika

![alt text](documentation/profil_uzytkownika.png)

![alt text](documentation/profil_uzytkownika2.png)

### 2.7 Edytowanie ogłoszenia

![alt text](documentation/edycja_ogloszen.png)

### 2.8 Panel administratora

![alt text](documentation/panel_administratora.png)

### 2.9 Nawigacja mobilna

<img src="documentation/nawigacja_mobile.png" width="200" height="400">

### 2.10 Strona przedmiotu

![alt text](documentation/strona_przedmiotu.png)

<img src="documentation/strona_przedmiotu_mobile.png" width="200" height="400">

## 3. Diagram architektury

![alt text](documentation/diagram_architektury.png)

## 4. Instrukcja uruchomienia

W folderze gdzie znajduje się plik docker-compose.yml wywołujemy komendę docker compose up -d. Pozwala to na uruchomienie konterenerów z zależnościami aplikacji.

## 5. Scenariusz testowy

### 5.1 Logowanie

**Nazwa**: Logowanie do aplikacji <br>
**Cel**: Sprawdzenie czy użytkownik może zalogować się do konta podając email oraz hasło <br>
**Warunki wstępne**: Użytkownik wcześniej już zarejestrował się do aplikacji

**Kroki**:

1. Otwórz stronę logowania: http://localhost:8080/login
2. Wprowadź poprawny adres email
3. Wprowadź poprawne hasło
4. Wybierz opcję zaloguj się

**Rezultat**:

- Użytkownik zostaje zalogowany do swojego konta i jest przekierowany na stronę swojego profilu: http://localhost:8080/user-page
- Utworzona zostanie sesja użytkownika do której zostały dodane nowe właściwości

### 5.2 Logowanie z niepoprawnym formatem adresu email

**Nazwa**: Logowanie do aplikacji przy użyciu niepoprawnego formatu adresu email<br>
**Cel**: Sprawdzenie czy użytkownik może zalogować się do konta podając zły format email <br>
**Warunki wstępne**: Brak

**Kroki**:

1. Otwórz stronę logowania: http://localhost:8080/login
2. Wprowadź adres email: test@
3. Wprowadź poprawne hasło
4. Wybierz opcję zaloguj się

**Rezultat**:

- Formularz nie zostanie wysłany
- Wyświeltony zostanie komunikat o treści: "Wprowadź poprawny adres email"

### 5.3 Logowanie do konta przy użyciu nieprawidłowych danych do konta

**Nazwa**: Logowanie do aplikacji przy użyciu nieprawidłowych danych do kontal<br>
**Cel**: Sprawdzenie czy system nie daje jednoznacznej informacji czy użytkownik o podanym adresie email istnieje już w bazie danych <br>
**Warunki wstępne**: Brak

**Kroki**:

1. Otwórz stronę logowania: http://localhost:8080/login
2. Wprowadź adres email: test@gmail.com
3. Wprowadź losowe hasło
4. Wybierz opcję zaloguj się

**Rezultat**:

- Wyświeltony zostanie komunikat o treści: "Email lub hasło niepoprawne."

### 5.4 Rejestracja

**Nazwa**: Rejestracja do konta<br>
**Cel**: Sprawdzenie czy aplikacja umożliwia zakładanie nowych kont <br>
**Warunki wstępne**: Brak

**Kroki**:

1. Otwórz stronę rejestracji: http://localhost:8080/register
2. Wprowadź adres email: test@gmail.com
3. Wprowadź hasło
4. Wprowadź ponownie hasło do pola "Powtórz hasło"
5. Wprowadź imię użytkownika
6. Wprowadź nazwisko użytkownika
7. Wybierz opcję stwórz konto

**Rezultat**:

- Użytkownik zostanie przekierowany na stronę logowania
- Dane użytkownika zostaną zapisane w tabeli
- Wyświeltony zostanie komunikat o treści: "Utworzono nowe konto, zaloguj się!"

### 5.5 Rola Administratora

**Nazwa**: Wyświetlanie panelu administratora <br>
**Cel**: Sprawdzenie czy w momencie posiada roli administratora zostanie poprawnie wyświetlony jego panel <br>
**Warunki wstępne**: Użytkownik musi posiadać rolę administratora

**Kroki**:

1. Otwórz stronę panelu administratora: http://localhost:8080/admin

**Rezultat**:

- Użytkownikowi wyświetli się panel administratora

### 5.6 Błąd 403 (Panel Administratora)

**Nazwa**: Wyświetlenie błędu<br>
**Cel**: Sprawdzenie czy w momencie braku roli administratora użytkownik zostanie przekierowany na stronę błędu 403 <br>
**Warunki wstępne**: Użytkownik nie posiada roli administratora

**Kroki**:

1. Otwórz stronę panelu administratora: http://localhost:8080/admin

**Rezultat**:

- Użytkownikowi wyświetli się strona błędu 403

### 5.7 Błąd 401 (Panel użytkownika)

**Nazwa**: Wyświetlenie błędu<br>
**Cel**: Sprawdzenie czy niezalogowany użytkownik zostanie przekierowany na strone błędu w momencie próbu wejścia na stronę profilu użytkownika<br>
**Warunki wstępne**: Użytkownik nie jest zalogowany

**Kroki**:

1. Otwórz stronę profilu użytkownika: http://localhost:8080/user-page

**Rezultat**:

- Użytkownikowi wyświetli się strona błędu 401

### 5.8 Tworzenie nowego ogłoszenia

**Nazwa**: Tworzenie nowego ogłoszenia <br>
**Cel**: Sprawdzenie czy zalogowany użytkownik może dodać nowe ogłoszenie<br>
**Warunki wstępne**: Użytkownik posiada konto w aplikacji oraz jest do niego zalogowany

**Kroki**:

1. Otwórz stronę formularzu: http://localhost:8080/add-offer
2. Wypełnij formularz
3. Wybierz opcję zapisz

**Rezultat**:

- Ogłoszenie zostanie zapisane w bazie
- Użytkownik zostanie przekierowany na stronę profilu

### 5.9 Wyświetlanie zgłoszeń użytkownika

**Nazwa**: Wyświetlanie zgłoszeń użytkownika <br>
**Cel**: Sprawdzenie czy zalogowany użytkownik może zobaczyć ogłoszenia na swoim profilu<br>
**Warunki wstępne**: Użytkownik posiada konto w aplikacji, jest do niego zalogowany oraz posiada już wcześniej utworzone ogłoszenia

**Kroki**:

1. Otwórz stronę profilu: http://localhost:8080/user-page

**Rezultat**:

- Użytkownikowi zostaną wyświetlone jego ogłoszenia

### 5.10 Aktualizowanie zgłoszeń

**Nazwa**: Aktualizowanie zgłoszeń użytkownika <br>
**Cel**: Sprawdzenie czy zalogowany użytkownik może aktualizować treść ogłoszenia<br>
**Warunki wstępne**: Użytkownik posiada konto w aplikacji, jest do niego zalogowany oraz posiada już wcześniej utworzone ogłoszenia

**Kroki**:

1. Otwórz stronę profilu: http://localhost:8080/user-page
2. Wybierz przycisk "Edytuj" przy wybranym ogłoszeniu
3. Wprowadź nową treść do wybranego pola.
4. Wybierz przycisk "Zapisz zmiany"

**Rezultat**:

- Treść wybranego ogłoszenia zostanie zaaktualizowana
- Użytkownik zostanie przekierowany na swój profil

### 5.11 Usuwanie zgłoszeń

**Nazwa**: Usuwanie zgłoszeń użytkownika <br>
**Cel**: Sprawdzenie czy zalogowany użytkownik może usunąć ogłoszenie<br>
**Warunki wstępne**: Użytkownik posiada konto w aplikacji, jest do niego zalogowany oraz posiada już wcześniej utworzone ogłoszenia

**Kroki**:

1. Otwórz stronę profilu: http://localhost:8080/user-page
2. Wybierz przycisk "Usuń" przy wybranym ogłoszeniu
3. Potwierdź operację w modalu.

**Rezultat**:

- Wybrane ogłoszenie zostanie usunięte z bazy danych
- Ogłoszenie przestanie być wyświetlane na profilu użytkownika

### 6. Zrealizowane funkcjonalności

- Tworzenie nowego konta - proces rejestracji
- Logowanie do konta wraz z utworzeniem sesji
- Filtrowanie ogłoszeń po tytule na stronie głównej
- Paginacja na stronie głównej
- Dodawanie nowych ogłoszeń, wraz z ich usuwaniem oraz edycją
- Panel administratora -> podział na rolę: użytkownik oraz administrator
- Profil użytkownika
- Pełna responsywność
- Wykorzystanie FETCH API do pobierania oraz usuwania ogłoszeń i użytkowników
- Użycie Loading Indicatora przy pobieraniu ogłoszeń
- Wykonane bingo bezpieczeństwa (oprócz A4, C3, E1, E5)
