<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AreaDocuments;
use App\Filament\Resources\AreaResource;
use App\Filament\Resources\DocumentResource;
use App\Filament\Resources\UserResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\ProfileSettings;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo('/images/accrehub1.png')
            ->brandLogoHeight('3rem')
            ->colors([
                'primary' => Color::Cyan,
                'secondary' => Color::Zinc,
                'success' => Color::Green,
                'danger' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                AreaDocuments::class,
                ProfileSettings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->navigation(function (NavigationBuilder $builder) {
                $user = auth()->user();
                $areaItems = collect();
            
                if ($user) {
                    $areas = $user->role === 'admin'
                    ? \App\Models\Area::whereNotNull('name')->orderBy('name')->get()
                    : $user->areas()->whereNotNull('areas.name')->orderBy('areas.name')->get();
            
                    \Log::info('Loaded areas for navigation', [
                        'user_id' => $user->id,
                        'role' => $user->role,
                        'area_ids' => $areas->pluck('id'),
                    ]);
            
                    foreach ($areas as $area) {
                        try {
                            $url = AreaDocuments::getUrl(['area' => $area->id]);
                    
                            $areaItems->push(
                                NavigationItem::make($area->name ?? 'Unnamed Area')
                                    ->url($url)
                                    ->icon('heroicon-o-folder')
                                    ->isActiveWhen(function () use ($area) {
                                        $route = request()->route();
                                        $parameter = $route?->parameter('area');
                    
                                        return $route?->getName() === 'filament.admin.pages.area-documents'
                                            && (string) ($parameter instanceof \App\Models\Area ? $parameter->getKey() : $parameter) === (string) $area->id;
                                    })
                            );
                        } catch (\Exception $e) {
                            \Log::error('Error generating area navigation item', [
                                'area_id' => $area->id,
                                'area_name' => $area->name,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                    
                }
            
                $navigationGroups = [
                    NavigationGroup::make('Accreditation Area')
                        ->items($areaItems->isNotEmpty() ? $areaItems->all() : [
                            NavigationItem::make('No Areas Available')
                                ->url('#')
                                ->icon('heroicon-o-exclamation-circle'),
                        ]),
            
                ];
            
                if ($user && $user->role === 'admin') {
                    $navigationGroups[] = NavigationGroup::make('Management')
                        ->items([
                            NavigationItem::make('Areas')
                                ->url(AreaResource::getUrl('index'))
                                ->icon('heroicon-o-folder')
                                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.areas.*')),
                            NavigationItem::make('Users')
                                ->url(UserResource::getUrl('index'))
                                ->icon('heroicon-o-users')
                                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.users.*')),
                        ]);
                }
                if ($user) {
                    $navigationGroups[] = NavigationGroup::make('Account')
                        ->items([
                            NavigationItem::make('Settings')
                                ->url(ProfileSettings::getUrl())
                                ->icon('heroicon-o-cog')
                                ->isActiveWhen(fn () => request()->routeIs('filament.admin.pages.profile-settings')),
                        ]);
                }
            
                return $builder
                    ->items([
                        NavigationItem::make('Dashboard')
                            ->icon('heroicon-o-home')
                            ->url('/admin')
                            ->isActiveWhen(fn () => request()->is('admin')),
                    ])
                    ->groups($navigationGroups);
            
            });
            
    }
}