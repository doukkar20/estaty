@php
    $workspaceRoute = null;
    foreach (['admin' => 'admin.dashboard', 'vendor' => 'vendor.dashboard', 'agent' => 'agent.dashboard', 'web' => 'user.dashboard'] as $workspaceGuard => $dashboardRoute) {
        if (Auth::guard($workspaceGuard)->check()) {
            $workspaceRoute = $dashboardRoute;
            break;
        }
    }
@endphp
@if ($workspaceRoute)
    <div class="item">
        <a href="{{ route($workspaceRoute) }}" class="btn btn-outline btn-sm rafa-workspace-link">
            <i class="fas fa-th-large" aria-hidden="true"></i> {{ __('Dashboard') }}
        </a>
    </div>
@endif
