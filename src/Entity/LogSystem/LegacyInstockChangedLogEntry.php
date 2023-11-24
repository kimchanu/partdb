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

namespace App\Entity\LogSystem;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class LegacyInstockChangedLogEntry extends AbstractLogEntry
{
    protected string $typeString = 'instock_changed';

    /**
     * Get the old instock.
     */
    public function getOldInstock(): int
    {
        return $this->extra['o'];
    }

    /**
     * Get the new instock.
     */
    public function getNewInstock(): int
    {
        return $this->extra['n'];
    }

    /**
     * Gets the comment associated with the instock change.
     */
    public function getComment(): string
    {
        return $this->extra['c'];
    }

    /**
     * Returns the price that has to be paid for the change (in the base currency).
     *
     * @param bool $absolute Set this to true, if you want only get the absolute value of the price (without minus)
     */
    public function getPrice(bool $absolute = false): float
    {
        if ($absolute) {
            return abs($this->extra['p']);
        }

        return $this->extra['p'];
    }

    /**
     * Returns the difference value of the change ($new_instock - $old_instock).
     *
     * @param bool $absolute set this to true if you want only the absolute value of the difference
     *
     * @return int difference is positive if instock has increased, negative if decreased
     */
    public function getDifference(bool $absolute = false): int
    {
        // Check if one of the instock values is unknown
        if (-2 === $this->getNewInstock() || -2 === $this->getOldInstock()) {
            return 0;
        }

        $difference = $this->getNewInstock() - $this->getOldInstock();
        if ($absolute) {
            return abs($difference);
        }

        return $difference;
    }

    /**
     * Checks if the Change was a withdrawal of parts.
     *
     * @return bool true if the change was a withdrawal, false if not
     */
    public function isWithdrawal(): bool
    {
        return $this->getNewInstock() < $this->getOldInstock();
    }
}
