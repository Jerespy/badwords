# buchin/badwords
PHP bad words detector 

## Installation
```bash
composer require buchin/badwords dev-master
```

## Usage
```php
<?php
use Buchin\Badwords\Badwords;

Badwords::isDirty('Blood sugar sex magic');

/*
when string contains bad words, it returns true
Example result:
(boolean) true 
*/

Badwords::isDirtyNegate("You are not an asshole");
/*
When string contains a negator like not, aren't, etc before the offensive word
it returns 1
Output:
-1 means NOT FOUND
0 means found but no negator (const NEGATE) found before the offensive word 
1 means found with a negator (const NEGATE) before the offensive word  
*/

Badwords::strip('Blood sugar sex magic');

/*
given string contains bad words, it replaces vocal chars in bad word with asterix
Example result:
(string) "Blood sugar s*x magic" 
*/

```
