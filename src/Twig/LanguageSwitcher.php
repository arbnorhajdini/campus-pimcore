<?php

declare(strict_types=1);

namespace App\Twig;

use OpenDxp\Http\Request\Resolver\DocumentResolver;
use OpenDxp\Model\Document;
use OpenDxp\Tool;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Attribute\AsTwigFunction;

/**
 * Links the current page in every language, for the language switch and the hreflang tags.
 */
final readonly class LanguageSwitcher
{
    public function __construct(
        private RequestStack $requestStack,
        private DocumentResolver $documentResolver,
        #[Autowire(service: 'router.default')]
        private RouterInterface $router,
    ) {
    }

    /**
     * A localized Symfony route links to its other locale, a document to its translation.
     * Anything else falls back to the start page of the language.
     *
     * The route comes first: OpenDXP attaches the nearest document as a fallback to Symfony routes,
     * so /de/kurse/web-entwicklung also "has" the document /de/kurse.
     *
     * @return list<array{locale: string, url: string, current: bool}>
     */
    #[AsTwigFunction('language_links')]
    public function links(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return [];
        }

        $document = $this->documentResolver->getDocument($request);
        $translations = $document !== null ? (new Document\Service())->getTranslations($document) : [];

        $links = [];
        foreach (Tool::getValidLanguages() as $locale) {
            $url = $this->routeUrl($request, $locale);

            if ($url === null && isset($translations[$locale])) {
                $url = Document::getById($translations[$locale])?->getFullPath();
            }

            $links[] = ['locale' => $locale, 'url' => $url ?? "/{$locale}", 'current' => $locale === $request->getLocale()];
        }

        return $links;
    }

    /**
     * Symfony registers a localized route once per locale, e.g. course_detail.de and course_detail.fr.
     * OpenDXP stores the name without the suffix in _route.
     */
    private function routeUrl(Request $request, string $locale): ?string
    {
        $route = $request->attributes->get('_route');
        if (!is_string($route) || $this->router->getRouteCollection()->get("{$route}.{$locale}") === null) {
            return null;
        }

        $parameters = $request->attributes->all('_route_params');

        return $this->router->generate($route, ['_locale' => $locale] + $parameters);
    }
}
