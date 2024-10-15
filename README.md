To implement Latta into Symfony do:

1. Install Latta Recorder via Composer

```
composer require lattaai/latta-symfony-recorder
```

2. Insert API Key into ENV File

```
LATTA_API_KEY=xyz
```

3. Add lines to config/services.yaml into services block

```
services:
    LattaAi\Symfony\Recorder\LattaSymfonyEventHandler:
        tags: [kernel.event_listener]
```

4. Add lines to config/packages/monolog.yaml into when@prod: monolog: handlers block

```
when@prod:
    monolog:
        handlers:
            latta:
                type: service
                id: LattaAi\Recorder\Symfony\LattaSymfonyLogHandler
                level: debug
```

5. Add lines to public/index.php to return function

```
return function (array $context) {
    $lattaRecorder = new LattaRecorder($_ENV["LATTA_API_KEY"]);
    $lattaRecorder->startRecording("Symfony", \Symfony\Component\HttpKernel\Kernel::VERSION, PHP_OS, "PHP", "server");

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
```