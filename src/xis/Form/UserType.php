<?php
namespace xis\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text',
                array(
                    'label' => 'Your name',
                    'constraints' => new Length(
                            array('min' => 10,
                                'minMessage' => 'Value too short, expected at least {{ limit }} chars!')
                        )
                )
            )
            ->add('email', 'text', array(
                    'label' => 'Your email',
                    'constraints' => new Length(
                            array('min' => 10,
                                'minMessage' => 'Value too short, expected at least {{ limit }} chars!')
                        )
                )
            )
            ->add('job', new JobType(), array(
                    'mapped' => false
                )
            );
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