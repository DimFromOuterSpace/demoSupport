<?php

namespace App\Form\Admin;

use App\Entity\Company;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class CompanyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, ['required' => true])
            ->add('mailContact', EmailType::class, ['required' => true])
            ->add('active', CheckboxType::class, ['required' => false])
            ->add('projects', Select2EntityType::class, [
                'multiple' => true,
                'remote_route' => 'admin_project_ajax_autocomplete',
                'class' => Project::class,
                'primary_key' => 'id',
                'text_property' => 'nom',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'placeholder' => 'Sélectionnez un projet',
                'allow_add' => [
                    'enabled' => true,
                    'new_tag_text' => ' (NEW)',
                    'new_tag_prefix' => '__',
                    'tag_separators' => '[",", ";"]',
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
