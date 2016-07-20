<?php

namespace darkcs\infinitescroll;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\widgets\LinkPager;

class InfiniteScrollPager extends LinkPager
{
    public $containerSelector = '.list-view';
    public $itemSelector = '.item';
    public $paginationSelector = '.pagination';
    public $nextSelector = '.pagination .next a:first';
    public $wrapperSelector = '.list-view';
    public $bufferPx = 40;
    public $pjaxContainer = null;
    public $autoStart = true;
    public $alwaysHidePagination = true;

    // опции jquery плагина напрямую
    public $pluginOptions = [];

    public function init()
    {
        $default = [
            'pagination' => $this->paginationSelector,
            'next' => $this->nextSelector,
            'item' => $this->itemSelector,
            'state' => [
                'isPaused' => !$this->autoStart,
            ],
            'pjax' => [
                'container' => $this->pjaxContainer,
            ],
            'bufferPx' => $this->bufferPx,
            'wrapper' => $this->wrapperSelector,
            'alwaysHidePagination' => $this->alwaysHidePagination,
        ];

        $this->pluginOptions = ArrayHelper::merge($default, $this->pluginOptions);

        InfiniteScrollAsset::register($this->view);
        $this->initInfiniteScroll();
        parent::init();
    }

    public function run()
    {
        parent::run();
    }

    public function initInfiniteScroll()
    {
        $options = Json::encode($this->pluginOptions);

        $js = "$('{$this->containerSelector}').infinitescroll({$options});";
        $this->view->registerJs($js);
    }

    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
            return Html::tag('li', Html::tag('span', $label), $options);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('li', Html::tag('span', $label), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        return Html::tag('li', Html::a($label, urldecode($this->pagination->createUrl($page)), $linkOptions), $options);
    }
}
