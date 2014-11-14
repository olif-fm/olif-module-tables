<?php
/**
 * ControllerTables
 * @version V 2.7
 * @copyright Alberto Vara (https://github.com/avara1986) & Jose Luis Represa (https://github.com/josex2r)
 * @package OLIF.ControllerTables
 *
 *
 * TODO: integrar en el sistema y no como módulo
 *
 * DEPENDS/REQUIRED:
 * + controllers/ControllerApp
 * + controllers/getControllerSession
 * + controllers/getControllerPage
 * + controllers/getControllerRequest
 */
namespace Olif;

require_once CORE_ROOT . CONTROLLERS . DIRECTORY_SEPARATOR . "ControllerApp.php";

class ControllerTables extends ControllerApp
{

    private $opFilters = array();

    private $nameSpace;

    private $queryFilter;

    private $sortField;

    private $sortOrder;

    public function setOpFilters($filters = array())
    {
        $this->opFilters = $filters;
    }

    public function setNameSpace($nameSpace)
    {
        $this->nameSpace = $nameSpace;
    }

    public function setFilters($field, $order)
    {
        $this->getControllerSession();
        $this->getControllerPage();
        $this->sortField = $this->session->get('sortField_' . $this->nameSpace);
        $this->sortOrder = $this->session->get('sortOrder_' . $this->nameSpace);
        if (strlen($field) > 0) {
            if (array_key_exists($field, $this->opFilters)) {
                if ($this->sortField == $field) {
                    if (strlen($order) == 0) {
                        $this->sortOrder = $this->session->get('sortOrder_' . $this->nameSpace) == "asc" ? "desc" : "asc";
                    } else {
                        $this->sortOrder = $order;
                    }
                }
                $this->session->set('sortOrder_' . $this->nameSpace, $this->sortOrder);

                $this->sortField = $field;
                $this->session->set('sortField_' . $this->nameSpace, $this->sortField);
            }
        }
        $this->page->assignVar("SORT_FIELD", $this->sortField);
        $this->page->assignVar("SORT_ORDER", $this->sortOrder);
        ;
    }

    public function getFilterQuery()
    {
        $this->getControllerSession();
        if (strlen($this->sortField) == 0)
            $this->sortField = $this->session->get('sortField_' . $this->nameSpace);
        if (strlen($this->sortOrder) == 0)
            $this->sortOrder = $this->session->get('sortOrder_' . $this->nameSpace);
        if (strlen($this->sortField) > 0 && array_key_exists($this->sortField, $this->opFilters)) {
            return ($this->sortField != "") ? "ORDER BY " . $this->opFilters[$this->sortField] . " $this->sortOrder" : "";
        }
    }

    public function setPagination($info)
    {
        $this->getControllerPage();
        $this->page->assignVar("total_results", $info['num_elements']);
        if (strlen($info['prev_page']) > 0 && $info['prev_page'] + 1 > '0') {
            $this->page->assignVar("PREV_PAGE", $info['prev_page'] + 1);
        } else {
            $this->page->assignVar("PREV_PAGE", "");
        }
        @$num_blocks_prev = count($info['blocks_prev']);
        for ($i = 0; $i < $num_blocks_prev; $i ++) {
            $this->page->assignList('block_prev', array(
                'PAG' => ($info['blocks_prev'][$i] + 1)
            ));
        }

        $this->page->assignVar("actual_page", $info['actual_page'] + 1);

        if (strlen($info['next_page']) > 0 && $info['next_page'] < $info['num_pages']) {
            $this->page->assignVar("NEXT_PAGE", $info['next_page'] + 1);
        } else {
            $this->page->assignVar("NEXT_PAGE", "");
        }

        @$num_blocks_next = count($info['blocks_next']);
        for ($i = 0; $i < $num_blocks_next; $i ++) {
            $this->page->assignList('block_next', array(
                'PAG' => ($info['blocks_next'][$i] + 1)
            ));
        }
    }

    public function getPag()
    {
        $this->getControllerRequest();
        $pag = $this->req->getVar('p');
        if (strlen($pag) == 0 || $pag < 0)
            $pag = 0;
        else
            if (($pag - 1) >= 0)
                $pag = $pag - 1;
        return $pag;
    }

    public function getTotalPerPage($default = 10)
    {
        $this->getControllerRequest();
        $this->getControllerPage();
        $this->getControllerSession();
        $total_per_page = $this->req->getVar('total_per_page');
        if (strlen($total_per_page) > 0) {
            $this->session->set('total_per_page_pages', $total_per_page);
        } else {
            $total_per_page = $this->session->get('total_per_page_pages');
        }
        if (strlen($total_per_page) == 0 || $total_per_page < 1) {
            $total_per_page = $default;
        }
        $this->page->assignVar("total_per_page", $total_per_page);
        $this->page->assignVar("TOTAL_PER_PAGE", $total_per_page);
        return $total_per_page;
    }
}