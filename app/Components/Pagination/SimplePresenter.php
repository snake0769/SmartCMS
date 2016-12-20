<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/12
 * Time: 10:17
 */

namespace app\Components\Pagination;


use Illuminate\Pagination\BootstrapThreePresenter;
use Illuminate\Support\HtmlString;

class SimplePresenter extends BootstrapThreePresenter
{

    const PREV_TEXT = '上一页';
    const NEXT_TEXT = '下一页';

    public function render()
    {
        if ($this->hasPages()) {
            $pageInfoHtml = sprintf(
                '<div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">显示 %s 到 %s ，共 %s 条</div>',
                $this->paginator->firstItem(),
                $this->paginator->lastItem(),
                $this->paginator->total()
            );
            $paginationHtml = sprintf(
                '<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">%s %s %s</div>',
                $this->getPreviousButton(self::PREV_TEXT),
                $this->getLinks(),
                $this->getNextButton(self::NEXT_TEXT)
            );
            return new HtmlString($pageInfoHtml.$paginationHtml);
        }
        // <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
        //<a class="paginate_button previous disabled" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0" id="DataTables_Table_0_previous">上一页</a>
        //<span><a class="paginate_button current" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0">1</a></span>
        //<a class="paginate_button next disabled" aria-controls="DataTables_Table_0" data-dt-idx="2" tabindex="0" id="DataTables_Table_0_next">下一页</a>
        //</div>
    }

    protected function getDisabledTextWrapper($text)
    {
        return '<a class="paginate_button disabled" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0">'.$text.'</a>';
    }

    protected function getActivePageWrapper($text)
    {
        return '<a class="paginate_button current" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0">'.$text.'</a>';
    }

    protected function getAvailablePageWrapper($url, $page, $rel = null)
    {
        $rel = is_null($rel) ? '' : ' rel="'.$rel.'"';
        $html = '<span><a class="paginate_button %s" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" href="%s" ref="%s">%s</a></span>';
        if($rel == 'prev'){
            $html = sprintf($html,'previous',$url,$rel,$page);
        }else if($rel == 'next'){
            $html = sprintf($html,'next',$url,$rel,$page);
        }else{
            $html = sprintf($html,'',$url,$rel,$page);
        }
        return $html;
    }


}