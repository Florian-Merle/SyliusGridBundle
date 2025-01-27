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

namespace spec\Sylius\Component\Grid\FieldTypes;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Grid\DataExtractor\DataExtractorInterface;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;

final class CallableFieldTypeSpec extends ObjectBehavior
{
    function let(DataExtractorInterface $dataExtractor): void
    {
        $this->beConstructedWith($dataExtractor);
    }

    function it_is_a_grid_field_type(): void
    {
        $this->shouldImplement(FieldTypeInterface::class);
    }

    function it_uses_data_extractor_to_obtain_data_and_passes_it_to_a_callable_with_htmlspecialchars(
        DataExtractorInterface $dataExtractor,
        Field $field,
    ): void {
        $dataExtractor->get($field, ['foo' => 'bar'])->willReturn('bar');

        $this->render($field, ['foo' => 'bar'], [
            'callable' => fn (string $value): string => "<strong>$value</strong>",
            'htmlspecialchars' => true,
        ])->shouldReturn('&lt;strong&gt;bar&lt;/strong&gt;');
    }

    function it_uses_data_extractor_to_obtain_data_and_passes_it_to_a_callable_without_htmlspecialchars(
        DataExtractorInterface $dataExtractor,
        Field $field,
    ): void {
        $dataExtractor->get($field, ['foo' => 'bar'])->willReturn('bar');

        $this->render($field, ['foo' => 'bar'], [
            'callable' => fn (string $value): string => "<strong>$value</strong>",
            'htmlspecialchars' => false,
        ])->shouldReturn('<strong>bar</strong>');
    }

    function it_uses_data_extractor_to_obtain_data_and_passes_it_to_a_function_callable(
        DataExtractorInterface $dataExtractor,
        Field $field,
    ): void {
        $dataExtractor->get($field, ['foo' => 'bar'])->willReturn('bar');

        $this->render($field, ['foo' => 'bar'], [
            'callable' => 'strtoupper',
            'htmlspecialchars' => true,
        ])->shouldReturn('BAR');
    }

    function it_uses_data_extractor_to_obtain_data_and_passes_it_to_a_static_callable(
        DataExtractorInterface $dataExtractor,
        Field $field,
    ): void {
        $dataExtractor->get($field, ['foo' => 'bar'])->willReturn('BAR');

        $this->render($field, ['foo' => 'bar'], [
            'callable' => [self::class, 'callable'],
            'htmlspecialchars' => true,
        ])->shouldReturn('bar');
    }

    function it_throws_an_exception_when_a_callable_return_value_cannot_be_casted_to_string(
        DataExtractorInterface $dataExtractor,
        Field $field,
    ): void {
        $field->getName()->willReturn('id');
        $dataExtractor->get($field, ['foo' => 'bar'])->willReturn('BAR');

        $this
            ->shouldThrow(\RuntimeException::class)
            ->during('render', [
                $field,
                ['foo' => 'bar'],
                [
                    'callable' => fn () => new \stdclass(),
                    'htmlspecialchars' => true,
                ],
            ]);
    }

    static function callable(mixed $value): string
    {
        return strtolower($value);
    }
}