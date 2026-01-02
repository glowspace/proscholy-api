# ProScholy.cz API server

API server databáze projektu ProScholy.cz. Ze serveru čerpají webové frontend aplikace
https://zpevnik.proscholy.cz, https://regenschori.cz,
a mobilní aplikace Zpěvník ProScholy.cz pro Android a iOS.

<img src="https://zpevnik.proscholy.cz/img/logo_bubble.svg" alt="Logo" width="150">

## Funkce serveru

-   GraphQL API
-   GraphQL playground
-   MySQL databáze
-   zpracování LilyPond not a ChordPro akordů
-   zpracování biblických souřadnic a liturgického kalendáře pro doporučení Co hrát na mši
-   webová administrace ve Vue.js pro redakční práci a správu databáze

## Informace k vývoji

Naše komunikace probíhá v rámci projektového Discord serveru projektu Glow Space.
Pokud se k nám chcete přidat, je to možné na https://glowspace.cz/join

## Made by Glow Space

Projekt ProScholy vyvíjí dobrovolníci z digitální komunity [Glow Space](https://glowspace.cz).
Naší snahou je digitalizovat duchovní prostředí, vytvářet kvalitní open-source software pro věřící
a vytvářet komunitu expertních dobrovolníků v oblasti vývoje software a webových aplikací.

### Líbí se Vám naše práce?

Staňte se naším sponzorem, abychom mohli dál tvořit weby a aplikace pro věřící.

# Instalace

## První spuštění v localhostu

Předpoklady:

-   nainstalovaný Docker
-   UNIXový systém (případně WSL)

V host systému spustit:

```sh
cp .env.local.example .env

docker compose up --build -d
```

Pokud proběhne úspěšně instalace a běží dev docker kontejner (laravel.test), tak spustit bash:

```sh
docker compose exec laravel.test bash
```

Tímto se spustí nová konzole, která má nainstalovány všechny potřebné závislosti (php, yarn, ...).

```sh
composer install
yarn install

php artisan key:generate

```

Dále je potřeba pořešit práva tak, aby dev kontejner mohl zapisovat do storage/.

## Produkční nasazení

make production-pull
make production-deploy
