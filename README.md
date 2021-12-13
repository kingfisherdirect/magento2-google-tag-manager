# Magento 2 Module: Google Tag Manager

> This module adds Google Tag Manager once it's configured and sends basic data layer information

## Features

- Adds script and iframe for Google Tag Manager
- Adds dataLayer for following pages/events:
    - Every Page - page type + currencyCode
    - Product Page - ecommerce.detail
    - Search Page - search query and result count
    - Checkout Success - ecommerce.purchase

It should be easy to add more data. See the code for this.
