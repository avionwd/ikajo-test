<?php
/**
 * MessageGrid file
 *
 * @author Oleksandr Muzychenko <avionwd@gmail.com>
 */

namespace app\grids;

use app\models\Message;
use DataTables\DataTable;
use Phalcon\Di;

/**
 * Class MessageGrid
 *
 * @package app\grids
 */
class MessageGrid extends Di\Injectable
{
    /**
     * Send Ajax response to DataTables handler
     */
    public function sendResponse()
    {
        $dataTables = new DataTable();
        $dataTables->fromBuilder($this->getQuery())->sendResponse();
    }

    /**
     * @return Message|\app\models\Message[]
     */
    protected function getQuery()
    {
        return $this->getDI()->get('modelsManager')->createBuilder()
            ->columns('id, name, phone, email, message, date_create, date_update')
            ->from('app\models\Message');
    }
}