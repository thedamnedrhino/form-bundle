<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\JQuery\Type;

use Genemu\Bundle\FormBundle\Form\JQuery\DataTransformer\ArrayToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Select2HiddenType to JQueryLib
 *
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 * @author Chris Tickner <chris.tickner@gmail.com>
 * @author Benjamin Schumacher <benschumi@hotmail.fr>
 */
class Select2HiddenType extends AbstractType
{
    private $configs;

    public function __construct(array $configs = array())
    {
        $this->configs = $configs;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!empty($options['configs']['multiple'])) {
            $builder->addViewTransformer(new ArrayToStringTransformer());
        } elseif (empty($options['configs']['multiple']) && null !== $options['transformer']) {
            $builder->addModelTransformer($options['transformer']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['configs'] = $options['configs'];

        // Adds a custom block prefix
        array_splice(
            $view->vars['block_prefixes'],
            array_search($this->getBlockPrefix(), $view->vars['block_prefixes']),
            0,
            'genemu_jqueryselect2'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaults = $this->configs;
        $resolver
            ->setDefaults(array(
                'configs'       => $defaults,
                'transformer'   => null,
            ))
            ->setNormalizer(
                'configs',
                function (Options $options, $configs) use ($defaults) {
                    return array_merge($defaults, $configs);
                }
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\HiddenType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'genemu_jqueryselect2_hidden';
    }
}
