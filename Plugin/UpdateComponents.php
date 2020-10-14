<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\MultiFeesCheckout\Plugin;

/**
 * Class UpdateComponents
 *
 * Change components templates etc
 */
class UpdateComponents
{
    /**
     * @var \MageWorx\MultiFeesCheckout\Observer\CheckoutLayoutModifier
     */
    private $checkoutLayoutModifier;

    /**
     * UpdateComponents constructor.
     *
     * @param \MageWorx\MultiFeesCheckout\Observer\CheckoutLayoutModifier $checkoutLayoutModifier
     */
    public function __construct(
        \MageWorx\MultiFeesCheckout\Observer\CheckoutLayoutModifier $checkoutLayoutModifier
    ) {
        $this->checkoutLayoutModifier = $checkoutLayoutModifier;
    }

    /**
     * @param $subject
     * @param $result
     * @return array
     */
    public function afterProcess($subject, array $result): array
    {
        $components = [];

        // Shipping fee elements
        if (isset($result['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shippingAdditional']['children']['mageworx-shipping-fee-form-container']
            ['children']['mageworx-shipping-fee-form-fieldset'])) {
            $components[] = &$result['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shippingAdditional']['children']['mageworx-shipping-fee-form-container']
            ['children']['mageworx-shipping-fee-form-fieldset'];
        }

        // Payment fee elements
        if (isset($result['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['beforeMethods']['children']['mageworx-payment-fee-form-container'])) {
            $components[] = &$result['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['beforeMethods']['children']['mageworx-payment-fee-form-container']
            ['children']['mageworx-payment-fee-form-fieldset'];
        }

        // Cart fee elements
        if (isset($result['components']['checkout']['children']['sidebar']['children']['summary']['children']
            ['itemsBefore']['children']['mageworx-fee-form-container']['children']['mageworx-fee-form-fieldset'])) {
            $components[] = &$result['components']['checkout']['children']['sidebar']['children']['summary']['children']
            ['itemsBefore']['children']['mageworx-fee-form-container']['children']['mageworx-fee-form-fieldset'];
        }

        foreach ($components as &$component) {
            $this->checkoutLayoutModifier->updateFieldTemplates($component);
        }

        return $result;
    }
}
