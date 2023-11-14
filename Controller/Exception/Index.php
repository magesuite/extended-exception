<?php

namespace MageSuite\ExtendedException\Controller\Exception;

class Index extends \Magento\Framework\App\Action\Action {

    /**
     * Throw exception to test exception handler additional processors
     *
     * @return \Magento\Framework\Controller\ResultInterface|\Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        throw new \Magento\Framework\Exception\LocalizedException(__('Exception message'));
    }
}
