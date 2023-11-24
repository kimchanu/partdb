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

namespace App\Services;

use Closure;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;
use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;

final class CustomEnvVarProcessor implements EnvVarProcessorInterface
{
    public function getEnv($prefix, $name, Closure $getEnv): bool
    {
        if ('validMailDSN' === $prefix) {
            try {
                $env = $getEnv($name);

                return !empty($env) && 'null://null' !== $env;
            } catch (EnvNotFoundException) {
                return false;
            }
        }

        return false;
    }

    public static function getProvidedTypes(): array
    {
        return [
            'validMailDSN' => 'bool',
        ];
    }
}
