<?php
/**
 * Copyright (c) 2019.
 * @author Andrey Inyagin <zemljanoj.i@gmail.com>
 */

namespace Zemljanoj\Eav\Console\Command\Product;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AttributeOptions extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Magento\Framework\App\State
     */
    protected $appState;

    /**
     * AttributeOption constructor.
     * @param \Magento\Framework\App\State $appState
     */
    public function __construct(
        \Magento\Framework\App\State $appState
    ) {
        $this->appState = $appState;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('reut:product:attributep-options');
        $this->setDescription('Manage Product Attribute Options.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        $this->appState->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);

        try {
            $output->writeln("");
            $output->writeln("<info>Product attribute optoins successfully processed.</info>");
            return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
        } catch (\Exception $exception) {
            $output->writeln("<error>{$exception->getMessage()}</error>");
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }
    }
}
