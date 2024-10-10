<?php

namespace App\Services\Form;

use Symfony\Component\Form\FormView;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('form_button', [$this, 'form_button']),
        ];
    }

    public function form_button(
        FormView $form,
        array $options = []
    ): Markup {
        return new Markup($this->twig->render('common/form_button.html.twig', $options), 'UTF-8');
    }
}
