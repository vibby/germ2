<?php

namespace App\Story;

use App\Factory\EventOutlineFactory;
use App\Factory\ChairFactory;
use Zenstruck\Foundry\Story;

final class DefaultChairsStory extends Story
{
    public function build(): void
    {
        ChairFactory::createMany(10);
        EventOutlineFactory::createMany(10);
    }
}
