<?php

namespace Objectivehtml\Events;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $fieldtypes = [
        // Fieldtypes\EventDate::class,
        Fieldtypes\RRule::class,
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
        'actions' => __DIR__.'/../routes/actions.php',
        // 'web' => __DIR__.'/../routes/web.php',
    ];
    
    protected $scripts = [
        __DIR__.'/../dist/js/cp.js',
        // __DIR__.'/../dist/forms.js',
        // __DIR__.'/../dist/events.js'
    ];
    
    protected $stylesheets = [
        __DIR__.'/../dist/js/rrule.css'
    ];
    
    protected $tags = [
        Tags\Events::class
    ];

    protected $widgets = [
        Widgets\ImportEvents::class
    ];
    
    public function boot()
    {
        parent::boot();
        
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'objectivehtml/statamic-events');
    }
}
