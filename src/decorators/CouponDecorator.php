<?php
namespace cncoders\order\decorators;

use cncoders\helper\Helper;
use cncoders\order\DecoratorException;
use cncoders\order\interfaces\DecoratorInterface;

class CouponDecorator implements DecoratorInterface
{
    protected $cart = [];
    protected $coupon = [];
    protected $order = [];
    protected $hasShare = false;

    /**
     * CouponDecorator constructor.
     * @param $coupon_id
     * @param $coupon_price
     * @param $coupon_where
     * @param bool $hasShare 是否分摊
     */
    public function __construct($coupon_id, $coupon_price, $coupon_where, $hasShare = false)
    {
        $this->coupon = [
            'coupon_id'     => $coupon_id,
            'coupon_price'  => $coupon_price,
            'coupon_where'  => $coupon_where
        ];
        $this->hasShare = $hasShare;
    }

    /**
     * @param array $cart
     * @return mixed|void
     */
    public function addCartData(array $cart)
    {
        $this->cart = $cart;
        return $this;
    }

    public function addOrderData(array $order)
    {
        if ($order['total_price'] < $this->coupon['coupon_where']) {
            throw new DecoratorException('优惠券不满足使用条件');
        }
        $order['final_total_price'] = Helper::formatPrice(
            ($order['final_total_price'] - $this->coupon['coupon_price'])
        );
        $order['coupon_price'] = $this->coupon['coupon_price'];
         if (true === $this->hasShare) {
            //剩余优惠金额
            $suplurs_price          = $this->coupon['coupon_price'];
            $order['coupon_price']  = Helper::formatPrice($this->coupon['coupon_price']);
            $count                  = count($order['detail']);
            for($i=0; $i < $count; $i++) {
                if ($i+1 == $count) {
                    $order['detail'][$i]['coupon_price'] = $suplurs_price;
                    $order['detail'][$i]['final_total_price'] = Helper::formatPrice(
                        ($order['detail'][$i]['total_price'] - $order['detail'][$i]['coupon_price'])
                    );
                    $suplurs_price = 0;
                } else {
                    $order['detail'][$i]['coupon_price'] = Helper::formatPrice(
                        ( ($order['detail'][$i]['total_price'] / $order['total_price']) * $this->coupon['coupon_price'] )
                    );
                    $order['detail'][$i]['final_total_price'] = Helper::formatPrice(
                        ($order['detail'][$i]['total_price'] - $order['detail'][$i]['coupon_price'])
                    );
                    $suplurs_price = Helper::formatPrice( ($suplurs_price - $order['detail'][$i]['coupon_price']));
                }

            }
        }
        $order['coupon'] = $this->coupon;
        $this->order = $order;
    }

    /**
     * @return mixed|void
     */
    public function boot()
    {
        return $this->order;
    }
}