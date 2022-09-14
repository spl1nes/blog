# Kombinatorik Solver

Finde Zahlen, die eine definierte Gesamtsumme ergeben

<div class="splash">
    <img alt="Splash" src="/content/solutions/finished/CombinatoricsSolver/img/CombinatoricsSolver_splash.png">
    <div class="price">Preis: 99,00 EUR</div>
    <div class="purchase">
        <a class="button" rel="download" type="application/zip" href="/api/download?key=<?= \urlencode('Q29tYmluYXRvcmljc1NvbHZlckFwcF9EZW1v'); ?>">Demo</a>
        <a class="button" href="#">Kaufen</a>
    </div>
</div>

## Zusammenfassung

Der Combinatorics Solver versucht, eine gegebene Menge von Zahlen so zu kombinieren, dass ein bestimmter Gesamtbetrag erreicht wird. Die Datenquelle kann über Excel oder CSV eingespielt werden. Eine mögliche Anwendung könnte in der Buchhaltung sein, wenn man die Buchungen finden muss, die einen bestimmten Betrag ausmachen.

## Funktionen

* Definieren Sie, wie viele Zahlen maximal kombiniert werden sollen, um den Gesamtbetrag zu erreichen.
* Erlaubt eine Fehlerkomponente (nützlich, um kleine Differenzen zum Erreichen des Gesamtbetrags zuzulassen)
* Mehrere Lösungen können zurückgegeben werden, falls vorhanden
* Automatische Warnung, wenn die Einstellungen aufgrund der Menge der möglichen Kombinationen Probleme verursachen könnten

## Abhängigkeiten

* Microsoft Windows 7 oder neuer

## Bilder

<div class="splash">
    <img alt="Splash" src="/content/solutions/finished/CombinatoricsSolver/img/CombinatoricsSolver_splash2.png">
</div>

## Technische Details

Die maximale Anyahl von möglichen Kombinationen, die während der Analyse überprüft werden sind durch den Binomialkoeffizienten definiert.

<img alt="Splash" src="/content/solutions/finished/CombinatoricsSolver/img/515b05ed3954b44c81f82709ee1cb188.png" width="200px">

wobei `K = maximale Kombinationen` und `n = Anzahl der Daten`.

## Legal

* [AGBs](/de/terms)
* [Lizenz](/content/licenses/LICENSE%20V2.txt)

### Haftungsausschluss

Die Suche nach möglichen Übereinstimmungen kann sehr schnell eine sehr große Anzahl von Kombinationen erreichen. Wenn es zum Beispiel 100 Zahlen gibt und die mögliche Gesamtsumme durch Addition von 1 bis 6 Zahlen aus diesen 100 erreicht werden soll, dann gibt es bis zu 1.271.427.895 Kombinationen, die geprüft werden könnten. Das bedeutet, dass die Berechnung je nach Datenmenge sehr lange dauern oder sogar fehlschlagen kann.

Sie können die Demo-Anwendung für einige Tests verwenden, um zu sehen, ob die Software Ihre Anforderungen erfüllt.

> Die Demo-Anwendung prüft nur bis zu 30 Zahlen mit maximal 4 Kombinationen.
