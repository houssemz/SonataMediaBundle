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

namespace Sonata\MediaBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sonata\MediaBundle\Provider\MediaProviderInterface;

abstract class Gallery implements GalleryInterface
{
    protected ?string $context = null;

    protected ?string $name = null;

    protected bool $enabled = false;

    protected ?\DateTimeInterface $updatedAt = null;

    protected ?\DateTimeInterface $createdAt = null;

    protected string $defaultFormat = MediaProviderInterface::FORMAT_REFERENCE;

    /**
     * @var Collection<int, GalleryItemInterface>
     */
    protected Collection $galleryItems;

    public function __construct()
    {
        $this->galleryItems = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName() ?? '-';
    }

    final public function setName(?string $name): void
    {
        $this->name = $name;
    }

    final public function getName(): ?string
    {
        return $this->name;
    }

    final public function setContext(?string $context): void
    {
        $this->context = $context;
    }

    final public function getContext(): ?string
    {
        return $this->context;
    }

    final public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    final public function getEnabled(): bool
    {
        return $this->enabled;
    }

    final public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    final public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    final public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    final public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    final public function setDefaultFormat(string $defaultFormat): void
    {
        $this->defaultFormat = $defaultFormat;
    }

    final public function getDefaultFormat(): string
    {
        return $this->defaultFormat;
    }

    final public function setGalleryItems(Collection $galleryItems): void
    {
        $this->galleryItems = new ArrayCollection();

        foreach ($galleryItems as $galleryItem) {
            $this->addGalleryItem($galleryItem);
        }
    }

    final public function getGalleryItems(): Collection
    {
        return $this->galleryItems;
    }

    final public function addGalleryItem(GalleryItemInterface $galleryItem): void
    {
        $galleryItem->setGallery($this);

        $this->galleryItems[] = $galleryItem;
    }

    final public function removeGalleryItem(GalleryItemInterface $galleryItem): void
    {
        if ($this->galleryItems->contains($galleryItem)) {
            $this->galleryItems->removeElement($galleryItem);
        }
    }

    final public function reorderGalleryItems(): void
    {
        $iterator = $this->getGalleryItems()->getIterator();

        if (!$iterator instanceof \ArrayIterator) {
            throw new \RuntimeException(sprintf(
                'The gallery %s cannot be reordered, $galleryItems should implement %s',
                $this->getId() ?? '',
                \ArrayIterator::class
            ));
        }

        $iterator->uasort(static function (GalleryItemInterface $a, GalleryItemInterface $b): int {
            return $a->getPosition() <=> $b->getPosition();
        });

        $this->setGalleryItems(new ArrayCollection(iterator_to_array($iterator)));
    }
}
