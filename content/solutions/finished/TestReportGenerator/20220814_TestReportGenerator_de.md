# Test Report Generator

Generiert einen localisierte Bericht basierend auf Testergebnisse

<div class="splash">
    <img alt="Splash" src="/content/solutions/finished/TestReportGenerator/img/TestReportGenerator_splash.png">
    <div class="price">Preis: 0,00 EUR</div>
    <div class="purchase">
        <a class="button" href="https://github.com/Karaka-Management/TestReportGenerator/archive/refs/heads/master.zip">Download</a>
    </div>
</div>

## Zusammenfassung

Der Test Report Generator ist ein PHP-Skript, das einen Bericht aus PHPUnit-Tests und anderen Testwerkzeugen erstellt. Dieser Bericht kann für Software-Releases und Software-Audits hilfreich sein, um zu zeigen, welche Tests durchgeführt wurden und welche Ergebnisse sie hatten. Die generierte Ausgabe besteht aus html und css und kann nach Belieben mit benutzerdefinierten Themen gestaltet werden. Der Bericht ist ideal für die Auslieferung von Software als Nachweis für Kunden, dass die Software (z. B. Updates) getestet wurde und die erwartete Leistung erbringt.

## Funktionen

* Benutzerdefinierte Berichtsvorlage, definiert in html und css
* Benutzerdefinierte Test- und Berichtslokalisierungen
* Benutzerdefinierte Befehlszeilenargumente, die im Bericht verwendet werden können
* Manuelle Festlegung, welche Tests im Bericht angezeigt werden sollen
* PHPUnit-Testergebnisse melden (Eingabe erforderlich: junit)
* Ergebnisse der Codeabdeckung melden (Format: coverage-clover)
* PHPStan-Ergebnisse melden (Format: junit)
* PHPCS-Ergebnisse melden (Format: junit)
* ESLint-Ergebnisse melden (Format: junit)

## Abhängigkeiten

* PHP Version 8.0 oder neuer
* Testergebnisse von PHPUnit im junit-Format
* Code coverage im Coverage-Clover-Format (optional)
* PHPStan-Bericht im json-Format (optional)
* PHPCS-Bericht im Junit-Format (optional)
* ESLint-Bericht im Junit-Format (optional)

## Legal

* [AGBs](/de/terms)
* [Lizenz](https://github.com/Karaka-Management/TestReportGenerator/blob/master/LICENSE.txt)

## Referenzen

* [Installation & Verwendung](https://github.com/Karaka-Management/TestReportGenerator/blob/master/README.md)
