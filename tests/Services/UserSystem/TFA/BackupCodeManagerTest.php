<?php
/**
 * This file is part of Part-DB (https://github.com/Part-DB/Part-DB-symfony).
 *
 * Copyright (C) 2019 - 2020 Jan Böhmer (https://github.com/jbtronics)
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

namespace App\Tests\Services\UserSystem\TFA;

use App\Entity\UserSystem\User;
use App\Services\UserSystem\TFA\BackupCodeManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackupCodeManagerTest extends WebTestCase
{
    /**
     * @var BackupCodeManager
     */
    protected $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->service = self::getContainer()->get(BackupCodeManager::class);
    }

    public function testRegenerateBackupCodes(): void
    {
        $user = new User();
        $old_codes = ['aaaa', 'bbbb'];
        $user->setBackupCodes($old_codes);
        $this->service->regenerateBackupCodes($user);
        $this->assertNotSame($old_codes, $user->getBackupCodes());
    }

    public function testEnableBackupCodes(): void
    {
        $user = new User();
        //Check that nothing is changed, if there are already backup codes

        $old_codes = ['aaaa', 'bbbb'];
        $user->setBackupCodes($old_codes);
        $this->service->enableBackupCodes($user);
        $this->assertSame($old_codes, $user->getBackupCodes());

        //When no old codes are existing, it should generate a set
        $user->setBackupCodes([]);
        $this->service->enableBackupCodes($user);
        $this->assertNotEmpty($user->getBackupCodes());
    }

    public function testDisableBackupCodesIfUnused(): void
    {
        $user = new User();

        //By default, nothing other 2FA is activated, so the backup codes should be disabled
        $codes = ['aaaa', 'bbbb'];
        $user->setBackupCodes($codes);
        $this->service->disableBackupCodesIfUnused($user);
        $this->assertEmpty($user->getBackupCodes());

        $user->setBackupCodes($codes);

        $user->setGoogleAuthenticatorSecret('jskf');
        $this->service->disableBackupCodesIfUnused($user);
        $this->assertSame($codes, $user->getBackupCodes());
    }
}
