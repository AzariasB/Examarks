<?php

/*
 * The MIT License
 *
 * Copyright 2017 azarias.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Description of ModuleEditType
 *
 * @author azarias
 */
class ModuleEditType extends AbstractType {

    //put your code here
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $typeOptions = [
            'required' => false,
            'attr' => ['class' => 'form-control']
        ];

        $builder
                ->add('number', IntegerType::class, $typeOptions)
                ->add('name', TextType::class, $typeOptions)
                ->add('abbreviation', TextType::class, $typeOptions)
                ->add('students', EntityType::class, [
                    'class' => \AppBundle\Entity\Student::class,
                    'choice_label' => 'login',
                    'multiple' => true,
                    'expanded' => true
        ]);
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => \AppBundle\Entity\Module::class
        ]);
    }

}
