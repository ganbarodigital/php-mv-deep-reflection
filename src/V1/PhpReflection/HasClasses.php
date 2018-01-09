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

use GanbaroDigital\DeepReflection\V1\PhpContexts\PhpClassContainer;
use GanbaroDigital\MissingBits\Checks\Check;

/**
 * does the context contain any classes at all?
 */
class HasClasses implements Check
{
    /**
     * does the context contain any classes at all?
     *
     * @param  PhpClassContainer $context
     *         the context to examine
     * @return boolean
     *         `true` if the context has at least one class defined
     *         `false` otherwise
     */
    public static function check(PhpClassContainer $context) : bool
    {
        $classes = GetAllClasses::from($context);
        return count($classes) > 0;
    }

    /**
     * does the context contain any classes at all?
     *
     * @param  PhpClassContainer $context
     *         the context to examine
     * @return boolean
     *         `true` if the context has at least one class defined
     *         `false` otherwise
     */
    public function __invoke(PhpClassContainer $context) : bool
    {
        return static::check($context);
    }

    /**
     * does the context contain any classes at all?
     *
     * @param  PhpClassContainer $context
     *         the context to examine
     * @return boolean
     *         `true` if the context has at least one class defined
     *         `false` otherwise
     */
    public function inspect($context) : bool
    {
        return static::check($context);
    }
}