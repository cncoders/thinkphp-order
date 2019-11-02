# thinkphp-order
提供了一个观察者以及一个基础订单生成 基础的优惠券处理 以及运费处理的观察器，对于不同业务自己可以扩展不同的观察器，以下为示例

```

$cart = array(
	array('goods_id' => 1, 'goods_name' => '课程一', 'price' => 9.9, 'num' => 2),
	array('goods_id' => 2, 'goods_name' => '课程2', 'price' => 19.9, 'num' => 1),
	array('goods_id' => 3, 'goods_name' => '课程3', 'price' => 29.9, 'num' => 3),
);

$decorator = new OrderDecorator($cart);

//先处理成基础订单
$decorator->addDecorator(new BaseDecorator());

//对订单的优惠券处理
$coupon_id      = 1;
$coupon_price   = 20;
$coupon_where   = 100;
$decorator->addDecorator(new CouponDecorator($coupon_id, $coupon_price, $coupon_where, true));

$decorator->addDecorator(new DeliveryDecorator(8, 288));

$order = $decorator->boot();
		
```

如果需要扩展或者改变为自己的处理只需要在自己的应用目录建立Decorator文件继承DecoratorInterface实现其中的方法，DecoratorInterface 需要实现以下方法

```
<?php
namespace cncoders\order\interfaces;

interface DecoratorInterface
{
    /**
     * 添加购物车数据 购物车的原始数据
     * @param array $cart
     * @return mixed
     */
    public function addCartData(array $cart);

    /**
     * 添加订单数据 (上一个观察者处理的订单数据会通过这个方法传递进来
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
```
