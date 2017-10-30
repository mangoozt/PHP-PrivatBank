# PHP-PrivatBank
Проект реалізує взаємодію із ПриватБанком відповідно до ["специфікації протоколу обміну даними 
про заборгованість та прийнятих платежів між підприємством та банком в режимі on-line"](https://docs.google.com/document/d/1JrH84x2p4FOjm89q3xArvnEfsFXRnbIoa6qJFNq2VYw/edit?pli=1)

***

## Реалізовані методи взаємодії
+ [Presearch](actions/presearch.php) - попередіній пошук платника (за особовим рахунком, логіном (ресурсом) або адресою)
+ [Search](actions/search.php) - основний пошук палатника після попереднього (за особовим рахунком)
+ [Check](actions/check.php) - перевірка можливості вводу платежу
+ [Pay](actions/pay.php) - здійснення (підтвердження) платежу
+ [Cancel](actions/cancel.php) - скасування платежу

***

## Використання
У файлі [`pb.php`](pb.php):

1. `require_once '/classes/pbDemo.class.php';` замінити на власний клас, який імплементує інтерфейс [`pb.class.php`](classes/pb.class.php)
2. `$pbAdapter = new pbDemo();` замінити викликом власного конструктора - `$pbAdapter = new myPbAdapter(...);`
3. додати в перелік `$allowedIps` відповідну IP-адресу (надається ПриватБанком)

***

# Demo проекту

[SANDBOX](http://pb-sandbox.isp-service24.com/)

[Код пісочниці](https://github.com/vPolyovyj/pb_payer)
