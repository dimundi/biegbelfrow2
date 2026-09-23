# Zasady projektu

## Rozdzielenie środowisk dev i prod

- Pliki produkcyjne zachowują oryginalne nazwy bez dopisku, np. `Dockerfile` i `docker-compose.yml`.
- Wszystkie pliki Dockerfile oraz konfiguracje YAML środowiska deweloperskiego/testowego mają dopisek `dev`: `Dockerfile.dev`, `docker-compose.dev.yml` (dla YAML dopisek przed rozszerzeniem `.yml` lub `.yaml`). Nie używamy dopisku `test`.
- Pliki wdrożeniowe przechowujemy w `deploy/dev/` i `deploy/prod/`. Każdy katalog zawiera Dockerfile, konfiguracje YAML, skrypty budowania i uruchamiania oraz pozostałe pliki potrzebne do obsługi danego środowiska.
- Przykładowe ścieżki: `deploy/dev/Dockerfile.dev`, `deploy/dev/docker-compose.dev.yml`, `deploy/prod/Dockerfile`, `deploy/prod/docker-compose.yml`.
- Konfiguracja dev musi wskazywać Dockerfile.dev. Skrypty i instrukcje uruchamiania muszą jednoznacznie wskazywać właściwe środowisko i poprawnie rozwiązywać ścieżki do źródeł na hoście.
- Środowiska muszą mieć oddzielne konfiguracje, dane baz, media i zasoby Docker. Testy nie mogą korzystać z produkcyjnej bazy ani zapisywać do produkcyjnych katalogów danych.

## Kontenery i pliki źródłowe

- Strona WordPress działa w kontenerze zarówno w środowisku testowym, jak i produkcyjnym.
- Wszystkie pliki źródłowe aplikacji znajdują się w katalogu na hoście i są podłączane do kontenera przez bind mount (mapowanie katalogu hosta do katalogu w kontenerze).
- Katalog źródeł to `wordpress/` w katalogu głównym projektu; w kontenerze jest podłączony jako `/var/www/html`.
- Zasada obejmuje również pliki WordPressa, motywy i wtyczki; źródła nie mogą istnieć wyłącznie wewnątrz kontenera ani być dostępne wyłącznie jako kopia w obrazie.
- Zmiany w źródłach wykonujemy w podłączonym katalogu hosta. Odtworzenie kontenera nie może usuwać tych zmian.
- Konfiguracja kontenerów musi zachowywać ten sposób podłączania źródeł w obu środowiskach: testowym i produkcyjnym.
- Dane bazy oraz przesłane media muszą być przechowywane trwale poza zapisywalną warstwą kontenera.
