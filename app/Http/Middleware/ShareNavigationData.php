<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareNavigationData
{
    public function handle(Request $request, Closure $next): Response
    {
        $navigation = $this->getNavigationWithPermissions();
        View::share('sidebarNavigation', $navigation);
        
        return $next($request);
    }
    
    private function getNavigationWithPermissions(): array
    {
        $user = auth()->user();
        
        if (!$user) {
            return [];
        }
        
        $navigation = config('navigation.sidebar');
        $filteredNavigation = [];
        
        foreach ($navigation as $item) {
            // Skip items that require permissions the user doesn't have
            if (isset($item['permission']) && !$user->can($item['permission'])) {
                continue;
            }
            
            // Handle submenu items
            if (isset($item['submenu'])) {
                $filteredSubmenu = [];
                
                foreach ($item['submenu'] as $submenuItem) {
                    // Skip null items (conditional items that don't apply)
                    if ($submenuItem === null) {
                        continue;
                    }
                    
                    // Check permissions if defined
                    if (isset($submenuItem['permission']) && !$user->can($submenuItem['permission'])) {
                        continue;
                    }
                    
                    // Add submenu item if it passes all checks
                    $filteredSubmenu[] = $submenuItem;
                }
                
                // Only add menu with submenu if it has visible items
                if (!empty($filteredSubmenu)) {
                    $item['submenu'] = $filteredSubmenu;
                    $filteredNavigation[] = $item;
                }
            } else {
                // Regular menu item with no submenu
                $filteredNavigation[] = $item;
            }
        }
        
        return $filteredNavigation;
    }
}
