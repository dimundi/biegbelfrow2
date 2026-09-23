# Bieg Belfrów — zakres prac

## Cel

Przeniesienie strony z projektu `biegbelfrow` (React/Next.js) do projektu `biegbelfrow2`, opartego na WordPressie. Nowa strona ma działać samodzielnie, bez zależności od dotychczasowego API. Ewentualna integracja z API może powstać później jako osobna wtyczka.

## Pilny etap — 6 godzin

- Możemy stracić dostęp do publicznego podglądu biegbelfrow.pl. Kod i dane pozostają dostępne; priorytetem jest przygotowanie działającej wersji na WordPressie.
- Pierwsza wersja nie wymaga uruchomionych płatności; ich konfigurację i weryfikację można dokończyć później.
- Zabezpieczenie starego podglądu odkładamy. Prace zaczynamy od widoku nowej strony: nagłówka, banera głównego i stopki.
- Zakres na pierwsze 6 godzin: uruchomienie WordPressa, najważniejsze strony i nawigacja, wygląd bieżącej edycji z edytowalnymi kolorami i logo oraz podstawowa struktura wtyczki BB i panelu uczestnika.
- Pełna migracja danych, rozbudowane funkcje uczestnika, rankingi i sklep pozostają w zakresie projektu; ich ukończenie w pierwszym etapie zależy od dostępności danych i środowiska.
- Do publicznego uruchomienia potrzebne są docelowy hosting WordPressa i możliwość skierowania domeny na nową stronę.

## Zakres

1. **Strona i treści** — przygotowanie motywu oraz przeniesienie stron informacyjnych, grafik i nawigacji; zarządzanie treściami w WordPressie. Aktualności nie przenosimy i nie umieszczamy ich w menu.
2. **Sklep WooCommerce** — pakiety uczestnictwa, koszulki i dodatki, koszyk, zamówienia oraz płatności.
3. **Wtyczka Bieg Belfrów** — zarządzanie bieżącą i archiwalnymi edycjami, zapisami, uczestnikami, aktywnościami, wynikami i rankingami; powiązanie edycji z produktami oraz zamówieniami WooCommerce.
4. **Panel uczestnika „Mój BB”** — jedno konto i logowanie do strony oraz sklepu; podgląd udziału w edycjach, dodawanie aktywności, własne wyniki, rankingi, historia udziału, zakupy, zamówienia i zarządzanie danymi konta.
5. **Migracja danych ze starego API** — klienci i konta, edycje, zapisy, aktywności, wyniki, produkty, zamówienia i statusy płatności, z zachowaniem powiązań między danymi.
6. **Weryfikacja i uruchomienie** — migracja próbna, sprawdzenie zgodności danych oraz działania zapisów, panelu i zakupów, końcowe uzupełnienie danych i uruchomienie strony.

## Wygląd zmieniany co edycję

- Nie kopiujemy starej strony 1:1. Możemy przeprojektować układ i nadać stronie lżejszy, bardziej współczesny wygląd.
- Wykorzystujemy istniejące logo, zdjęcia i grafiki. Nowy projekt nie powinien wymagać tworzenia nowych materiałów graficznych lub powinien ograniczać ich liczbę do minimum.
- Poprawę wyglądu opieramy przede wszystkim na typografii, odstępach, kolorystyce, kompozycji sekcji i responsywności.
- Każda coroczna edycja ma własną identyfikację wizualną: kolory główne i pomocnicze, kolor lub wariant logo oraz grafiki.
- Ustawienia wyglądu edycji muszą być łatwo edytowalne w panelu WordPressa, bez zmian w kodzie i ręcznego poprawiania każdej podstrony.
- Wybór bieżącej edycji stosuje jej identyfikację wizualną spójnie na stronie, w sklepie i panelu uczestnika.
- Ustawienia poprzednich edycji pozostają zapisane; rozpoczęcie nowej edycji nie nadpisuje ich identyfikacji ani treści archiwalnych.
- Przy tworzeniu kolejnej edycji można skopiować ustawienia poprzedniej i zmienić kolory, logo, grafiki oraz treści.

## Założenia migracji

- Importer zachowuje identyfikatory ze starego systemu i umożliwia ponowny import bez tworzenia duplikatów.
- Możliwość przeniesienia haseł wymaga sprawdzenia; w razie braku zgodności potrzebny będzie proces ustawienia nowego hasła.
- Import historycznych zamówień nie może ponownie uruchamiać płatności ani wysyłki wiadomości.
- Szczegółowy zakres danych i sposób ich pobrania ustalimy po analizie starego API oraz dostępnych eksportów.
