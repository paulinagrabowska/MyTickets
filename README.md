# MyTickets

Aplikacja systemu sprzedaży biletów na koncerty, napisana w języku PHP i wykorzystywać bazę danych MySQL przy  użyciu  frameworka Symfony w ostatniej wersji LTS. Umożliwia wyszukiwanie interesujących użytkownika wydarzeń  muzycznych,  dokonanie rezerwacji na poszczególne koncerty, zarządzanie wydarzeniami przez admina serwisu.

Instalacja projektu:

Polecenia do wykonania w konsoli:
1. cd app
2. composer install
3. php bin/console doctrine:database:create
4. php bin/console doctrine:migrations:migrate
5. php bin/console doctrine:fixtures:load
6. php bin/console server:run
