<?php

class ERPinCloud_Connector_Model_Catalog_Category_Api extends Mage_Catalog_Model_Category_Api {

    /**
     * Return the list of products category ids
     *
     * @param array $filters
     * @param string|int $store
     * @return array
     */
    public function search($filters = null, $store = null) {
        $collection = Mage::getModel('catalog/category')->getCollection()
                ->setStoreId($this->_getStoreId($store))
                ->addAttributeToSelect('name');

        if (is_array($filters)) {
            try {
                foreach ($filters as $field => $value) {
                    if (isset($this->_filtersMap[$field])) {
                        $field = $this->_filtersMap[$field];
                    }

                    $collection->addFieldToFilter($field, $value);
                }
            } catch (Mage_Core_Exception $e) {
                $this->_fault('filters_invalid', $e->getMessage());
            }
        }

        $result = array();

        foreach ($collection as $product) {
            $result[] = $product->getId();
        }

        return $result;
    }

    public function move($categoryId, $parentId, $afterId = null) {
        $category = $this->_initCategory($categoryId);
        $parent_category = $this->_initCategory($parentId);

        $parentChildren = $parent_category->getChildren();
        $child = explode(',', $parentChildren);
        // TODO Improve speed when using $afterId
        if (!in_array($categoryId, $child) || $afterId != null) {
            // if $afterId is null - move category to the down
            if ($afterId === null && $parent_category->hasChildren()) {

                $afterId = array_pop($child);
            }

            if (strpos($parent_category->getPath(), $category->getPath()) === 0) {
                $this->_fault('not_moved', "Operation do not allow to move a parent category to any of children category");
            }

            try {
                $category->move($parentId, $afterId);
            } catch (Mage_Core_Exception $e) {
                $this->_fault('not_moved', $e->getMessage());
            }
        }
        return true;
    }

}
