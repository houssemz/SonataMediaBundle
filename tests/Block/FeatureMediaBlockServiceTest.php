<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\MediaBundle\Tests\Block;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\BlockBundle\Test\BlockServiceTestCase;
use Sonata\MediaBundle\Block\FeatureMediaBlockService;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Sonata\MediaBundle\Provider\Pool;

class FeatureMediaBlockServiceTest extends BlockServiceTestCase
{
    private FeatureMediaBlockService $blockService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blockService = new FeatureMediaBlockService(
            $this->twig,
            new Pool('default'),
            $this->createStub(AdminInterface::class),
            $this->createStub(MediaManagerInterface::class)
        );
    }

    public function testDefaultSettings(): void
    {
        $blockContext = $this->getBlockContext($this->blockService);

        $this->assertSettings([
            'attr' => [],
            'content' => false,
            'context' => false,
            'extra_cache_keys' => [],
            'format' => false,
            'media' => false,
            'mediaId' => null,
            'orientation' => 'left',
            'template' => '@SonataMedia/Block/block_feature_media.html.twig',
            'title' => null,
            'translation_domain' => null,
            'icon' => null,
            'class' => null,
            'ttl' => 0,
            'use_cache' => true,
        ], $blockContext);
    }
}
