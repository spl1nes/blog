# Test Report Generator

Automatically generates a localized report from test outputs

<div class="splash">
    <img alt="Splash" src="/content/solutions/finished/TestReportGenerator/img/TestReportGenerator_splash.png">
    <div class="price">Price: EUR 0.00</div>
    <div class="purchase">
        <a class="button" rel="download" type="application/pdf" target="_blank" href="https://raw.githubusercontent.com/Karaka-Management/TestReportGenerator/master/tests/TestReport.pdf">Demo</a>
        <a class="button" rel="download" type="application/zip" href="https://github.com/Karaka-Management/TestReportGenerator/archive/refs/heads/master.zip">Download</a>
    </div>
</div>

## Summary

The Test Report Generator is a php script which creates a report from PHPUnit tests and other testing tools. This report can be helpful for software releases and software audits to show what tests were performed and what their results were. The generated output is html and css and can be styled with custom themes as desired. The report is ideal to ship with software as proof for customers that the software (i.e. updates) have been tested and perform as expected.

## Features

* Custom report template defined in html and css
* Custom test and report localizations
* Custom command line arguments which can be used in the report
* Manually define which tests should be shown in the report
* Report PHPUnit test results (input required: junit)
* Report code coverage results (format: coverage-clover)
* Report PHPStan results (format: junit)
* Report PHPCS results (format: junit)
* Report ESLint results (format: junit)

## Dependencies

* PHP Version 8.0 or newer
* Test results from PHPUnit in junit format
* Code coverage report in coverage-clover format (optional)
* PHPStan report in json format (optional)
* PHPCS report in junit format (optional)
* ESLint report in junti format (optional)

## Legal

* [Terms of Service](/en/terms)
* [License](/content/licenses/LICENSE%20MIT%20V1.txt)

## References

* [Installation & usage](https://github.com/Karaka-Management/TestReportGenerator/blob/master/README.md)
