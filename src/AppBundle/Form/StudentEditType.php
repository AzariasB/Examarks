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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of StudentEditType
 *
 * @author azarias
 */
class StudentEditType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $common = [
            'required' => false,
            'attr' => ['class' => 'form-control']
        ];

        $builder
                ->add('email', EmailType::class, $common)
                ->add('name', TextType::class, $common)
                ->add('lastName', TextType::class, $common)
                ->add('phone', TextType::class, [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'pattern' => '^\+?[0-9 _-]+$',
                        'title' => 'Valid phone number'
                    ]
                ])
                ->add('address', TextType::class, $common)
                ->add('submit', SubmitType::class, [
                    'label' => 'Save modifications',
                    'attr' => ['class' => 'btn btn-primary']
        ]);
    }

}
