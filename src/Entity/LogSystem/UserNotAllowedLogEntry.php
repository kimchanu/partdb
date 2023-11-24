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
class UserNotAllowedLogEntry extends AbstractLogEntry
{
    protected string $typeString = 'user_not_allowed';

    public function __construct(string $path)
    {
        parent::__construct();
        $this->level = LogLevel::WARNING;

        $this->extra['a'] = $path;
    }

    /**
     * Returns the path the user tried to accessed and what was denied.
     */
    public function getPath(): string
    {
        return $this->extra['a'] ?? 'legacy';
    }

    public function getMessage(): string
    {
        return $this->extra['p'] ?? '';
    }
}
