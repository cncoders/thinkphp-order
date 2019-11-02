<?php
namespace cncoders\order\decorators;

use cncoders\helper\Helper;
use cncoders\order\interfaces\DecoratorInterface;

class DeliveryDecorator implements DecoratorInterface
{
    protected $order;
    protected $fixedDeliveryPrice; //固定运费金额
    protected $freeDeliveryPrice; //免运费金额 当达到这个金额就免除运费

    public function __construct($fixed_delivery_price, $free_delivery_price = 0)
    {
        $this->fixedDeliveryPrice = $fixed_delivery_price;
        $this->freeDeliveryPrice = $free_delivery_price;
    }

    /**
     * @param array $cart
     * @return mixed|void
     */
    public function addCartData(array $cart)
    {
        // TODO: Implement addCartData() method.
    }

    /**
     * @param array $order
     * @return mixed|void
     */
    public function addOrderData(array $order)
    {
        $this->order = $order;
    }

    public function boot()
    {
        $delivery_price = 0;
        if ($this->order['final_total_price'] < $this->freeDeliveryPrice) {
            $delivery_price = $this->fixedDeliveryPrice;
        }

        $this->order['delivery_price'] = Helper::formatPrice($delivery_price);
        $this->order['final_total_price'] = Helper::formatPrice(
            ($this->order['final_total_price'] + $delivery_price)
        );

        return $this->order;
    }
}