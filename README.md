<img src="https://cdn.edge.network/assets/img/edge-logo-green.svg" width="200">

# Power BI Embed

> Power BI Embed is a Statamic addon that allows you to seamlessly integrate Power BI reports and dashboards into your Statamic-powered website.

## Features

This addon provides:

- Easy embedding of Power BI reports and dashboards
- Secure authentication with Azure AD
- Customizable display options
- Test mode for development and debugging
- Simple tag-based implementation in Antlers templates

## How to Install

Download the addon to the directory `addons/edge/powerbi-embed` in your project.

Run the following command from your project root:

``` bash
composer require edge/powerbi-embed
```

## How to Use

1. After installation, publish the configuration file:

```bash
php artisan vendor:publish --tag=powerbi-embed-config
```

2. Set up your Power BI credentials in your `.env` file:

```
POWERBI_CLIENT_ID=your_client_id
POWERBI_CLIENT_SECRET=your_client_secret
POWERBI_TENANT_ID=your_tenant_id

POWERBI_TEST_MODE=false
POWERBI_PLACEHOLDER_IMAGE=https://example.com/path/to/placeholder-image.jpg
```

3. In your Antlers template, use the `powerbi_embed` tag to embed a Power BI report:

```antlers
{{ powerbi_embed workspace_id="your_workspace_id" report_id="your_report_id" }}
```

4. Optionally, you can enable test mode in your `.env` file to display a placeholder image instead of the actual report:

```
POWERBI_TEST_MODE=true
POWERBI_PLACEHOLDER_IMAGE=https://10play.com.au/ip/s3/2022/06/07/936e014eeb8c66d6b83f9334cef1116f-1154393.jpg
```

## Example Usage

1. Create a new Antlers view file, for example, `resources/views/powerbi_test.antlers.html`:

``` html
---
title: PowerBI Test
---
<h1>PowerBI Embed Test</h1>
{{ powerbi_embed workspace_id="your_workspace_id" report_id="your_report_id" }}
```

2. Add a route in your `routes/web.php` file:

```php
Route::get('/powerbi-test', function () {
    return view('powerbi_test');
});
```

3. Access the test page at `/powerbi-test` in your browser.

Make sure to replace `your_workspace_id` and `your_report_id` with your actual Power BI workspace and report IDs.

## Error Handling

If there's an error during the embedding process (e.g., authentication failure), the addon will display an error message instead of the report. You can customize the error view by publishing and modifying the `error.blade.php` view:

```bash
php artisan vendor:publish --tag=powerbi-embed-views
```

Then edit the `resources/views/edge/powerbi-embed/error.blade.php` file to customize the error display.

## Contributing

Contributions are welcome. Please open an issue or submit a pull request.

## License

Edge is the infrastructure of Web3. A peer-to-peer network and blockchain providing high performance decentralised web services, powered by the spare capacity all around us.

Copyright notice
(C) 2024 Edge Network Technologies Limited <support@edge.network><br />
All rights reserved

This product is part of Edge.
Edge is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version ("the GPL").

**If you wish to use Edge outside the scope of the GPL, please contact us at licensing@edge.network for details of alternative license arrangements.**

**This product may be distributed alongside other components available under different licenses (which may not be GPL). See those components themselves, or the documentation accompanying them, to determine what licenses are applicable.**

Edge is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

The GNU General Public License (GPL) is available at: https://www.gnu.org/licenses/gpl-3.0.en.html<br />
A copy can be found in the file GPL.md distributed with
these files.

This copyright notice MUST APPEAR in all copies of the product!
