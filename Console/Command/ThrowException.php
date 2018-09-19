<?php

namespace MageSuite\ExtendedException\Console\Command;

class ThrowException extends \Symfony\Component\Console\Command\Command
{
    protected function configure()
    {
        $this->setName('exception:throw');
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    )
    {
        throw new \Magento\Framework\Exception\LocalizedException(__('Exception message'));
    }
}