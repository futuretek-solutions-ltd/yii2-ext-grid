<?php
/**
 * @author    Petr Leo Compel <petr.compel@futuretek.cz>
 * @version   1.0.0
 */

namespace futuretek\grid;

use himiklab\sortablegrid\SortableGridAsset;
use yii\helpers\Url;

/**
 * Class GridView
 *
 * @package futuretek\grid
 */
class GridView extends \kartik\grid\GridView
{
    /** @var string|array Sort action */
    public $sortableAction = ['sort'];
    public $sortable = false;

    public function init()
    {
        parent::init();
        if ($this->sortable) {
            $this->sortableAction = Url::to($this->sortableAction);
        }
    }

    public function run()
    {
        if ($this->sortable) {
            $this->registerWidget();
        }
        parent::run();
    }

    protected function registerWidget()
    {
        $view = $this->getView();
        $view->registerJs("jQuery('#{$this->options['id']}').SortableGridView('{$this->sortableAction}');");
        SortableGridAsset::register($view);
    }
}