<?php

/**
 * Copyright (c) 2017-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * If you wish to use this program in proprietary software, you can purchase
 * a closed-source license. Contact licensing@ganbarodigital.com for details.
 *
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   https://www.gnu.org/licenses/agpl.html  GNU Affero GPL v3
 * @link      http://ganbarodigital.github.io/php-mv-deep-reflection
 */

namespace GanbaroDigital\DeepReflection\V1\PhpReflection;

use GanbaroDigital\DeepReflection\V1\PhpContexts\PhpParameterContainer;
use GanbaroDigital\MissingBits\ClassesAndObjects\StatelessClass;

/**
 * does the context contain a named parameter?
 */
class HasParameterCalled
{
    // we don't want you making objects from this class, sorry!
    use StatelessClass;

    /**
     * does the context contain a named parameter?
     *
     * @param  string $name
     *         which parameter are you looking for?
     * @param  PhpParameterContainer $context
     *         the context to examine
     * @return boolean
     *         - `true` if the context contains the named parameter
     *         - `false` otherwise
     */
    public static function check(string $name, PhpParameterContainer $context) : bool
    {
        $classes = GetAllParameters::from($context);
        return isset($classes[$name]);
    }
}
