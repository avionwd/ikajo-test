<?php
/**
 * MessageController file
 *
 * @author Oleksandr Muzychenko <avionwd@gmail.com>
 */

namespace app\controllers;

use app\forms\MessageForm;
use app\grids\MessageGrid;
use app\models\Message;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

/**
 * Class MessageController
 *
 * @package app\controllers
 */
class MessageController extends Controller
{
    /**
     * Action to migrate base database structure for application
     */
    public function migrateAction()
    {
        if ($this->request->get('start')) {
            $baseDir = $this->config->application->baseDir;
            $cmd = 'vendor/bin/phalcon.php migration run';

            $log = $this->config->application->baseDir . '/logs/migration.log';
            $PID = shell_exec("cd $baseDir && nohup $cmd > $log 2>&1 & echo $!");

            $process_is_running = function ($pid) {
                exec("ps $pid", $ProcessState);
                return(count($ProcessState) >= 2);
            };

            while($process_is_running($PID))
            {
                sleep(1);
            }

            $output = file_get_contents($log);

            $this->view->output = $output;
        }
    }

    /**
     * List all messages from database
     */
    public function indexAction()
    {
        try {
            Message::findFirst();
        } catch (\Exception $e) {
            $this->response->redirect('/message/migrate');
            return;
        }

        $grid = new MessageGrid($this->getDI());

        if ($this->request->isAjax()) {
            $grid->sendResponse();
        }

        $this->view->grid = $grid;
    }

    /**
     * Create message
     */
    public function createAction()
    {
        $this->view->disableLevel(\Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT);

        $form = new MessageForm(new Message());
        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost()) || !$form->save()) {
                $this->response->setStatusCode(400, 'Errors found');
            }
        }

        $this->view->form = $form;
    }

    /**
     * Update existing message
     * @param int $id message ID
     */
    public function updateAction($id)
    {
        $this->view->disableLevel(\Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT);

        $message = Message::findFirst($id);
        $form = new MessageForm($message);

        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost()) || !$form->save()) {
                $this->response->setStatusCode(400, 'Errors found');
            }
        }

        $this->view->form = $form;
    }

    /**
     * Delete message from database
     * @param int $id the message ID
     * @return Response
     * @throws \HttpException
     */
    public function deleteAction($id)
    {
        if (!$this->request->isAjax()) {
            throw new \HttpException(400, 'Delete available only with ajax request');
        }

        $this->view->disable();

        $response = $this->getResponse();

        $message = Message::findFirst($id);
        if (!$message) {
            $response->setContent(
                $this->prepareJson([
                    'error' => [
                        'code' => 404,
                        'message' => 'Not found'
                    ]
                ]));
            $response->setStatusCode(404, 'Not found');

            return $response->send();
        }

        if ($message->delete()) {
            $response->setStatusCode(200);
            $response->setContent(
                $this->prepareJson([
                    'message' => "Message deleted from database"
                ]));
        }
        else {
            $response->setStatusCode(500);
            $response->setContent(
                $this->prepareJson([
                    'error' => [
                        'code' => 500,
                        'message' => "Message can not be deleted"
                    ]
                ]));
        }

        return $response->send();
    }

    /**
     * @return Response
     */
    protected function getResponse()
    {
        $response = new Response();
        $response->setContentType('application/json', 'UTF-8');

        return $response;
    }

    /**
     * @param array $data array to decode into JSON string
     * @return mixed
     * @throws \Phalcon\Exception
     */
    protected function prepareJson($data)
    {
        $data = json_encode($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Phalcon\Exception('JSON decode error: ' . json_last_error_msg());
        }
        return $data;
    }
}