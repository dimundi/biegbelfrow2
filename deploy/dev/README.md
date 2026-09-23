# WordPress dev

Wymagany Docker Desktop z uruchomionymi kontenerami Linux i Docker Compose.

1. Uruchom `build-dev.bat`, aby zbudować obraz i pobrać bazę.
2. Uruchom `start-dev.bat`, aby uruchomić kontenery i zainstalować WordPressa.
3. Otwórz http://localhost:8080 lub http://localhost:8080/wp-admin/.

Domyślne lokalne konto: `admin` / `admin`. Zmień wartości w `.env` przed pierwszym startem, jeśli potrzebujesz innych danych. Skrypty tworzą `.env` z `.env.example`, gdy plik nie istnieje. Zmiana danych administratora w `.env` po instalacji nie zmienia istniejącego konta.

Pliki WordPressa są kopiowane przy pierwszym uruchomieniu z oficjalnego obrazu do katalogu `wordpress/` na hoście. Cały katalog jest podłączony przez bind mount, w tym motyw, wtyczki, wp-config.php i media. Konfiguracja wp-config.php odczytuje zmienne środowiskowe przekazane z `.env`. Baza jest w osobnym wolumenie projektu dev. Ponowny start nie resetuje instalacji, konta ani danych.

Zatrzymanie z katalogu deploy/dev:
`docker compose --env-file .env -f docker-compose.dev.yml down`

Nie dodawaj `-v`, jeśli chcesz zachować bazę. Port jest dostępny tylko lokalnie. Przy zmianie portu zmień również WORDPRESS_URL; istniejąca instalacja wymaga osobnej aktualizacji adresu w bazie.

Dokumentacja obrazu: https://hub.docker.com/_/wordpress

