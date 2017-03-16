<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContext;

/**
 * Class RouterListener
 * @package AppBundle\EventListener
 */
class RouterListener implements EventSubscriberInterface
{
    private $baseUrl;
    private $env;

    /**
     * RouterListener constructor.
     * @param $baseUrl
     * @param $env
     */
    public function __construct($baseUrl, $env) {
        $this->baseUrl = $baseUrl;
        $this->env = $env;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        // https://github.com/symfony/symfony/issues/21480
        if (preg_match('/_profiler|_wdt/i', $event->getRequest()->getRequestUri())) {
            return;
        }

        if ($this->env === "prod" && $event->isMasterRequest()) {
            $r = $event->getRequest();
            $uri = str_replace($this->baseUrl, "", $r->server->get("REQUEST_URI"));
            $r->server->set("REQUEST_URI", $uri);

            $r->initialize($r->query->all(), $r->request->all(), $r->attributes->all(), $r->cookies->all(), $r->files->all(), $r->server->all(), $r->getContent());
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 33)),
        );
    }
}