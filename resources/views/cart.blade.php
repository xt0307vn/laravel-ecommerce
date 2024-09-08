@extends('layouts.app')
@section('content')
    <style>
        .text-danger {
            color: #f00;
        }

        .text-success {
            color: #0f0;
        }

        .select {
            position: relative;
            min-width: 200px;
        }

        .flex-grow {
            flex-grow: 1;
        }

        select {
            border: none;
            outline: 0;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            width: 100%;
            padding: 14px 22px;
            font-family: "Inter", sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            background-color: transparent;
            border-radius: 12px;
            color: #111;
            margin-bottom: 0px;
            position: relative;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            -ms-appearance: none;
        }

        .select-coupon {
            border: 0.125rem solid #e4e4e4;
        }

        /*select {
            border: none;
            outline: 0;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            width: 100%;
            padding: 14px 22px;
            font-family: "Inter", sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            background-color: transparent;
            border: 1px solid #ECF0F4;
            border-radius: 12px;
            color: #111;
            margin-bottom: 0px;
            position: relative;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            -ms-appearance: none;
        }

        .select::after {
            position: absolute;
            content: "\e934";
            right: 22px;
            top: 50%;
            font-family: "icomoon";
            font-size: 18px;
            color: var(--Body-Text);
            pointer-events: none;
            -webkit-transition: 0.25s all ease;
            -o-transition: 0.25s all ease;
            transition: 0.25s all ease;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        select option {
            font-family: "Inter", sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            color: #111;
            background: #F2F7FB;
            text-transform: capitalize;
            cursor: pointer;
        }*/
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Cart</h2>
            <div class="checkout-steps">
                <a href="javascrip:void(0)" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Shopping Bag</span>
                        <em>Manage Your Items List</em>
                    </span>
                </a>
                <a href="javascrip:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Shipping and Checkout</span>
                        <em>Checkout Your Items List</em>
                    </span>
                </a>
                <a href="javascrip:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Confirmation</span>
                        <em>Review And Submit Your Order</em>
                    </span>
                </a>
            </div>
            <div class="shopping-cart">
                @if($items->count() > 0)
                    <div class="cart-table__wrapper">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th></th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>
                                            <div class="shopping-cart__product-item">
                                                <img loading="lazy" src="{{ asset("uploads/product") }}/{{ $item->model->image }}" width="120" height="120" alt="{{ $item->name }}"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shopping-cart__product-item__detail">
                                                <h4>{{ $item->name }}</h4>
                                                <ul class="shopping-cart__product-item__options">
                                                    <li>Color: Yellow</li>
                                                    <li>Size: L</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="shopping-cart__product-price">${{ $item->price }}</span>
                                        </td>
                                        <td>
                                            <div class="qty-control position-relative">
                                                <input type="number" name="quantity" value="{{ $item->qty }}" min="1" class="qty-control__number text-center">
                                                <form method="POST" action="{{ route("cart.qty.decrease", ["rowId"=>$item->rowId]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="qty-control__reduce">-</div>
                                                </form>

                                                <form method="POST" action="{{ route("cart.qty.increase", ["rowId"=>$item->rowId]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="qty-control__increase">+</div>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="shopping-cart__subtotal">{{ $item->subTotal() }}</span>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route("cart.remove", ["rowId"=>$item->rowId]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="javascript:void(0)" class="remove-cart">
                                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z"/>
                                                        <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z"/>
                                                    </svg>
                                                </a>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="cart-table-footer">
                            @if(Session::has("coupon"))
                                <form action="{{ route("cart.coupon.remove") }}" method="POST" class="position-relative bg-body">
                                    @csrf
                                    @method("DELETE")
                                    <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code" value="@if(Session::has("coupon")) {{ Session::get("coupon")["code"] }} applied @endif">
                                    <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit" value="REMOVE COUPON">
                                </form>
                            @else
                                <form action="{{ route("cart.coupon.apply") }}" method="POST" class="position-relative bg-body d-flex align-items-center flex-shrink-0 select-coupon">
                                    @csrf
                                    <div class="" style="width: 23.125rem; max-width: 100%;">
                                        <select class="" name="coupon_code" style="min-width: 100px">
                                            <option value="-1">Chose Coupon</option>
                                            @foreach($coupons as $coupon)
                                                <option value="{{ $coupon->code }}">{{ $coupon->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4 bg-success" type="submit" value="APPLY COUPON">
                                </form>
                            @endif
                            <form method="POST" action="{{ route("cart.empty") }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light">CLEAR CART</button>
                            </form>
                        </div>
                        <div>
                            @if(Session::has("success"))
                                <p class="text-success">{{ Session::get("success") }}</p>
                            @else
                                <p class="text-danger">{{ Session::get("error") }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="shopping-cart__totals-wrapper">
                        <div class="sticky-content">
                            <div class="shopping-cart__totals">
                                <h3>Cart Totals</h3>
                                @if(Session::has("discounts"))
                                    <table class="cart-totals">
                                        <tbody>
                                            <tr>
                                                <th>Subtotal</th>
                                                <td>${{ Cart::instance('cart')->subtotal() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Discount {{ Session::get("coupon")['code'] }}</th>
                                                <td>${{ Session::get("discounts")['discount'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Subtotal After Discount</th>
                                                <td>${{ Session::get("discounts")['subtotal'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping</th>
                                                <td>
                                                    Free
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>VAT</th>
                                                <td>${{ Session::get("discounts")['tax'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>${{ Session::get("discounts")['total'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="cart-totals">
                                        <tbody>
                                            <tr>
                                                <th>Subtotal</th>
                                                <td>${{ Cart::instance('cart')->subtotal() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping</th>
                                                <td>
                                                    Free
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>VAT</th>
                                                <td>${{ Cart::instance("cart")->tax() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>${{ Cart::instance("cart")->total() }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="mobile_fixed-btn_wrapper">
                                <div class="button-wrapper container">
                                    <a href="{{ route("cart.checkout") }}" class="btn btn-primary btn-checkout">PROCEED TO CHECKOUT</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center pt-5 bp-5">
                            <p>No item found in your cart</p>
                            <a href="{{ route("shop.index") }}" class="btn btn-info">Shop now</a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection

@push("scripts")
    <script>
        $(function () {
            $('.qty-control__increase').on("click", function () {
                $(this).closest("form").submit();
            })

            $('.qty-control__reduce').on("click", function () {
                $(this).closest("form").submit();
            })

            $('.remove-cart').on("click", function () {
                $(this).closest("form").submit();
            })
        })
    </script>
@endpush
