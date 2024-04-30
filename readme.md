# Degree-Day Calculator

The calculator offers two methods for calculating degree-days using daily temperatures and upper/lower cutoff(s):
 - `degreedays()` (alias `dd()`): calculates using the single-sine method [more info](http://ipm.ucanr.edu/WEATHER/ddconcepts.html)
 - `degreedaysAvg()`: less accurate, it uses an approximate formula using just the mean temperature

```php
use WsuDas\Degreedays\Calculator;

$dd = Calculator::degreedays($tmax, $tmin, $lower, $upper, $cutoff);
```
