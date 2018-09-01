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
use Microsoft\PhpParser\Node\MethodDeclaration;
use Microsoft\PhpParser\Node\Statement as Statements;
use Microsoft\PhpParser\Node as Nodes;

/**
 * understand a method declaration
 */
class ReflectParameterDeclarationList
{
    /**
     * understand a method declaration
     *
     * @param  Nodes\DelimitedList\ParameterDeclarationList $node
     *         the AST that declares the function param
     * @param  Scope $activeScope
     *         keeping track of where we are as we inspect things
     * @return PhpContexts\PhpFunctionLikeParameter
     *         our understanding about the parameter
     */
    public static function from(Nodes\DelimitedList\ParameterDeclarationList $node, Scope $activeScope) : PhpContexts\PhpFunctionLikeParameter
    {
        // what's hiding inside?
        foreach ($node->getChildNodes() as $childNode)
        {
            // echo '--- ' . get_class($childNode) . PHP_EOL;
            switch (true) {
                case $childNode instanceof Nodes\Parameter:
                    return self::inspectParameterNode($childNode, $activeScope);
            }
        }

        // all done
        // return $retval;
    }

    /**
     * find all the things that our class contains
     *
     * @param  Nodes\Parameter $node
     *         the parameter to examine
     * @param  Scope $activeScope
     *         keeping track of where we are as we inspect things
     * @return PhpContexts\PhpFunctionLikeParameter
     *         our understanding about the parameter
     */
    public static function inspectParameterNode(Nodes\Parameter $node, Scope $activeScope) : PhpContexts\PhpFunctionLikeParameter
    {
        // what is the parameter called?
        $name = Helpers\GetTokenText::from($node, $node->variableName);

        // does it have a type-hint at all?
        $typeHint = Helpers\GetTokenText::from($node, $node->typeDeclaration);

        // is it passed by reference?
        $passByReference = Helpers\GetTokenText::from($node, $node->byRefToken, false) ? true : false;

        // does it have a default value?
        $hasDefaultValue = false;
        $defaultValue = null;
        if ($node->equalsToken) {
            $defaultValue = Helpers\GetTokenText::from($node, $node->default);
            $hasDefaultValue = true;
        }

        // is the parameter variadic?
        $isVariadic = $node->dotDotDotToken ? true : false;

        // putting it all together
        $retval = new PhpContexts\PhpFunctionLikeParameter(
            $typeHint,
            $passByReference,
            $isVariadic,
            $name,
            $hasDefaultValue,
            $defaultValue
        );

        // all done
        return $retval;
    }
}