<?php
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator;
use xis\Translation\Translator;

ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php';

/**
 * @param TranslatorInterface $translator
 * @param TwigRendererEngine $formEngine
 *
 * @return Twig_Environment
 */
function loadTwig(TranslatorInterface $translator, TwigRendererEngine $formEngine)
{
    $loader = new Twig_Loader_Filesystem(array(__DIR__ . '/templates', __DIR__ . '/templates/form'));
    $twig = new Twig_Environment($loader, array(
        'cache' => __DIR__ . '/cache',
        'debug' => true,
        'auto_reload' => true
    ));
    $twig->addExtension(new FormExtension(new TwigRenderer($formEngine)));
    $twig->addExtension(new TranslationExtension($translator));
    return $twig;
}

$defaultFormTheme = 'my.html.twig';
$formEngine = new TwigRendererEngine(array($defaultFormTheme));
$translator = new Translator();
$twig = loadTwig($translator, $formEngine);

$metadataFactory = new ClassMetadataFactory();
$validatorFactory = new ConstraintValidatorFactory();
$factory = Forms::createFormFactoryBuilder()
    ->addExtension(new ValidatorExtension(new Validator($metadataFactory, $validatorFactory, $translator)))
    ->getFormFactory();
$form = $factory->create(new xis\Form\UserType(), new xis\Entity\User());

if (!empty($_POST['user'])) {
    $form->submit($_POST['user']);
    if ($form->isValid()) {
        echo 'VALID!';
    }
}

$formView = $form->createView();
echo $twig->render('index.html.twig', array('name' => 'foo', 'form' => $formView));