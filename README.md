# Combination Generate

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ws-packages/combination-generate.svg?style=flat-square)](https://packagist.org/packages/ws-packages/combination-generate)
[![Total Downloads](https://img.shields.io/packagist/dt/ws-packages/combination-generate.svg?style=flat-square)](https://packagist.org/packages/ws-packages/combination-generate)

A Laravel package to generate all possible combinations from multiple arrays. Perfect for creating product variants, configuration options, or any scenario where you need to combine multiple sets of values.

## Installation

You can install the package via composer:

```bash
composer require ws-packages/combination-generate
```

For Laravel 5.5+ the service provider will be automatically discovered. For older versions, add the service provider to your `config/app.php`:

```php
'providers' => [
    // ...
    WsPackages\CombinationGenerate\Providers\CombinationServiceProvider::class,
],
```

## Usage

This package is designed specifically for Laravel applications and integrates seamlessly with Laravel's service container.

### Method 1: Dependency Injection (Recommended)

The best way to use this package in Laravel is through dependency injection:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Controller;
use WsPackages\CombinationGenerate\Services\CombinationService;

class ProductController extends Controller
{
    public function generateVariants(CombinationService $combinationService)
    {
        $productOptions = [
            ['Small', 'Medium', 'Large'],      // Sizes
            ['Red', 'Blue', 'Green'],          // Colors
            ['Cotton', 'Polyester']            // Materials
        ];
        
        $variants = $combinationService->generateCombination($productOptions);
        
        return response()->json([
            'total_variants' => count($variants),
            'variants' => $variants
        ]);
    }
}
```

### Method 2: Using Laravel's Service Container

```php
<?php

namespace App\Services;

use WsPackages\CombinationGenerate\Services\CombinationService;

class ProductVariantService
{
    public function createAllVariants($attributes)
    {
        // Resolve from container
        $combinationService = app(CombinationService::class);
        
        return $combinationService->generateCombination($attributes);
    }
}
```

### Method 3: Using the Helper Binding

```php
<?php

namespace App\Http\Controllers;

class InventoryController extends Controller
{
    public function generateCombinations()
    {
        // Using the singleton binding
        $service = app('combination-service');
        
        $options = [
            ['XS', 'S', 'M', 'L', 'XL'],
            ['Black', 'White', 'Navy'],
        ];
        
        return $service->generateCombination($options);
    }
}
```

### Method 4: Using Facade (Simplest)

```php
<?php

namespace App\Http\Controllers;

use WsPackages\CombinationGenerate\Facades\Combination;

class QuickController extends Controller
{
    public function generate()
    {
        $variants = Combination::generateCombination([
            ['Red', 'Blue'],
            ['S', 'M', 'L']
        ]);
        
        return $variants;
    }
}
```

You can also add the facade alias to your `config/app.php` for even easier access:

```php
'aliases' => [
    // ... other aliases
    'Combination' => WsPackages\CombinationGenerate\Facades\Combination::class,
],
```

Then use it without the full namespace:

```php
$variants = Combination::generateCombination($arrays);
```

### Method 5: In Artisan Commands

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use WsPackages\CombinationGenerate\Services\CombinationService;

class GenerateProductVariants extends Command
{
    protected $signature = 'products:generate-variants {product}';
    protected $description = 'Generate all variants for a product';

    public function handle(CombinationService $combinationService)
    {
        $productId = $this->argument('product');
        
        // Get product attributes from database
        $attributes = [
            ['S', 'M', 'L'],
            ['Red', 'Blue'],
            ['Cotton', 'Silk']
        ];
        
        $variants = $combinationService->generateCombination($attributes);
        
        $this->info('Generated ' . count($variants) . ' variants');
        $this->table(['Size', 'Color', 'Material'], $variants);
    }
}
```

### Method 6: In Jobs and Queued Tasks

```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use WsPackages\CombinationGenerate\Services\CombinationService;

class ProcessProductVariants implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $productData;

    public function __construct($productData)
    {
        $this->productData = $productData;
    }

    public function handle(CombinationService $combinationService)
    {
        $variants = $combinationService->generateCombination($this->productData);
        
        // Process each variant...
        foreach ($variants as $variant) {
            // Save to database, update inventory, etc.
        }
    }
}
```

### Laravel-Specific Use Cases

#### E-commerce Product Variants
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use WsPackages\CombinationGenerate\Services\CombinationService;

class Product extends Model
{
    public function generateVariants()
    {
        $service = app(CombinationService::class);
        
        $attributes = [
            $this->sizes()->pluck('name')->toArray(),
            $this->colors()->pluck('name')->toArray(),
            $this->materials()->pluck('name')->toArray(),
        ];
        
        return $service->generateCombination($attributes);
    }
}
```

#### Form Option Generation
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use WsPackages\CombinationGenerate\Services\CombinationService;

class ConfigurationController extends Controller
{
    public function getConfigOptions(CombinationService $service)
    {
        $options = [
            ['Basic', 'Premium', 'Enterprise'],    // Plans
            ['Monthly', 'Yearly'],                 // Billing
            ['1 User', '5 Users', '10 Users'],     // User limits
        ];
        
        $combinations = $service->generateCombination($options);
        
        return view('configuration', compact('combinations'));
    }
}
```
#### Testing Data Generation
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use WsPackages\CombinationGenerate\Services\CombinationService;

class ProductTest extends TestCase
{
    public function test_all_product_configurations()
    {
        $service = app(CombinationService::class);
        
        $testCases = $service->generateCombination([
            ['enabled', 'disabled'],        // Feature flags
            ['guest', 'user', 'admin'],     // User roles
            ['mobile', 'desktop'],          // Platforms
        ]);
        
        foreach ($testCases as $case) {
            [$feature, $role, $platform] = $case;
            // Run test for each combination...
        }
    }
}
```

## Laravel Integration Features

### Service Provider Auto-Discovery
This package supports Laravel's package auto-discovery feature. For Laravel 5.5+, the service provider will be automatically registered.

### Available Bindings
The package registers the following bindings in Laravel's service container:

- `CombinationService::class` - Standard class binding
- `'combination-service'` - Singleton binding (recommended for performance)
- `'combination'` - Facade binding
- `Combination` facade - For static method calls

### Configuration
No configuration is required! The package works out of the box with Laravel.

### Performance Considerations
- The service is registered as a singleton, so it's instantiated only once per request
- For large datasets, consider using Laravel's job queue for processing
- Memory usage scales with the number of combinations generated

## API

### `generateCombination(array $arrays): array`

Generates all possible combinations from the provided arrays.

**Parameters:**
- `$arrays` (array): An array of arrays containing the values to combine

**Returns:**
- `array`: An array containing all possible combinations

**Laravel Example:**
```php
// In a controller
public function index(CombinationService $service)
{
    $result = $service->generateCombination([
        ['A', 'B'],
        [1, 2]
    ]);
    
    return response()->json($result);
    // Returns: [['A', 1], ['A', 2], ['B', 1], ['B', 2]]
}
```

## Requirements

- PHP ^7.3|^8.0
- Laravel 5.5+

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email admin@google.com instead of using the issue tracker.

## Credits

- [An Engineer](https://github.com/ws-packages)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
