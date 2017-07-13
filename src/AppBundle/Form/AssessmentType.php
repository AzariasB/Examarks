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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use AppBundle\Entity\Assessment;

/**
 * Form description for an assessment
 *
 * @author azarias
 */
class AssessmentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('type', ChoiceType::class, [
                    'choices' => [
                        Assessment::ASSIGNMENT => Assessment::ASSIGNMENT,
                        Assessment::LAB_TEST => Assessment::LAB_TEST,
                        Assessment::WRITTEN_EXAM => Assessment::WRITTEN_EXAM
                    ],
                    'attr' => ['class' => 'form-control']
                ])
                ->add('weight', IntegerType::class, [
                    'attr' => [
                        'min' => 0,
                        'max' => 100,
                        'class' => 'form-control col-xs-12 col-sm-4'
                    ]
        ]);
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => \AppBundle\Entity\Assessment::class
        ]);
    }

}
