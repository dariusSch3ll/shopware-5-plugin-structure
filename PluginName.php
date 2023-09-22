<?php

declare(strict_types=1);

namespace PluginName;

use Doctrine\DBAL\Exception;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Models\Mail\Mail;

final class PluginName extends Plugin
{
    public const NAME = 'PluginName';

    public function install(InstallContext $context): void
    {
        parent::install($context);

        $this->installMailTemplate();
    }

    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        $this->uninstallMailTemplate();
    }

    private function installMailTemplate(): void
    {
        $entityManager = Shopware()->Models();
        $mailTemplate = $entityManager
            ->getRepository(Mail::class)
            ->findOneBy(['name' => self::NAME]);

        if (!$mailTemplate instanceof Mail) {
            $mailLocation = __DIR__.'/Resources/mails/HolabeJumpToPdp.html';
            if (!$content = file_get_contents($mailLocation)) {
                throw new \RuntimeException(sprintf('Could not load content from "%s"', $mailLocation));
            }

            $mail = new Mail();
            $mail->setIsHtml();
            $mail->setName(self::NAME);
            $mail->setContentHtml($content);
            $mail->setFromMail('{config name=mail}');
            $mail->setFromName('{config name=shopName}');
            $mail->setSubject('Invalid Products deactivated');
            $mail->setMailtype(Mail::MAILTYPE_USER);

            $entityManager->persist($mail);
            $entityManager->flush();
        }
    }

    /**
     * @throws Exception
     */
    private function uninstallMailTemplate(): void
    {
        $sql = 'DELETE FROM s_core_config_mails WHERE s_core_config_mails.name = :mailTemplate';

        Shopware()->Models()->getConnection()->executeQuery($sql, ['mailTemplate' => self::NAME]);
    }
}

