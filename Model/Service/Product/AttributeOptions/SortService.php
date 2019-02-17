<?php
/**
 * Copyright (c) 2019.
 * @author Andrey Inyagin <zemljanoj.i@gmail.com>
 */

namespace Zemljanoj\Eav\Model\Service\Product\AttributeOptions;

/**
 * Class \Zemljanoj\Eav\Model\Service\Product\AttributeOptions\SortService.
 */
class SortService
{
    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    private $productAttributeRepository;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory
     */
    private $optionCollectionFactory;

    /**
     * SortService constructor.
     *
     * @param \Magento\Eav\Api\AttributeRepositoryInterface $productAttributeRepository
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $optionCollectionFactory
     */
    public function __construct (
        \Magento\Eav\Api\AttributeRepositoryInterface $productAttributeRepository,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $optionCollectionFactory
    ) {
        $this->productAttributeRepository = $productAttributeRepository;
        $this->optionCollectionFactory = $optionCollectionFactory;
    }

    public function execute()
    {
        $storeId = 0;
        $power = $this->productAttributeRepository->get(
            \Magento\Catalog\Model\Product::ENTITY,
            'power'
        );
        /** @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection $collection */
        $collection = $this->optionCollectionFactory->create()->setPositionOrder(
            'asc'
        )->setAttributeFilter(
            $power->getAttributeId()
        )->setStoreFilter(
            $storeId
        )->load();
        /** @var \Magento\Eav\Model\Entity\Attribute\Option[] $options */
        $options = $collection->getItems();

        foreach ($options as $option) {
            $value = $option->getValue();
            $r = preg_match_all('/([0-9,]{0,})(.*)/', $value, $match);
            if (!$r) {
                continue;
            }
            $dimension = $match[1][0];
            $r = preg_match('/(,)/', $dimension);
            if (!$r) {
                $dimension .= ',0';
            }
            $length = strlen($dimension);
            $target = str_repeat('0', 7 - $length) . $dimension;
            $comparingValue = trim($match[2][0]) . $target;
            $option->setDataUsingMethod('comparing_value', $comparingValue);
        }

        usort(
            $options,
            function($a, $b) {
                return $a->getDataUsingMethod('comparing_value') > $b->getDataUsingMethod('comparing_value');
            }
        );

        $index = 0;
        foreach ($options as $option) {
            $index += 100;
            $option->setSortOrder($index);
            $option->save();
        }
    }
}
