<?php declare(strict_types=1);

use Shopware\Components\Logger;
use Shopware\Bundle\StoreFrontBundle\Struct\Product;
use Doctrine\ORM\EntityManagerInterface;
use Shopware\Components\Model\QueryBuilder;
use Shopware\Models\Article\Detail;
use PluginName\Value\Email;
use PluginName\Services\NotifyEmail;

class Shopware_Controllers_Frontend_FrontController extends Enlight_Controller_Action
{
    public function indexAction(): void
    {

    }
}
