<?php

namespace MageSuite\ExtendedException\Processor;

class MemoryPeakUsageProcessor extends \Monolog\Processor\MemoryPeakUsageProcessor
{
    use \MageSuite\ExtendedException\Service\ScopeConfigProvider;

    const XML_PATH_ADD_MEMORY_PEAK_PROCESSOR = 'extended_exception/processors/memory_peak_processor';

    public function __invoke(array $record)
    {
        if(!$this->getConfig(self::XML_PATH_ADD_MEMORY_PEAK_PROCESSOR)) {
            return $record;
        }

        return parent::__invoke($record);
    }
}
