<?php
/**
 * MessageForm file
 *
 * @author Oleksandr Muzychenko <avionwd@gmail.com>
 */

namespace app\forms;

use app\library\validators\PhoneValidator;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

/**
 * Class MessageForm
 *
 * @package app\forms
 */
class MessageForm extends Form
{
    /**
     * @inheritdoc
     */
    public function initialize()
    {
        $name = new Text('name', ['class' => 'form-control']);
        $name
            ->setLabel('Name')
            ->addValidator(new PresenceOf())
            ->addValidator(new StringLength([
                'min' => 3,
                'max' => 64
            ]))
            ->setFilters(['string', 'trim']);
        $this->add($name);

        $phone = new Text('phone');
        $phone
            ->setLabel('Phone number')
            ->addValidator(new PresenceOf())
            ->addValidator(new PhoneValidator())
            ->setFilters(['trim']);
        $this->add($phone);

        $email = new Email('email');
        $email
            ->setLabel('Email')
            ->addValidator(new PresenceOf());
        $this->add($email);

        $message = new TextArea('message');
        $message
            ->setLabel('Message')
            ->addValidator(new PresenceOf())
            ->addValidator(new StringLength([
                'min' => 3,
                'max' => 200
            ]))
            ->setFilters(['string', 'trim']);
        $this->add($message);
    }

    /**
     * @return mixed
     */
    public function save()
    {
        return $this->getEntity()->save();
    }

    /**
     * @param string $name attribute name
     * @return string
     */
    public function renderDecorated($name)
    {
        $element = $this->get($name);

        $class = $element->getAttribute('class');
        if (stripos($class, 'form-control') === false) {
            $class = 'form-control ' . $class;
        }
        $element->setAttribute('class', $class);

        // Get any generated messages for the current element
        $messages = $this->getMessagesFor($element->getName());
        $has_errors = count($messages);


        $html = '<div class="form-group' . ($has_errors ? ' has-error' : null) . '">';
        $html .= '<label for="' . $element->getName() . '">' . $element->getLabel() . '</label>';
        $html .= $element;
        if ($has_errors) {
            // Print each element
            $errors = [];
            foreach ($messages as $message) {
                $errors[] = '<div class="help-block">'.$message.'</div>';
            }
            $html .= implode('', $errors);
        }
        $html .= '</div>';

        return $html;
    }
}