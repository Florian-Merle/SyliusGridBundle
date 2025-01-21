<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Parser;

final class OptionsParser implements OptionsParserInterface
{
    public function parseOptions(array $parameters): array
    {
        return array_map(
            /**
             * @param mixed $parameter
             *
             * @return mixed
             */
            function ($parameter) {
                if (is_array($parameter)) {
                    return $this->parseOptions($parameter);
                }

                return $this->parseOption($parameter);
            },
            $parameters,
        );
    }

    /**
     * @param mixed $parameter
     *
     * @return mixed
     */
    private function parseOption($parameter)
    {
        if (!is_string($parameter)) {
            return $parameter;
        }

        if (0 === strpos($parameter, 'callable:')) {
            return $this->parseOptionCallable(substr($parameter, 9));
        }

        return $parameter;
    }

    private function parseOptionCallable(string $callable): \Closure
    {
        if (!is_callable($callable)) {
            throw new \RuntimeException(\sprintf('%s is not a callable.', $callable));
        }

        return $callable(...);
    }
}
