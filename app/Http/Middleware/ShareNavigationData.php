<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ShareNavigationData
{
    public function handle(Request $request, Closure $next)
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
                // Handle regular menu items
                // Check permissions if defined
                if (isset($item['permission']) && !$user->can($item['permission'])) {
                    continue;
                }
                
                $filteredNavigation[] = $item;
            }
        }
        
        return $filteredNavigation;
    }
}
