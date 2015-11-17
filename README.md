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

Sortable
--------

* Add to your database new `unsigned int` attribute, such `position`.

* Add new behavior in the AR model, for example:

```php
use himiklab\sortablegrid\SortableGridBehavior;

public function behaviors()
{
    return [
        'sort' => [
            'class' => SortableGridBehavior::className(),
            'sortableAttribute' => 'position'
        ],
    ];
}
```

* Add action in the controller, for example:

```php
use himiklab\sortablegrid\SortableGridAction;

public function actions()
{
    return [
        'sort' => [
            'class' => SortableGridAction::className(),
            'modelName' => Model::className(),
        ],
    ];
}
```

* Add parameter `sortable` to GridView widget config

* Modify default `sortableAction` if needed

```php
\futuretek\grid\GridView::widget([
    'sortable' => true, 
    'sortableAction' => 'sortMyGridBitch'
]);
```

* You can also subscribe to the JS event 'sortableSuccess' generated widget after a successful sorting.