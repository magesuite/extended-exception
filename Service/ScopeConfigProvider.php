<?php

namespace MageSuite\ExtendedException\Service;

trait ScopeConfigProvider
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    protected function getObjectManager() {
        return \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * ObjectManager has to be used directly because we cannot override constructor
     * @param $path
     * @return mixed
     */
    protected function getConfig($path) {
        $scopeConfig = $this->getObjectManager()->get(\Magento\Framework\App\Config\ScopeConfigInterface::class);

        return $scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * ObjectManager has to be used directly because we cannot override constructor
     * @param $path
     * @return mixed
     */
    protected function getAppMode() {
        $state = $this->getObjectManager()->get(\Magento\Framework\App\State::class);

        return $state->getMode();
    }
}