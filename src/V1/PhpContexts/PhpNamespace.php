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

namespace GanbaroDigital\DeepReflection\V1\PhpContexts;

use GanbaroDigital\DeepReflection\V1\PhpScopes\PhpScope;

/**
 * container for everything in a given namespace
 */
class PhpNamespace extends PhpSourceCode
  implements PhpClassContainer, PhpFunctionContainer, PhpInterfaceContainer, PhpNamespaceContainer, PhpTraitContainer
{
    /**
     * what namespace do we represent?
     *
     * @var PhpNamespaceName
     */
    protected $namespace;

    /**
     * class constructor
     *
     * @param string $namespace
     *        what is our name?
     */
    public function __construct(PhpScope $scope, string $namespace)
    {
        parent::__construct($scope);
        $this->namespace = new PhpNamespaceName($namespace);
    }

    /**
     * what is the name of the context we represent?
     *
     * @return PhpNamespaceName
     */
    public function getName()
    {
        return $this->namespace;
    }

    /**
     * what kind of context are we?
     *
     * this should be human-readable, suitable for putting in error
     * messages as so on
     *
     * @return string
     */
    public function getContextType()
    {
        return PhpContextTypes::NAMESPACE_CONTEXT;
    }

    /**
     * use this to get this namespace with '\' on the end
     * suitable for using as a prefix in your code
     *
     * @return PhpNamespaceName
     */
    public function getNameAsPrefix()
    {
        return new PhpNamespaceName($this->namespace . '\\');
    }
}
