<?php

namespace app\models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

/**
 * Class Message
 *
 * @package app\models
 */
class Message extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=64, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=24, nullable=true)
     */
    public $phone;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $message;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $date_create;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $date_update;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'message';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Message[]|Message
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Message
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @inheritdoc
     */
    public function afterCreate()
    {
        if (!$this->getDI()->has('mailer')) {
            return;
        }

        /** @var \Phalcon\Ext\Mailer\Manager $mailer */
        $mailer = $this->getDI()->get('mailer');

        // Send message to user
        $params = [
            'username' => $this->name
        ];
        $message = $mailer->createMessageFromView('user', $params)
            ->to($this->email, $this->name)
            ->subject('Thanks for you message');
        $message->send();

        // Send message to administrator
        if ($this->getDI()->get('config')->offsetExists('adminEmail')) {
            $message = $mailer->createMessageFromView('admin', $this->toArray())
                ->to($this->getDI()->get('config')->adminEmail)
                ->subject('New message on site');
            $message->send();
        }
    }
}
