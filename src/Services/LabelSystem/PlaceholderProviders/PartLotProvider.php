<?php
/*
 * This file is part of Part-DB (https://github.com/Part-DB/Part-DB-symfony).
 *
 *  Copyright (C) 2019 - 2022 Jan Böhmer (https://github.com/jbtronics)
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

declare(strict_types=1);

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

namespace App\Services\LabelSystem\PlaceholderProviders;

use App\Entity\Parts\StorageLocation;
use App\Entity\UserSystem\User;
use App\Entity\Parts\PartLot;
use App\Services\Formatters\AmountFormatter;
use App\Services\LabelSystem\LabelTextReplacer;
use IntlDateFormatter;
use Locale;

/**
 * @see \App\Tests\Services\LabelSystem\PlaceholderProviders\PartLotProviderTest
 */
final class PartLotProvider implements PlaceholderProviderInterface
{
    public function __construct(private readonly LabelTextReplacer $labelTextReplacer, private readonly AmountFormatter $amountFormatter)
    {
    }

    public function replace(string $placeholder, object $label_target, array $options = []): ?string
    {
        if ($label_target instanceof PartLot) {
            if ('[[LOT_ID]]' === $placeholder) {
                return (string) ($label_target->getID() ?? 'unknown');
            }

            if ('[[LOT_NAME]]' === $placeholder) {
                return $label_target->getName();
            }

            if ('[[LOT_COMMENT]]' === $placeholder) {
                return $label_target->getComment();
            }

            if ('[[EXPIRATION_DATE]]' === $placeholder) {
                if (!$label_target->getExpirationDate() instanceof \DateTimeInterface) {
                    return '';
                }
                $formatter = IntlDateFormatter::create(
                    Locale::getDefault(),
                    IntlDateFormatter::SHORT,
                    IntlDateFormatter::NONE
                );

                return $formatter->format($label_target->getExpirationDate());
            }

            if ('[[AMOUNT]]' === $placeholder) {
                if ($label_target->isInstockUnknown()) {
                    return '?';
                }

                return $this->amountFormatter->format($label_target->getAmount(), $label_target->getPart()->getPartUnit());
            }

            if ('[[LOCATION]]' === $placeholder) {
                return $label_target->getStorageLocation() instanceof StorageLocation ? $label_target->getStorageLocation()->getName() : '';
            }

            if ('[[LOCATION_FULL]]' === $placeholder) {
                return $label_target->getStorageLocation() instanceof StorageLocation ? $label_target->getStorageLocation()->getFullPath() : '';
            }

            if ('[[OWNER]]' === $placeholder) {
                return $label_target->getOwner() instanceof User ? $label_target->getOwner()->getFullName() : '';
            }

            if ('[[OWNER_USERNAME]]' === $placeholder) {
                return $label_target->getOwner() instanceof User ? $label_target->getOwner()->getUsername() : '';
            }

            return $this->labelTextReplacer->handlePlaceholder($placeholder, $label_target->getPart());
        }

        return null;
    }
}
