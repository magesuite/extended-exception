<?php

namespace MageSuite\ExtendedException\Console\Command;

class ThrowException extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct();

        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName('exception:throw');
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    )
    {
        $this->logger->error('ThrowException message');

        throw new \Magento\Framework\Exception\LocalizedException(__('ThrowException message'));
    }
}