<?php
namespace cncoders\order\interfaces;

interface DecoratorInterface
{
    /**
     * 添加购物车数据
     * @param array $cart
     * @return mixed
     */
    public function addCartData(array $cart);

    /**
     * 添加订单数据
     * @param array $order
     * @return mixed
     */
    public function addOrderData( array $order);

    /**
     * 返回处理结果
     * @return mixed
     */
    public function boot();
}