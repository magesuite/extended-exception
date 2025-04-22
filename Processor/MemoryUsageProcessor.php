<?php

namespace MageSuite\ExtendedException\Processor;

class MemoryUsageProcessor extends \Monolog\Processor\MemoryUsageProcessor
{
    use \MageSuite\ExtendedException\Service\ScopeConfigProvider;

    const XML_PATH_ADD_MEMORY_USAGE_PROCESSOR = 'extended_exception/processors/memory_usage_processor';

    public function __invoke(\Monolog\LogRecord $record): \Monolog\LogRecord
    {
        try {
            if (!$this->getConfig(self::XML_PATH_ADD_MEMORY_USAGE_PROCESSOR)) {
                return $record;
            }
        } catch (\DomainException $e) {
            $this->getObjectManager()->get(\Magento\Framework\App\Config\ReinitableConfigInterface::class)->reinit();
            return $record;
        }

        return parent::__invoke($record);
    }
}
