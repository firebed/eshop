<?php


namespace Ecommerce\Repository\Contracts;


use Ecommerce\Models\Cart\Cart;
use Ecommerce\Models\Cart\CartProduct;
use Ecommerce\Models\Cart\CartStatus;

interface CartContract
{
    /**
     * Updates the cart. This method will also handle possible post updates like
     * updating the cart's total.
     *
     * @param Cart $cart The cart model or id value.
     */
    public function updateCart(mixed $cart): void;

    /**
     * Deletes a cart. Product quantities will be released if necessary.
     *
     * @param mixed $cart
     */
    public function deleteCart(mixed $cart): void;

    /**
     * Deletes a set of cart items.
     *
     * @param array $ids The ids of the carts to delete.
     */
    public function deleteCarts(array $ids): void;

    /**
     * @param mixed       $cart
     * @param CartProduct $cart_product
     */
    public function attachCartProduct(mixed $cart, CartProduct $cart_product): void;

    /**
     * Adds a product to the cart.
     *
     * @param int|Cart $cart          The cart which product belongs to.
     * @param int      $product_id    The product's id value.
     * @param int      $quantity
     * @param float    $price         If null, the value will be the product's price.
     * @param float    $compare_price If null, the value price will the same as product's compare price.
     * @param float    $discount      The discount in percentage, valid range is [0-1]. If null, the value will be the same as the product's discount.
     * @param float    $vat           The vat in percentage, valid range is [0-1]. If null, the value will be the same as product's vat.
     */
    public function attachProduct(mixed $cart, int $product_id, int $quantity, float $price, float $compare_price, float $discount, float $vat): void;

    /**
     * Updates a cart item by it's primary key.
     *
     * @param CartProduct $cartProduct
     */
    public function updateCartItem(CartProduct $cartProduct): void;

    /**
     * Updates the cart total.
     *
     * @param Cart $cart The cart instance.
     * @return bool
     */
    public function updateTotal(Cart $cart): bool;

    /**
     * Resets the products prices and other values as well.
     * If the product price is 0 then the product will be removed
     * from the cart.
     *
     * @param mixed $cart
     * @return bool
     */
    public function resetProductPrices(mixed $cart): bool;

    /**
     * Calculates the cart's total including all fees.
     *
     * @param Cart $cart The cart instance.
     * @return float The cart's total.
     */
    public function calculateTotal(Cart $cart): float;

    /**
     * Returns only the products total. Any fees that are applied to cart (like shipping or payment) are excluded.
     *
     * @param Cart $cart The cart instance.
     * @return float The products total.
     */
    public function getProductsTotal(Cart $cart): float;

    /**
     * Decreases the stock amounts of all cart's products.
     *
     * @param int $cart_id
     */
    public function captureStocks(int $cart_id): void;

    /**
     * Increases the stock amounts of all cart's products.
     *
     * @param int $cart_id
     */
    public function releaseStocks(int $cart_id): void;

    /**
     * Applies a percentage discount to the selected products. If $cart_products_id is empty
     * the discount will be applied to all the products in cart.
     *
     * @param Cart   $cart
     * @param float  $discount
     * @param ?int[] $cart_item_id
     */
    public function setDiscount(Cart $cart, float $discount, ?array $cart_item_id = NULL): void;

    /**
     *
     * @param Cart  $cart        The cart instance.
     * @param int[] $product_ids The product primary keys.
     * @return void
     */
    public function deleteCartItems(Cart $cart, ...$product_ids): void;

    /**
     * Restores a cart product by it's primary key.
     * Only use this method if the products in cart are unique.
     *
     * @param Cart  $cart        The cart instance.
     * @param int[] $product_ids The cart product primary key.
     * @return void
     */
    public function restoreCartItems(Cart $cart, array $product_ids): void;

    /**
     * Permanently deletes a cart product by it's primary key.
     * Only use this method if the products in cart are unique.
     *
     * @param Cart  $cart       The cart instance.
     * @param int[] $productIds The cart product primary key.
     * @return void
     */
    public function deleteCartItemsPermanently(Cart $cart, ...$productIds): void;

    /**
     * Changes the status of all the given carts.
     *
     * @param CartStatus $status   The new status.
     * @param int[]      $cart_ids The carts' id values.
     */
    public function setBulkCartStatus(CartStatus $status, array $cart_ids): void;

    /**
     * Changes the status of the given cart.
     *
     * @param Cart       $cart            The cart instance.
     * @param CartStatus $currentStatus   The cart's new status.
     * @param bool       $notifyCustomer  Whether to notify the customer or not.
     * @param ?string    $notesToCustomer Special notification message to the customer.
     */
    public function updateCartStatus(Cart $cart, CartStatus $currentStatus, bool $notifyCustomer = false, ?string $notesToCustomer = null): void;

    /**
     * Determines if a cart is eligible to capture the associated products' quantity.
     *
     * @param CartStatus $previousStatus The cart's previous status.
     * @param CartStatus $currentStatus  The cart's current status.'
     * @return bool
     */
    public function shouldCaptureProductStocks(CartStatus $previousStatus, CartStatus $currentStatus): bool;

    /**
     * Determines if a cart is eligible to release the associated products' quantity.
     *
     * @param CartStatus $previousStatus The cart's previous status.
     * @param CartStatus $currentStatus  The cart's current status.'
     * @return bool
     */
    public function shouldReleaseProductStocks(CartStatus $previousStatus, CartStatus $currentStatus): bool;

    /**
     * Updates the voucher code of the given cart.
     */
    public function setVoucher(Cart|int $cart, ?string $voucher): bool;

    /**
     * Resets the cart's status as it was newly submitted.
     *
     * @param Cart|int $cart
     * @return CartStatus
     */
    public function resetStatus(Cart|int $cart): CartStatus;
}
