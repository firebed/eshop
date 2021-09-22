<?php

namespace Eshop\Actions\Schema;

use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;

class Schema
{
    public function webSite($id): string
    {
        return (new WebSiteSchema())->handle($id);
    }

    public function webPage(string $name, string $description = null): string
    {
        return (new WebPageSchema())->handle($name, $description);
    }

    public function organization(): string
    {
        return (new OrganizationSchema())->handle();
    }

    public function product(Product $product): string
    {
        return (new ProductSchema())->handle($product);
    }

    public function breadcrumb(Category $category, Product $product = null, Product $variant = null): string
    {
        return (new BreadcrumbSchema())->handle($category, $product, $variant);
    }
}