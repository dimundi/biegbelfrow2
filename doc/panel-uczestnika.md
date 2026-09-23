# Panel uczestnika — pierwszy etap

- Adres: /mojbb/. Logowanie z menu strony przekierowuje do panelu.
- Obsługa konta i zapisu danych znajduje się we wtyczce wordpress/wp-content/plugins/biegbelfrow/.
- Widok znajduje się w motywie, w pliku page-mojbb.php.
- Wtyczka tworzy stronę przy aktywacji. Skrypt start-dev.bat aktywuje ją przy uruchomieniu środowiska.
- Działa edycja imienia, nazwiska i nazwy wyświetlanej własnego konta. Adres e-mail jest tylko do odczytu.
- Reset hasła korzysta z mechanizmu WordPressa i wymaga działającej wysyłki poczty.
- Moje edycje, Moje wyniki oraz Zakupy i zamówienia mają przygotowane widoki z informacją o niedostępności. Nie są jeszcze połączone z danymi uczestników ani WooCommerce.
- Publiczny ranking nie jest przypisywany do użytkowników na podstawie podobieństwa nazw.
- Widoki konta wymagają logowania i wysyłają nagłówki wyłączające cache. Zapis profilu wymaga POST oraz tokena nonce, a ID użytkownika pochodzi z sesji.

Sprawdzono na tymczasowym koncie uczestnika: przekierowanie gościa do logowania, powrót do panelu, zapis profilu, odrzucenie żądania bez nonce, brak możliwości edycji innego konta przez przesłanie ID, pięć widoków na komputerze i telefonie oraz wylogowanie. Konto testowe usunięto.
