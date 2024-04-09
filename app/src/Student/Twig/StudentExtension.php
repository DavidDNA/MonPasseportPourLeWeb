<?php

namespace App\Student\Twig;

use App\Entity\Avatar;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StudentExtension extends AbstractExtension {

    public function __construct(private readonly SerializerInterface $serializer) {
    }

    /**
     * Registers Twig functions.
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array {
        return [
            new TwigFunction("avatar", [$this, "renderAvatar"], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        ];
    }

    /**
     * Renders the student's avatar. The data is taken from the avatar's entity,
     * serialized and passed to the template as a json string so our Stimulus
     * controller can read it and convert it back to a JS object.
     *
     * @param Environment $environment
     * @param Avatar $avatar
     * @param string|null $id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderAvatar(Environment $environment, Avatar $avatar, string $id = null): string {
        return $environment->render("student/avatar.html.twig", [
            "id" => $id,
            "avatar" => $this->serializer->serialize($avatar, 'json', ['groups' => 'avatar']),
        ]);
    }
}
