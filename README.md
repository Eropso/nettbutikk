# Nettbutikk


## Beskrivelse

Erosho er et nettbutikk utviklet for å selge bedriften sine produkter til kunder. Applikasjonen gir brukerne muligheten til å legge til klær i handlekurven og fullføre kjøpene sine. Dette verktøyet er ideelt for både nye og erfarne kunder som ønsker en enkel og brukervennlig handleopplevelse.

## Funksjoner

- **Legg til produkter i handlekurven**: Brukere kan enkelt legge til produkter i handlekurven med ønsket antall.
- **Vis handlekurv**: Brukere kan se en oversikt over produktene i handlekurven, inkludert pris og totalbeløp.
- **Fjern produkter fra handlekurven**: Brukere kan fjerne produkter fra handlekurven hvis de ombestemmer seg.
- **Fullfør kjøp**: Brukere kan gå til kassen og betale for produktene sine via Stripe.
- **Brukerregistrering og innlogging**: Brukere kan registrere seg, logge inn og administrere profilen sin.
- **E-postbekreftelse**: Brukere mottar en bekreftelseskode via e-post for å verifisere kontoen sin.

## Teknologier

- **PHP**: For server-side logikk.
- **MySQL**: For databaselagring.
- **HTML/CSS**: For frontend-design.
- **PHPMailer**: For e-postsending.
- **Stripe API**: For betalingsintegrasjon.


## Bruk

1. **Registrer deg**: Opprett en konto ved å fylle ut registreringsskjemaet.
2. **Logg inn**: Logg inn med e-post og passord.
3. **Handle**: Bla gjennom produktene, legg dem til i handlekurven, og fullfør kjøpet.
4. **Administrer profilen din**: Oppdater personlig informasjon via innstillingene.



## Hosting og tilgjengelighet

Nettsiden er hostet både lokalt og i skyen:

- **Lokal hosting:** Nettsiden kjører på en Raspberry Pi med Apache og Ubuntu for utvikling og testing.
    - For ny oppsett av Raspberry Pi følg stegene 1-6 i README-filen https://github.com/Eropso/installasjonsveiledning


- **Skyhosting:** Produksjonsversjonen er deployet i Azure og er tilgjengelig på [https://eropso.com](https://eropso.com).
    - For tilgang til Azure brukeren ta kontakt

Dette gir fleksibilitet for både lokal utvikling og sikker, skalerbar drift i skyen.

## Teknisk informasjon

| Miljø           | Server/Host         | Operativsystem | Webserver | IP-adresse / URL         | Annet         |
|-----------------|--------------------|----------------|-----------|--------------------------|---------------|
| Lokal utvikling | Raspberry Pi        | Ubuntu         | Apache    | 10.100.10.134 (lokal IP) | Tilgang via LAN |
| Sky (produksjon)| Azure Web App      | Linux/Ubuntu   | Nginx     | [https://eropso.com](https://eropso.com) | SSL aktivert   |

- **Lokal IP**: Du finner IP-adressen til Raspberry Pi ved å kjøre `hostname -I` i terminalen på Pi-en.
- **Apache**: Brukes som webserver lokalt.
- **Nginx**: Brukes som webserver i produksjon på Azure.
- **SSL**: Aktivert i produksjon for sikker kommunikasjon.


## Miljøvariabler og sikkerhet

- **Lokalt:** Viktige passord og API-nøkler lagres i en `.env`-fil som ikke er med i versjonskontroll
- **Azure (sky):** Sensitive verdier som API-nøkler og passord settes som environment variables i Azure-portalen.

Dette sikrer at hemmelig informasjon ikke eksponeres i kildekoden eller i offentlige repoer.


## Bilder
 - Ikoner er hentet fra Google Fonts
 - Hero-bildet er generert med kunstig intelligens ved hjelp av ChatGPT (OpenAI).
 - Klærne er designet av Eropso (Paul) ved hjelp av gratis mockups og redigert i Photoshop.

## Kontakt
For spørsmål eller tilbakemeldinger, vennligst kontakt phpkuben@gmail.com.
