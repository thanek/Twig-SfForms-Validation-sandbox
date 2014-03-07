<?php
namespace xis\Form;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class TypeChangeSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SUBMIT => 'preSubmit');
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        if (!empty($data['some_val_type'])) {
            $form = $event->getForm();
            $this->addFieldValidator('some_val_value', $form);
        }
    }

    /**
     * @param string $fieldName
     * @param FormInterface $form
     */
    private function addFieldValidator($fieldName, $form)
    {
        $field = $form->get($fieldName);
        $type = $field->getConfig()->getType()->getName();
        $options = $field->getConfig()->getOptions();
        $options['label'] = 'Numeric value';
        $options['constraints'][] = new Range(array('min' => 30, 'max' => 100));
        $form->add($fieldName, $type, $options);
    }
}