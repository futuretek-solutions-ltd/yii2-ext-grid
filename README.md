Yii2 FTS Grid
=============
Custom Futuretek Grid

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist futuretek/yii2-grid "*"
```

or add

```
"futuretek/yii2-grid": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
\futuretek\grid\GridView::widget(['dataProvider' => $dataProvider])
```
