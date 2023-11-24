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

namespace App\Form\AdminPages;

use App\Entity\Base\AbstractNamedDBElement;
use App\Entity\Parts\MeasurementUnit;
use App\Form\Type\StructuralEntityType;
use App\Form\Type\UserSelectType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class StorelocationAdminForm extends BaseEntityAdminForm
{
    protected function additionalFormElements(FormBuilderInterface $builder, array $options, AbstractNamedDBElement $entity): void
    {
        $is_new = null === $entity->getID();

        $builder->add('is_full', CheckboxType::class, [
            'required' => false,
            'label' => 'storelocation.edit.is_full.label',
            'help' => 'storelocation.edit.is_full.help',
            'disabled' => !$this->security->isGranted($is_new ? 'create' : 'edit', $entity),
        ]);

        $builder->add('limit_to_existing_parts', CheckboxType::class, [
            'required' => false,
            'label' => 'storelocation.limit_to_existing.label',
            'help' => 'storelocation.limit_to_existing.help',
            'disabled' => !$this->security->isGranted($is_new ? 'create' : 'edit', $entity),
        ]);

        $builder->add('only_single_part', CheckboxType::class, [
            'required' => false,
            'label' => 'storelocation.only_single_part.label',
            'help' => 'storelocation.only_single_part.help',
            'disabled' => !$this->security->isGranted($is_new ? 'create' : 'edit', $entity),
        ]);

        $builder->add('storage_type', StructuralEntityType::class, [
            'required' => false,
            'label' => 'storelocation.storage_type.label',
            'help' => 'storelocation.storage_type.help',
            'class' => MeasurementUnit::class,
            'disable_not_selectable' => true,
            'disabled' => !$this->security->isGranted($is_new ? 'create' : 'edit', $entity),
        ]);

        $builder->add('owner', UserSelectType::class, [
            'required' => false,
            'label' => 'storelocation.owner.label',
            'disabled' => !$this->security->isGranted($is_new ? 'create' : 'edit', $entity),
        ]);
        $builder->add('part_owner_must_match', CheckboxType::class, [
            'required' => false,
            'label' => 'storelocation.part_owner_must_match.label',
            'disabled' => !$this->security->isGranted($is_new ? 'create' : 'edit', $entity),
        ]);
    }
}
