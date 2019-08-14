<?php

class ERPinCloud_Connector_Model_Catalog_Product_Attributeset extends Mage_Api_Model_Resource_Abstract {

    /**
     * Retrieve attribute set list
     *
     * @return array
     */
    public function items() {
        $entityType = Mage::getModel('catalog/product')->getResource()->getEntityType();
        $collection = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter($entityType->getId());

        $result = array();
        foreach ($collection as $attributeSet) {
            $result[] = array(
                'attribute_set_id' => $attributeSet->getId(),
                'attribute_set_name' => $attributeSet->getAttributeSetName()
            );
        }

        return $result;
    }

}

// Class Mage_Catalog_Model_Product_Attribute_Set_Api End
