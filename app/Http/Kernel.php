<?php

namespace App\Http;

use App\Http\Middleware\FullSetup;
use App\Http\Middleware\PasswordConfirm;
use App\Http\Middleware\Tracking;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\HttpsProtocol::class, // Force https in prod
            \App\Http\Middleware\LocaleChange::class, // Save language changing
            Tracking::class,
            \App\Http\Middleware\CheckIfUserBanned::class,
            \App\Http\Middleware\OTPMiddleware::class,
        ],

        'api' => [
            //Do this in the routes 'throttle:rate_limit,1',
            'bindings',
            \App\Http\Middleware\ApiLogMiddleware::class,
        ],
        // Used for locale-less routes like our sitemaps, go/, auth/callbacks, webhooks
        'minimum' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\HttpsProtocol::class,
        ]
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     */
    protected $middlewareAliases = [
        'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
        'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        'localizeDatetime' => \App\Http\Middleware\LocalizeDatetime::class,
        'client' => CheckClientCredentials::class, // Laravel Passport

        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'campaign' => '\App\Http\Middleware\Campaign',
        'campaign.member' => \App\Http\Middleware\CampaignMember::class,
        'campaign.boosted' => \App\Http\Middleware\CampaignBoosted::class,
        'campaign.superboosted' => \App\Http\Middleware\CampaignSuperBoosted::class,

        'translator' => \App\Http\Middleware\Translator::class,
        'identity' => \App\Http\Middleware\Identity::class,
        'partner' => \App\Http\Middleware\Partner::class,
        'password.confirm' => PasswordConfirm::class,
        'subscriptions' => \App\Http\Middleware\Subscriptions::class,
        'fullsetup' => FullSetup::class,
        '2fa' => \App\Http\Middleware\OTPMiddleware::class,
        'not-api' => \App\Http\Middleware\NotApi::class,

    ];
}
