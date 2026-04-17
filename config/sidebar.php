<?php
return [
    [
        'title' => 'Dashboard',
        'route' => 'dashboard.index',
        'icon' => 'nav-icon fas fa-tachometer-alt',
        'active' => 'dashboard.index',
    ],
    [
        'title' => 'Categories',
        'route' => 'dashboard.index',
        'icon' => 'nav-icon fas fa-boxes',
        'badge' => [
            'type' => 'info',
            'text' => 'New'
        ],
        'active' => 'dashboard.categories.*',
    ],
    [
        'title' => 'Orders',
        'route' => 'dashboard.index',
        'icon' => 'nav-icon fas fa-clipboard-check',
        'active' => 'dashboard.orders.*',
    ],
    [
        'title' => 'Products',
        'route' => 'dashboard.index',
        'icon' => 'nav-icon fas fa-box-open',
        'active' => 'dashboard.products.*',
    ],
    [
        'title' => 'Users',
        'route' => 'dashboard.index',
        'icon' => 'nav-icon fas fa-users',
        'active' => 'dashboard.users.*',
    ],
];
