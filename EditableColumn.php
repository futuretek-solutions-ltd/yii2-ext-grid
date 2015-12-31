<?php

/**
 * @author    Petr Leo Compel <petr.compel@futuretek.cz>
 * @version   1.0.0
 */

namespace futuretek\grid;

use Yii;
use Closure;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\base\Config;

/**
 * The EditableColumn converts the data to editable using the Editable widget [[\kartik\editable\Editable]]
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class EditableColumn extends \kartik\grid\EditableColumn
{

    /**
     * Renders the data cell content.
     *
     * @param mixed   $model the data model
     * @param mixed   $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the models array returned by
     *     [[GridView::dataProvider]].
     *
     * @return string the rendering result
     * @throws InvalidConfigException
     */
    public function renderDataCellContent($model, $key, $index)
    {
        $readonly = $this->readonly;
        if ($readonly instanceof Closure) {
            $readonly = call_user_func($readonly, $model, $key, $index, $this);
        }
        if ($readonly === true) {
            return parent::renderDataCellContent($model, $key, $index);
        }
        $this->_editableOptions = $this->editableOptions;
        if (!empty($this->editableOptions) && $this->editableOptions instanceof Closure) {
            $this->_editableOptions = call_user_func($this->editableOptions, $model, $key, $index, $this);
        }
        if (!is_array($this->_editableOptions)) {
            $this->_editableOptions = [];
        }
        $options = ArrayHelper::getValue($this->_editableOptions, 'containerOptions', []);
        Html::addCssClass($options, $this->_css);
        $this->_editableOptions['containerOptions'] = $options;
        if ($this->grid->pjax && empty($this->_editableOptions['pjaxContainerId'])) {
            $this->_editableOptions['pjaxContainerId'] = $this->grid->pjaxSettings['options']['id'];
        }
        $strKey = $key;
        if (empty($key)) {
            throw new InvalidConfigException("Invalid or no primary key found for the grid data.");
        } elseif (!is_string($key) && !is_numeric($key)) {
            $strKey = serialize($key);
        }
        if ($this->attribute !== null) {
            $this->_editableOptions['model'] = $model;
            $this->_editableOptions['attribute'] = "[{$index}]{$this->attribute}";
        } elseif ((empty($this->_editableOptions['name']) && empty($this->_editableOptions['model'])) || (!empty($this->_editableOptions['model']) && empty($this->_editableOptions['attribute']))) {
            throw new InvalidConfigException(
                "You must setup the 'attribute' for your EditableColumn OR set one of 'name' OR 'model' & 'attribute'" .
                " in 'editableOptions' (Exception at index: '{$index}', key: '{$strKey}')."
            );
        }
        $val = $this->getDataCellValue($model, $key, $index);
        if (!isset($this->_editableOptions['displayValue']) && $val !== null && $val !== '') {
            $this->_editableOptions['displayValue'] = parent::renderDataCellContent($model, $key, $index);
        }
        $params = Html::hiddenInput('editableIndex', $index) . Html::hiddenInput('editableKey', $strKey) .
            Html::hiddenInput('editableAttribute', $this->attribute);
        if (empty($this->_editableOptions['beforeInput'])) {
            $this->_editableOptions['beforeInput'] = $params;
        } else {
            $output = $this->_editableOptions['beforeInput'];
            $this->_editableOptions['beforeInput'] = function ($form, $widget) use ($output, $params) {
                if ($output instanceof Closure) {
                    return $params . call_user_func($output, $form, $widget);
                } else {
                    return $params . $output;
                }
            };
        }

        if (array_key_exists('value', $this->_editableOptions) && $this->_editableOptions['value'] instanceof Closure) {
            $this->_editableOptions['value'] = call_user_func($this->_editableOptions['value'], $model, $key, $index);
        }

        if ($this->refreshGrid) {
            $id = $this->grid->options['id'];
            $this->_view->registerJs("kvRefreshEC('{$id}','{$this->_css}');");
        }
        return Editable::widget($this->_editableOptions);
    }
}
