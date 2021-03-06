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

namespace GanbaroDigital\DeepReflection\V1\PhpReflectors;

use GanbaroDigital\DeepReflection\V1\Checks;
use GanbaroDigital\DeepReflection\V1\Helpers;
use GanbaroDigital\DeepReflection\V1\PhpContexts;
use GanbaroDigital\DeepReflection\V1\Scope;
use Microsoft\PhpParser\Node\Statement\ClassDefinition;
use Microsoft\PhpParser\Node\Statement as Statements;
use Microsoft\PhpParser\Node as Nodes;

/**
 * understand an interface declaration
 */
class ReflectInterfaceDeclaration
{
    /**
     * understand an interface declaration
     *
     * @param  Statements\InterfaceDefintion $node
     *         the AST that declares the interface
     * @param  Scope $activeScope
     *         keeping track of where we are as we inspect things
     * @return PhpContexts\PhpInterface
     *         our understanding about the interface
     */
    public static function from(Statements\InterfaceDeclaration $node, Scope $activeScope) : PhpContexts\PhpInterface
    {
        // what is our parent's namespace?
        $namespaceCtx = $activeScope->getNamespace();
        $namespacePrefix = $namespaceCtx->getNameAsPrefix();

        // what is this interface called?
        $classname = $node->name->getText($node->parent->fileContents);

        // put the two together
        $fqcn = new PhpContexts\PhpInterfaceName($namespacePrefix . $classname);

        // now we can create the class itself
        $retval = new PhpContexts\PhpInterface($activeScope, $fqcn);

        // does it have a docblock?
        Helpers\AttachLeadingComment::using($node, $retval, $activeScope);

        // now that we have a class, our active scope has changed!
        $activeScope = $activeScope->with($retval);

        foreach ($node->getChildNodes() as $childNode)
        {
            // echo get_class($childNode) . PHP_EOL;
            switch (true) {
                case $childNode instanceof Nodes\InterfaceMembers:
                    self::inspectMembersNode($childNode, $activeScope, $retval);
            }
        }

        // all done
        return $retval;
    }

    /**
     * find all the things that our interface contains
     *
     * @param  Nodes\InterfaceMembers $node
     *         the container to examine
     * @param  Scope $activeScope
     *         keeping track of where we are as we inspect things
     * @param  PhpContexts\PhpInterface $retval
     *         the interface we are learning about
     * @return void
     */
    protected static function inspectMembersNode(Nodes\InterfaceMembers $node, Scope $activeScope, PhpContexts\PhpInterface $retval)
    {
        foreach ($node->getChildNodes() as $childNode)
        {
            // echo "- " . get_class($childNode) . PHP_EOL;
            switch (true) {
                case $childNode instanceof Nodes\MethodDeclaration:
                    $methodCtx = ReflectMethodDeclaration::from($childNode, $activeScope);
                    Helpers\AttachToParents::using($methodCtx, $activeScope);
                    break;
            }
        }
    }
}