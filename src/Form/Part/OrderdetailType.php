<?php
/**
 * This file is part of Part-DB (https://github.com/Part-DB/Part-DB-symfony).
 *
 * Copyright (C) 2019 - 2022 Jan Böhmer (https://github.com/jbtronics)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace App\Form\Part;

use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Parts\MeasurementUnit;
use App\Entity\Parts\Supplier;
use App\Entity\PriceInformations\Orderdetail;
use App\Entity\PriceInformations\Pricedetail;
use App\Form\Type\StructuralEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderdetailType extends AbstractType
{
    public function __construct(protected Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('supplierpartnr', TextType::class, [
            'label' => 'orderdetails.edit.supplierpartnr',
            'attr' => [
                'placeholder' => 'orderdetails.edit.supplierpartnr.placeholder',
            ],
            'required' => false,
            'empty_data' => '',
        ]);

        $builder->add('supplier', StructuralEntityType::class, [
            'class' => Supplier::class,
            'disable_not_selectable' => true,
            'label' => 'orderdetails.edit.supplier',
            'allow_add' => $this->security->isGranted('@suppliers.create'),
        ]);

        $builder->add('supplier_product_url', UrlType::class, [
            'required' => false,
            'empty_data' => '',
            'label' => 'orderdetails.edit.url',
        ]);

        $builder->add('obsolete', CheckboxType::class, [
            'required' => false,
            'label' => 'orderdetails.edit.obsolete',
        ]);

        //Add pricedetails after we know the data, so we can set the default currency
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options): void {
            /** @var Orderdetail $orderdetail */
            $orderdetail = $event->getData();

            $dummy_pricedetail = new Pricedetail();
            if ($orderdetail instanceof Orderdetail && $orderdetail->getSupplier() instanceof Supplier) {
                $dummy_pricedetail->setCurrency($orderdetail->getSupplier()->getDefaultCurrency());
            }

            //Attachment section
            $event->getForm()->add('pricedetails', CollectionType::class, [
                'entry_type' => PricedetailType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'reindex_enable' => true,
                'prototype_data' => $dummy_pricedetail,
                'by_reference' => false,
                'entry_options' => [
                    'measurement_unit' => $options['measurement_unit'],
                ],
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orderdetail::class,
            'error_bubbling' => false,
        ]);

        $resolver->setRequired('measurement_unit');
        $resolver->setAllowedTypes('measurement_unit', [MeasurementUnit::class, 'null']);
    }
}
