<?php

namespace PluginName\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Plugin\Configuration\ReaderInterface;

class TemplateRegistration implements SubscriberInterface
{
    private string $pluginDirectory;
    private \Enlight_Template_Manager $templateManager;
    private ReaderInterface $pluginConfigReader;
    private string $pluginName;

    public function __construct(
        string $pluginDirectory,
        \Enlight_Template_Manager $templateManager,
        ReaderInterface $pluginConfigReader,
        string $pluginName
    ) {
        $this->pluginDirectory = $pluginDirectory;
        $this->templateManager = $templateManager;
        $this->pluginConfigReader = $pluginConfigReader;
        $this->pluginName = $pluginName;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch',
        ];
    }

    public function onPreDispatch(): void
    {
        $this->templateManager->addTemplateDir($this->pluginDirectory.'/Resources/views');
    }
}