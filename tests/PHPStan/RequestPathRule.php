<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PrivatePackagist\ApiClient\Api\AbstractApi;

/**
 * A path argument that skips AbstractApi::buildPath() is not URL-encoded, and an unencoded "#" or
 * "?" in a name cuts the path short and moves the request to another endpoint. Only a literal or a
 * buildPath() call can be trusted, so this rejects sprintf(), concatenation and interpolation.
 *
 * @implements Rule<MethodCall>
 */
class RequestPathRule implements Rule
{
    /** @var string[] */
    private static $requestMethods = ['get', 'getcollection', 'post', 'postfile', 'put', 'delete'];

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Identifier || !in_array($node->name->toLowerString(), self::$requestMethods, true)) {
            return [];
        }

        if (!$this->isOnThis($node)) {
            return [];
        }

        $class = $scope->getClassReflection();
        if ($class === null || ($class->getName() !== AbstractApi::class && !$class->isSubclassOf(AbstractApi::class))) {
            return [];
        }

        if (!isset($node->args[0]) || !$node->args[0] instanceof Node\Arg) {
            return [];
        }

        $path = $node->args[0]->value;
        if ($path instanceof String_ || ($path instanceof MethodCall && $this->isOnThis($path) && $this->isNamed($path, 'buildpath'))) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Path passed to %s() must be a literal string or built with $this->buildPath(), so that its arguments are URL-encoded.',
                $node->name->toString()
            ))->build(),
        ];
    }

    private function isOnThis(MethodCall $call): bool
    {
        return $call->var instanceof Variable && $call->var->name === 'this';
    }

    private function isNamed(MethodCall $call, string $lowercaseName): bool
    {
        return $call->name instanceof Identifier && $call->name->toLowerString() === $lowercaseName;
    }
}
