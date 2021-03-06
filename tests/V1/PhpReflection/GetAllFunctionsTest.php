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

namespace GanbaroDigitalTest\DeepReflection\V1\PhpReflection;

use GanbaroDigital\DeepReflection\V1\PhpContexts;
use GanbaroDigital\DeepReflection\V1\PhpReflection;
use GanbaroDigital\DeepReflection\V1\PhpReflection\GetAllFunctions;
use GanbaroDigitalTest\DeepReflection\V1\PhpFixtures\AddFunctionsToContainer;
use PhpUnit\Framework\TestCase;

require_once(__DIR__ . '/../PhpFixtures/AddFunctionsToContainer.php');

/**
 * @coversDefaultClass GanbaroDigital\DeepReflection\V1\PhpReflection\GetAllFunctions
 */
class GetAllFunctionsTest extends TestCase
{
    use AddFunctionsToContainer;

    /**
     * @covers ::__construct
     * @expectedException Error
     */
    public function test_cannot_be_instantiated()
    {
        // ----------------------------------------------------------------
        // setup your test


        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetAllFunctions;

        // ----------------------------------------------------------------
        // test the results

    }

    /**
     * @covers ::from
     */
    public function test_returns_empty_array_when_no_functions_in_context()
    {
        // ----------------------------------------------------------------
        // setup your test

        $context = new PhpContexts\PhpGlobalContext;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = GetAllFunctions::from($context);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals([], $actualResult);
    }

    /**
     * @covers ::from
     */
    public function test_can_get_all_functions_from_context()
    {
        // ----------------------------------------------------------------
        // setup your test

        $context = new PhpContexts\PhpGlobalContext;
        $this->assertFalse(PhpReflection\HasFunctions::check($context));
        $this->addMinimalFunctions($context);
        $this->assertTrue(PhpReflection\HasFunctions::check($context));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = GetAllFunctions::from($context);

        // ----------------------------------------------------------------
        // test the results

        // make sure we got back an array of the right size
        $this->assertEquals('array', gettype($actualResult));
        $this->assertEquals(2, count($actualResult));

        // the function 'foo' should be in the array
        $this->assertInstanceOf(PhpContexts\PhpFunction::class, $actualResult['foo']);
        $this->assertEquals('foo', $actualResult['foo']->getName());

        // the function 'bar' should be in the array
        $this->assertInstanceOf(PhpContexts\PhpFunction::class, $actualResult['bar']);
        $this->assertEquals('bar', $actualResult['bar']->getName());
    }

}