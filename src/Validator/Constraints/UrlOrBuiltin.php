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

namespace App\Validator\Constraints;

use App\Entity\Attachments\Attachment;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Constraints the field that way that the content is either an url or a path to a builtin ressource (like %FOOTPRINTS%).
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class UrlOrBuiltin extends Url
{
    /**
     * @var array A list of the placeholders that are treated as builtin
     */
    public array $allowed_placeholders = Attachment::BUILTIN_PLACEHOLDER;
}
