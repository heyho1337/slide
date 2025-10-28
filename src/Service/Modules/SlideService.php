<?php

namespace App\Service\Modules;

use App\Entity\EvcSlide;
use Doctrine\ORM\EntityManagerInterface;

class SlideService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LangService $langService
    ){

    }

    public function getSliderImages(): ?array
    {
        $images = $this->entityManager
            ->getRepository(EvcSlide::class)
            ->findBy(
                ['slide_aktiv' => 1],
                ['sorrend' => 'ASC']
            );

        $slider = [];
        foreach($images as $image){
            $slide = new \stdClass();
            $slide->nev = $image->getSlideNev();
            $slide->caption = $image->getSlideCaption();
            $slide->link = $image->getSlideLink();
            $slide->target = $image->getSlideTarget();
            $slide->alt = $image->getSlideAlt();
            $slide->title = $image->getSlideTitle();
            $slide->sorrend = $image->getSorrend();
            $slide->kepnev = $image->getSlideKepnev();
            $slider[] = $slide;
        }
        return $slider;
    }
}