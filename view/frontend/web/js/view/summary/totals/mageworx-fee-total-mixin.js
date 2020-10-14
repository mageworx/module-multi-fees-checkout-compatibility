/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(
    [
        'ko'
    ],
    function (ko) {
        'use strict';

        return function (origComponent) {
            if (window.isMageWorxCheckout) {
                return origComponent.extend(
                    {
                        contentVisible: ko.observable(),
                        /**
                         * Toggle collapsible class state
                         */
                        toggleCollapsible: function () {
                            this.contentVisible(!this.contentVisible());
                        }
                    }
                );
            }

            return origComponent;
        };
    }
);
