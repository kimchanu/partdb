<?php

declare(strict_types=1);

/*
 * This file is part of Part-DB (https://github.com/Part-DB/Part-DB-symfony).
 *
 *  Copyright (C) 2019 - 2023 Jan Böhmer (https://github.com/jbtronics)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
namespace App\Form\Type;

use App\Entity\Parts\StorageLocation;
use App\Entity\Parts\Part;
use App\Entity\Parts\PartLot;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartLotSelectType extends AbstractType
{
    public function getParent(): string
    {
        return EntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('part');
        $resolver->setAllowedTypes('part', Part::class);

        $resolver->setDefaults([
            'class' => PartLot::class,
            'choice_label' => ChoiceList::label($this, static fn(PartLot $part_lot): string => ($part_lot->getStorageLocation() instanceof StorageLocation ? $part_lot->getStorageLocation()->getFullPath() : '')
                . ' (' . $part_lot->getName() . '): ' . $part_lot->getAmount()),
            'query_builder' => fn(Options $options) => static fn(EntityRepository $er) => $er->createQueryBuilder('l')
                ->where('l.part = :part')
                ->setParameter('part', $options['part'])
        ]);
    }
}
