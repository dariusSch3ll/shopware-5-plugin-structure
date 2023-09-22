<?php declare(strict_types=1);

namespace PluginName\Services;

use PluginName\Value\Email;
use PluginName\PluginName;
use Doctrine\DBAL\Exception;
use Shopware\Components\Logger;
use Shopware\Components\Plugin\Configuration\ReaderInterface;

class NotifyEmail
{
    private Logger                            $logger;
    private ReaderInterface                   $configReader;
    private \Shopware_Components_TemplateMail $templateMail;
    public function __construct(
        Logger                            $logger,
        ReaderInterface                   $configReader,
        \Shopware_Components_TemplateMail $templateMail
    ) {
        $this->logger = $logger;
        $this->configReader = $configReader;
        $this->templateMail = $templateMail;
    }
    /** @throws \RuntimeException */
    public function notifyUsers($vendor, $productID): void
    {
        try {
            $mail = $this->templateMail->createMail(PluginName::NAME, [
                'vendor' => $vendor, 'productID' => $productID
            ]);
        } catch (\Enlight_Exception $e) {
            $this->logger->addRecord(
                \Monolog\Logger::ERROR,
                'Could not sent email.',
                [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ]
            );

            throw new \RuntimeException('Failed PluginName. See logs for more information');
        }

        $config = $this->configReader->getByPluginName(PluginName::NAME);

        try {
            $recipients = array_map(static function (string $value) {
                return Email::fromAny(trim($value));
            }, explode(',', $config['adminEmails'] ?? ''));
        } catch (\TypeError $e) {
            $this->logger->addRecord(
                \Monolog\Logger::ERROR,
                'Could not sent email.',
                [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ]
            );

            throw new \RuntimeException('Failed PluginName See logs for more information');
        }

        $mail->addTo($recipients);

        try {
            $mail->send();
        } catch (\Exception $e) {
            $this->logger->addRecord(
                \Monolog\Logger::ERROR,
                'Could not sent email.',
                [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ]
            );
        }
    }
}