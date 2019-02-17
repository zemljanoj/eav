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
    const OPTION_SORT = 'sort';

    /**
     * @var \Magento\Framework\App\State
     */
    protected $appState;

    /**
     * @var \Zemljanoj\Eav\Model\Service\Product\AttributeOptions\SortService
     */
    private $sortService;

    /**
     * AttributeOption constructor.
     * @param \Magento\Framework\App\State $appState
     * @param \Zemljanoj\Eav\Model\Service\Product\AttributeOptions\SortService $sortService
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        \Zemljanoj\Eav\Model\Service\Product\AttributeOptions\SortService $sortService
    ) {
        $this->appState = $appState;
        $this->sortService = $sortService;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('reut:product:attribute-options');
        $this->setDescription('Manage Product Attribute Options.');
        $this->setDefinition(
            [
                new \Symfony\Component\Console\Input\InputOption(
                    static::OPTION_SORT,
                    's',
                    \Symfony\Component\Console\Input\InputOption::VALUE_NONE,
                    'Sort attribute options.'
                ),
            ]
        );
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
            $this->sort($input);
            $output->writeln("");
            $output->writeln("<info>Product attribute optoins successfully processed.</info>");
            return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
        } catch (\Exception $exception) {
            $output->writeln("<error>{$exception->getMessage()}</error>");
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     */
    private function sort(
        \Symfony\Component\Console\Input\InputInterface $input
    ) {
        $sort = $input->getOption(static::OPTION_SORT);
        if (!$sort) {
            return;
        }

        $this->sortService->execute();
    }
}
