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

namespace Integration\Storage;

use Sylius\Bundle\GridBundle\Storage\FilterStorageInterface;
use Sylius\Bundle\GridBundle\Storage\SessionFilterStorage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SessionFilterStorageTest extends KernelTestCase
{
    public function testTheContainerContainsTheServiceAndAliases(): void
    {
        $this->bootKernel();

        $container = $this->getContainer();

        $this->assertTrue($container->has('sylius_grid.symfony.session.filter_storage'));
        $this->assertInstanceOf(SessionFilterStorage::class, $container->get('sylius_grid.symfony.session.filter_storage'));

        $this->assertTrue($container->has('sylius_grid.filter_storage'));
        $this->assertInstanceOf(SessionFilterStorage::class, $container->get('sylius_grid.symfony.session.filter_storage'));

        $this->assertTrue($container->has(FilterStorageInterface::class));
        $this->assertInstanceOf(SessionFilterStorage::class, $container->get(FilterStorageInterface::class));
    }
}
