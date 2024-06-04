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

namespace Sylius\Bundle\GridBundle\Tests\Functional;

use ApiTestCase\ApiTestCase;
use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use Symfony\Component\DomCrawler\Crawler;

final class PaginationTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixturesFromFile('fixtures.yml');
    }

    /** @test */
    public function it_returns_incorrect_amount_of_items_per_page_with_fetch_join_collection_disabled(): void
    {
        $this->client->request('GET', '/authors/with-books/with-fetch-join-collection-disabled');

        self::assertNotCount(10, $this->getAuthorNames());
    }

    /** @test */
    public function it_returns_correct_amount_of_items_per_page_with_fetch_join_collection_enabled_by_default(): void
    {
        $this->client->request('GET', '/authors/with-books/with-fetch-join-collection-enabled');

        self::assertCount(10, $this->getAuthorNames());
    }

    /** @return string[] */
    private function getAuthorNames(): array
    {
        return $this->getCrawler()
            ->filter('[data-test-name]')
            ->each(
                fn (Crawler $node): string => $node->text(),
            );
    }

    private function getCrawler(): Crawler
    {
        return $this->client->getCrawler();
    }

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
