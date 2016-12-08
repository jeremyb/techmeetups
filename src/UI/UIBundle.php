<?php

namespace UI;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use UI\DependencyInjection\UIExtension;

final class UIBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new UIExtension();
    }
}
