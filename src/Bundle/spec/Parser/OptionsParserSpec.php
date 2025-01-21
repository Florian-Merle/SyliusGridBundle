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

namespace spec\Sylius\Bundle\GridBundle\Parser;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\GridBundle\Parser\OptionsParserInterface;

final class OptionsParserSpec extends ObjectBehavior
{
    function it_is_an_options_parser(): void
    {
        $this->shouldImplement(OptionsParserInterface::class);
    }

    function it_parses_options_with_callback(): void
    {
        $this
            ->parseOptions([
                'type' => 'callback',
                'option' => [
                    'callback' => 'callback:App\\Helper\\GridHelper::addHashPrefix',
                ],
                'label' => 'app.ui.id',
            ])
            ->shouldBeAValidConfig([
                'type' => 'callback',
                'option' => [],
                'label' => 'app.ui.id',
            ])
        ;
    }

    public function getMatchers(): array
    {
        return [
            'beAValidConfig' => function ($subject, $subset) {
                if ([] !== array_diff($subject, $subset)) {
                    return false;
                }

                return is_callable($subject['option']['callback'] ?? null);
            },
        ];
    }

    function it_fails_while_parsing_options_with_invalid_callback(): void
    {
        $this
            ->shouldThrow(\RuntimeException::class)
            ->during('parseOptions', [[
                'type' => 'callback',
                'option' => [
                    'callback' => 'callback:foobar',
                ],
                'label' => 'app.ui.id',
            ]])
        ;
    }
}
