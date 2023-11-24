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
namespace App\Tests\Services\LogSystem;

use App\Services\LogSystem\EventCommentNeededHelper;
use PHPUnit\Framework\TestCase;

class EventCommentNeededHelperTest extends TestCase
{
    public function testIsCommentNeeded(): void
    {
        $service = new EventCommentNeededHelper(['part_edit', 'part_create']);
        $this->assertTrue($service->isCommentNeeded('part_edit'));
        $this->assertTrue($service->isCommentNeeded('part_create'));
        $this->assertFalse($service->isCommentNeeded('part_delete'));
        $this->assertFalse($service->isCommentNeeded('part_stock_operation'));
    }

    public function testIsCommentNeededInvalidTypeException(): void
    {
        $service = new EventCommentNeededHelper(['part_edit', 'part_create']);
        $this->expectException(\InvalidArgumentException::class);
        $service->isCommentNeeded('this_is_not_valid');
    }
}
