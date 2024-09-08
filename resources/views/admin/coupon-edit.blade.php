@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Coupon infomation</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route("home.index") }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route("admin.coupon") }}">
                            <div class="text-tiny">Coupons</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit Coupon</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form class="form-new-product form-style-1" method="POST" action="{{ route("admin.coupon.update") }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $coupon->id }}" />
                    <fieldset class="name">
                        <div class="body-title">Coupon Code <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Coupon Code" name="code"
                               tabindex="0" value="{{ $coupon->code }}" aria-required="true" required="">
                    </fieldset>
                    @error('code')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="category">
                        <div class="body-title">Coupon Type</div>
                        <div class="select flex-grow">
                            <select class="" name="type">
                                <option value="">Select</option>
                                <option value="fixed" {{ $coupon->type == "fixed" ? "selected" : "" }}>Fixed</option>
                                <option value="percent" {{ $coupon->type == "percent" ? "selected" : "" }}>Percent</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('type')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Value <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Coupon Value" name="value"
                               tabindex="0" value="{{ $coupon->value }}" aria-required="true" required="">
                    </fieldset>
                    @error('value')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Cart Value <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Cart Value"
                               name="cart_value" tabindex="0" value="{{ $coupon->cart_value }}" aria-required="true"
                               required="">
                    </fieldset>
                    @error('cart_value')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Expiry Date <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="date" placeholder="Expiry Date"
                               name="expiry_date" tabindex="0" value="{{ $coupon->expiry_date }}" aria-required="true"
                               required="">
                    </fieldset>
                    @error('expiry_date')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        $(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault()
                var form = $(this).closest('form');
                swal({
                    title: 'Are you sure?',
                    text: 'You want delete the record?',
                    type: 'Warning',
                    buttons: ["No", "Yes"],
                    confirmButtonColor: '#1affed',
                }).then(function(result) {
                    if(result) {
                        form.submit();
                    }
                })
            })
        })
    </script>
@endpush
