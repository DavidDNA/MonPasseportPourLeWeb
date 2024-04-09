<?php

namespace App\Core\Twig;

use App\Core\ViewMode;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CoreExtension extends AbstractExtension {

    private readonly RequestStack $requestStack;

    public function __construct(RequestStack $requestStack) {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array {
        return [
            new TwigFunction('viewMode', [$this, 'viewMode']),
        ];
    }

    public function viewMode(): string {
        return ViewMode::fromOrDefault($this->requestStack->getCurrentRequest()->query->get('viewMode'))->value;
    }
}
