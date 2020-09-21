<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\MultiFeesCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class CheckoutLayoutModifier
 *
 * Replace multifees component on the checkout page;
 * Change templates;
 */
class CheckoutLayoutModifier implements ObserverInterface
{
    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var \MageWorx\Checkout\Api\LayoutModifierAccess $subject */
        $subject = $observer->getSubject();
        /** @var array $jsLayout */
        $jsLayout = &$subject->getJsLayout();

        $this->updateTotals($jsLayout);
        $this->updateCartFees($jsLayout);
        $this->updateShippingFees($jsLayout);
        $this->updatePaymentFees($jsLayout);
    }

    /**
     * Update fee in totals:
     * - change template;
     *
     * @param array $jsLayout
     * @return array
     */
    private function updateTotals(array &$jsLayout): array
    {
        // Regular Fee without Tax
        $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']['totals']
        ['children']['mageworx_fee']['config']['template'] =
            'MageWorx_MultiFeesCheckout/summary/totals/mageworx-fee-expandable';

        // Regular Fee with Tax
        $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']['totals']
        ['children']['mageworx_fee_tax']['config']['template'] =
            'MageWorx_MultiFeesCheckout/summary/totals/mageworx-fee-expandable';

        // Product Fee without Tax
        $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']['totals']
        ['children']['mageworx_product_fee']['config']['template'] =
            'MageWorx_MultiFeesCheckout/summary/totals/mageworx-fee-expandable';

        // Product Fee with Tax
        $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']['totals']
        ['children']['mageworx_product_fee_tax']['config']['template'] =
            'MageWorx_MultiFeesCheckout/summary/totals/mageworx-fee-expandable';

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     * @return array
     */
    public function updateCartFees(array &$jsLayout): array
    {
        $nameInLayout = 'mageworx-fee-form-container';
        // Copy element
        $originalElement = $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']
        ['itemsBefore']['children'][$nameInLayout];

        // Remove original element from layout
        unset(
            $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']
            ['itemsBefore']['children'][$nameInLayout]
        );

        // MageWorx_MultiFees/mageworx-fee-form
        $originalElement['config']['template'] = 'MageWorx_MultiFeesCheckout/summary/additional-inputs/mageworx-fee-form';

        $jsLayout['components']['checkout']['children']['sidebar']['children']['additionalInputs']['children'][$nameInLayout] =
            $originalElement;

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     * @return array
     */
    public function updateShippingFees(array &$jsLayout): array
    {
        if (!isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shippingAdditional']['children']
            ['mageworx-shipping-fee-form-container'])) {
            return $jsLayout;
        }

        $container = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shippingAdditional']['children']
        ['mageworx-shipping-fee-form-container'];

        // Remove original element from layout
        unset(
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shippingAdditional']['children']
            ['mageworx-shipping-fee-form-container']
        );

        $container['displayArea'] = 'shipping_method_additional_data';
        $container['config']['template'] = 'MageWorx_MultiFeesCheckout/shipping-fee/form-container';

        $this->updateFieldTemplates($container);

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingMethods']['children']['mageworx-shipping-fee-form-container'] = $container;

        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     * @return array
     */
    public function updatePaymentFees(array &$jsLayout): array
    {
        if (!isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['beforeMethods']['children']['mageworx-payment-fee-form-container'])) {
            return $jsLayout;
        }

        $container = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
        ['children']['payment']['children']['beforeMethods']['children']['mageworx-payment-fee-form-container'];

        // Remove original element from layout
        unset(
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['beforeMethods']['children']['mageworx-payment-fee-form-container']
        );

        $container['displayArea'] = 'before-place-order';
        $container['config']['template'] = 'MageWorx_MultiFeesCheckout/payment-fee/form-container';

        $this->updateFieldTemplates($container);

        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
        ['children']['payment']['children']['payments-list']['children']['mageworx-payment-fee-form-container'] =
            $container;

        return $jsLayout;
    }

    /**
     * Update templates for inputs and fields
     *
     * @param array $container
     * @return array
     */
    public function updateFieldTemplates(array &$container): array
    {
        if (empty($container['children'])) {
            return $container;
        }

        foreach ($container['children'] as &$child) {
            $this->replaceInputTemplates($child);
            if (!empty($child['children'])) {
                $this->updateFieldTemplates($child);
            }
        }

        return $container;
    }

    /**
     * Replaces an original template by corresponding analog compatible with mageworx checkout design
     *
     * @param array $component
     * @return array
     */
    private function replaceInputTemplates(array &$component): array
    {
        if (empty($component['component'])) {
            return $component;
        }

        $type = $component['component'];
        switch ($type) {
            case 'MageWorx_MultiFees/js/form/element/checkbox-set':
                $component['config']['template'] = 'MageWorx_MultiFeesCheckout/form/field';
                $component['config']['elementTmpl'] = 'MageWorx_MultiFeesCheckout/form/element/checkbox-set';
                break;
            case 'uiComponent':
            default:
                break;
        }

        return $component;
    }
}
