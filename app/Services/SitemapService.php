<?php

namespace App\Services;

use App\Models\Campaign;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SitemapService
{
    /**
     * @var string
     */
    protected $locale = '';
    protected $page = '';

    /**
     * @param string $locale
     * @return $this
     */
    public function locale(string $locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @param string $page
     * @return $this
     */
    public function page(string $page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return array
     */
    public function sitemaps(): array
    {
        if (empty($this->page)) {
            return $this->base();
        } elseif (!empty($this->page) && method_exists($this, $this->page)) {
            return [];
        }

        return [];
    }

    public function urls(): array
    {
        if (empty($this->locale)) {
            return [];
        }

        if (!empty($this->page) && method_exists($this, $this->page)) {
            return $this->{$this->page}();
        }

        return $this->language(true);
    }

    /**
     * @param bool $urls
     * @return array
     */
    protected function language(bool $urls = false): array
    {
        $links = [];
        return $links;
    }

    /**
     * @return array
     */
    protected function campaigns(): array
    {
        $links = [];

        $features = Campaign::public()->front()->featured()->get();
        $campaigns = Campaign::public()->front()->featured(false)->paginate();

        /** @var Campaign $campaign */
        foreach ($features as $campaign) {
            $links[] = LaravelLocalization::localizeURL(route('dashboard', $campaign->id), $this->locale);
        }
        foreach ($campaigns as $campaign) {
            $links[] = LaravelLocalization::localizeURL(route('dashboard', $campaign->id), $this->locale);
        }
        return $links;
    }

    /**
     * @return array
     */
    protected function index(): array
    {
        $links = [];
        $base = [
            '/',
            'about',
            'privacy-policy',
            'features',
            'pricing',
            'roadmap',
            'public-campaigns',
            'hall-of-fame',
            'boosters',
            'press-kit',
            'security',

        ];

        foreach ($base as $link) {
            $links[] = LaravelLocalization::localizeURL($link, $this->locale);
        }
        return $links;
    }

    protected function base(): array
    {
        $links = [];
        $links[] = route('front.sitemap', ['locale' => $this->locale, 'page' => 'index']);
        $links[] = route('front.sitemap', ['locale' => $this->locale, 'page' => 'campaigns']);
        return $links;
    }
}
