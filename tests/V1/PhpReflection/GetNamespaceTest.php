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
use GanbaroDigital\DeepReflection\V1\PhpReflection\GetNamespace;
use GanbaroDigital\MissingBits\ErrorResponders\OnFatal;
use GanbaroDigitalTest\DeepReflection\V1\PhpFixtures\AddNamespacesToContainer;
use PhpUnit\Framework\TestCase;

require_once(__DIR__ . '/../PhpFixtures/AddNamespacesToContainer.php');

/**
 * @coversDefaultClass GanbaroDigital\DeepReflection\V1\PhpReflection\GetNamespace
 */
class GetNamespaceTest extends TestCase
{
    use AddNamespacesToContainer;

    /**
     * @covers ::__construct
     * @expectedException Error
     */
    public function test_cannot_be_instantiated()
    {
        // ----------------------------------------------------------------
        // setup your test

        $context = new PhpContexts\PhpGlobalContext;
        $this->assertFalse(PhpReflection\HasNamespaces::check($context));
        $this->addNamespaces($context, 'FooNamespace');
        $this->assertTrue(PhpReflection\HasNamespaces::check($context));

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetNamespace($context);

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @covers ::from
     */
    public function test_can_get_named_namespace_from_context()
    {
        // ----------------------------------------------------------------
        // setup your test

        $context = new PhpContexts\PhpGlobalContext;
        $this->assertFalse(PhpReflection\HasNamespaces::check($context));
        $this->addNamespaces($context, 'FooNamespace');
        $this->assertTrue(PhpReflection\HasNamespaces::check($context));

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = GetNamespace::from('FooNamespace', $context);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(PhpContexts\PhpNamespace::class, $actualResult);
        $this->assertEquals('FooNamespace', $actualResult->getName());
    }

    /**
     * @covers ::from
     * @expectedException GanbaroDigital\DeepReflection\V1\PhpExceptions\NoSuchNamespace
     */
    public function test_throws_NoSuchNamespace_when_named_namespace_not_in_context()
    {
        // ----------------------------------------------------------------
        // setup your test

        $context = new PhpContexts\PhpGlobalContext;
        $this->assertFalse(PhpReflection\HasNamespaces::check($context));
        $this->addNamespaces($context, 'FooNamespace');
        $this->assertTrue(PhpReflection\HasNamespaces::check($context));

        // ----------------------------------------------------------------
        // perform the change

        GetNamespace::from('not_a_namespace', $context);

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @covers ::from
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage VIVA LA REVOLUTION
     */
    public function test_can_override_failure_action_when_named_namespace_not_in_context()
    {
        // ----------------------------------------------------------------
        // setup your test

        $context = new PhpContexts\PhpGlobalContext;
        $this->assertFalse(PhpReflection\HasNamespaces::check($context));
        $this->addNamespaces($context, 'FooNamespace');
        $this->assertTrue(PhpReflection\HasNamespaces::check($context));

        $onFatal = new OnFatal(function($name) {
            throw new \InvalidArgumentException("VIVA LA REVOLUTION");
        });

        // ----------------------------------------------------------------
        // perform the change

        GetNamespace::from('not_a_namespace', $context, $onFatal);
    }

}