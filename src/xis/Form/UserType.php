<?php
namespace xis\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Your name',
                    'constraints' => new Length(
                            array('max' => 10,
                                'maxMessage' => 'Value too long, expected at least {{ limit }} chars!')
                        )
                )
            )
            ->add('email', 'text', array('label' => 'Your email',
                    'constraints' => new Length(
                            array('max' => 10,
                                'maxMessage' => 'Value too long, expected at least {{ limit }} chars!')
                        )
                )
            )
            ->add('some_val_type', 'checkbox', array('label' => 'Should next val be numeric?',
                    'required' => false, 'mapped' => false)
            )
            ->add('some_val_value', 'text', array('label' => 'Some value', 'mapped' => false));

        $builder->addEventSubscriber(new TypeChangeSubscriber());
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'user';
    }
}