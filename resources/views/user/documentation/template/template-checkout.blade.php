@extends('user.layout.layout')

@section('title', 'Checkout')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Checkout</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.documentation.index') }}">Templates</a>
                            </li>

                            <li class="breadcrumb-item active">Checkout Template</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-md-{{ $items->isEmpty() ? '12' : '7' }}">
                        <div class="card">

                            <div class="card-body">
                                @if (session()->has('template_title'))
                                    <div class="alert alert-success" role="alert">
                                        "{{ session('template_title') ?? '' }}" has been added to your cart. <a
                                            href="javascript:void(0)">Continue
                                            shopping</a>
                                    </div>
                                @endif

                                @if (!$items->isEmpty())
                                    <table class="table table-borderless mb-0" cellspacing="0">
                                        <tbody>

                                            @foreach ($items as $item)
                                                <tr class="cart_item">
                                                    <td class="ps-0">
                                                        <div class="d-flex position-relative align-items-center">
                                                            <img src="{{ $item->template->preview_image_url }}"
                                                                width="100"
                                                                class="me-2 border rounded-3 lazy entered loaded"
                                                                alt="" data-ll-status="loaded">
                                                            <div class="flex-1">
                                                                <h5 class="fs-0 mb-0">
                                                                    <a href="{{ authRoute('user.template.show', ['template' => $item->template]) }}"
                                                                        class="stretched-link text-decoration-none text-900 fw-bold">
                                                                        {{ $item->template->title }}
                                                                        @if ($item->template->original_price > $item->template->price)
                                                                            <small class="text-success">
                                                                                ({{ round((($item->template->original_price - $item->template->price) / $item->template->original_price) * 100) }}%
                                                                                Off)
                                                                            </small>
                                                                        @endif
                                                                    </a>
                                                                </h5>
                                                                <p class="fs--1 mb-0">License Type: Unlimited Use License
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end align-middle pb-0 pe-0">
                                                        <span>
                                                            <span class="d-block fw-semi-bold">
                                                                <span class="woocommerce-Price-amount amount"><bdi><span
                                                                            class="woocommerce-Price-currencySymbol">$</span>{{ $item->template->price }}</bdi></span>
                                                            </span>

                                                            <form
                                                                action="{{ authRoute('user.template.cart.remove', ['cart' => $item]) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button
                                                                    class="btn-no-style text-muted fst-underline">Remove</button>

                                                            </form>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach


                                            {{-- <tr>
                                            <td colspan="6" class="actions px-0">

                                                <div class="coupon d-flex">
                                                    <label for="coupon_code" class="d-none">Coupon:</label>
                                                    <input type="text" required="" name="coupon_code"
                                                        class="input-text form-control me-2" id="coupon_code" value=""
                                                        placeholder="Coupon code">
                                                    <button type="submit" class="btn btn-outline-dark" name="apply_coupon"
                                                        value="Apply coupon">Apply</button>
                                                </div>

                                                <input type="hidden" id="woocommerce-cart-nonce"
                                                    name="woocommerce-cart-nonce" value="acdbc3793b"><input type="hidden"
                                                    name="_wp_http_referer" value="/cart/">
                                            </td>
                                        </tr> --}}

                                        </tbody>
                                    </table>
                                @else
                                    <x-no-data message="Your template cart is currently empty." />

                                @endif
                            </div>

                        </div>
                    </div>
                    @if (!$items->isEmpty())
                        <div class="col-md-5">
                            <div class="card">

                                <div class="card-body p-4">

                                    <div class="cart_totals ">

                                        <table cellspacing="0" class="table">

                                            <tbody>
                                                <tr class="cart-subtotal">
                                                    <th class="ps-0">Subtotal</th>
                                                    <td class="pe-0 text-end" data-title="Subtotal"><span
                                                            class="woocommerce-Price-amount amount"><bdi><span
                                                                    class="woocommerce-Price-currencySymbol">$</span>{{ number_format($subtotal, 2) }}</bdi></span>
                                                    </td>
                                                </tr>


                                                <tr class="order-total">
                                                    <th class="ps-0">Total</th>
                                                    <td class="pe-0 text-end" data-title="Total"><strong><span
                                                                class="woocommerce-Price-amount amount"><bdi><span
                                                                        class="woocommerce-Price-currencySymbol">$</span>{{ number_format($total, 2) }}</bdi></span></strong>
                                                    </td>
                                                </tr>


                                            </tbody>
                                        </table>

                                        <div class="wc-proceed-to-checkout">
                                            <form action="{{ authRoute('user.template.checkout') }}" method="post">
                                                @csrf
                                                <button class="btn btn-success d-block w-100">
                                                    Proceed to checkout</button>
                                            </form>
                                        </div>


                                    </div>
                                    <div class="text-center mt-3">
                                        <div class="d-flex justify-content-center w-100 align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em"
                                                viewBox="0 0 24 24">
                                                <path d="M0 0h24v24H0z" fill="none" />
                                                <path fill="currentColor" fill-rule="evenodd"
                                                    d="M20.29 8.567c.133.323.334.613.59.85v.002a3.536 3.536 0 0 1 0 5.166a2.44 2.44 0 0 0-.776 1.868a3.534 3.534 0 0 1-3.651 3.653a2.48 2.48 0 0 0-1.87.776a3.537 3.537 0 0 1-5.164 0a2.44 2.44 0 0 0-1.87-.776a3.533 3.533 0 0 1-3.653-3.654a2.44 2.44 0 0 0-.775-1.868a3.537 3.537 0 0 1 0-5.166a2.44 2.44 0 0 0 .775-1.87a3.55 3.55 0 0 1 1.033-2.62a3.6 3.6 0 0 1 2.62-1.032a2.4 2.4 0 0 0 1.87-.775a3.535 3.535 0 0 1 5.165 0a2.44 2.44 0 0 0 1.869.775a3.53 3.53 0 0 1 3.652 3.652c-.012.35.051.697.184 1.02ZM9.927 7.371a1 1 0 1 0 0 2h.01a1 1 0 0 0 0-2zm5.889 2.226a1 1 0 0 0-1.414-1.415L8.184 14.4a1 1 0 0 0 1.414 1.414zm-2.79 5.028a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2z"
                                                    clip-rule="evenodd" />
                                            </svg>

                                            <strong>100% Satisfaction Guarantee</strong>
                                        </div>
                                        <p class="fs--1 text-600 mb-0">Don't love your theme? We'll issue you a full refund.
                                        </p>
                                    </div>

                                </div>

                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @include('layout.components.copyright')
    </div>
@endsection

@section('js')

@endsection
