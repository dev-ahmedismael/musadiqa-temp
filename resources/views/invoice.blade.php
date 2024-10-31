<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice</title>
    <style>
        body {
            direction: rtl;
            text-align: right;
            font-size: small;
            color: rgb(13, 13, 13);
            line-height: 0.7;
        }

        .invoice_header {
            display: flex;
            justify-content: space-between;
            gap: 100px;
        }

        .text-xl {
            font-size: x-large;
        }

        .text-lg {
            font-size: larger;
        }

        .text-bold {
            font-weight: 700;
        }

        .text-slate {
            color: slategrey;
        }

        .bg-red {
            background-color: red;
        }

        .total-table td {
            padding-right: 20px;
            padding-left: 20px;
        }

        tr {
            border-bottom: 1px solid siver
        }
    </style>

</head>

<body dir="rtl">
    <div style="display: flex;
            justify-content: space-between;
            gap: 100px;">
        <div>
            @if ($data['customer_tax_number'])
                <p class="text-xl">فاتورة ضريبية</p>
                <p class="text-xl text-slate">Tax Invoice</p>
            @else
                <p class="text-xl">فاتورة ضريبية مبسطة</p>
                <p class="text-xl text-slate">Simplified Tax Invoice</p>
            @endif

            <div style="display: flex;
            justify-content: space-between;
            gap: 100px;">
                <div>
                    <p>العميل</p>
                    <p class="text-slate">Bill to</p>
                    <p>{{ $data['customer_name'] }}</p>
                    @if ($data['customer_tax_number'])
                        <p>المملكة العربية السعودية</p>
                        <p>رقم التسجيل الضريبي</p>
                        <p>VAT Number</p>
                        <p>{{ $data['customer_tax_number'] }}</p>
                    @endif

                    @if ($data['purchase_order'])
                        <p class="text-bold">رقم الأمر</p>
                        <p>{{ $data['purchase_order'] }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-bold">رقم الفاتورة</p>
                    <p class="text-slate">Invoice Number</p>
                    <p>{{ $data['invoice_number'] }}</p>
                    <br>
                    @if ($data['reference'])
                        <p class="text-bold">رقم المرجع</p>
                        <p class="text-slate">Reference Number</p>
                        <p>{{ $data['reference'] }}</p>
                        <br>
                    @endif

                    <p class="text-bold">التاريخ</p>
                    <p class="text-slate">Date</p>
                    <p>{{ $data['date'] }}</p>
                    <br>
                    <p class="text-bold">تاريخ الإصدار</p>
                    <p class="text-slate">Issue Date</p>
                    <p>{{ now()->format('d-m-Y') }}</p>
                    <br>
                    <p class="text-bold">تاريخ الإستحقاق</p>
                    <p class="text-slate">Due Date</p>
                    <p>{{ $data['due_date'] }}</p>
                    <br>

                </div>

            </div>
        </div>
        <div>
            {{-- @if ($logo)
                <img src="{{ $logo }}" alt="User Logo" width="150" height="150">
            @endif --}}
            <p>{{ auth()->user()->company_name }}</p>
            <p>{{ auth()->user()->building_number }} {{ auth()->user()->street }}</p>
            <p>{{ auth()->user()->zip_code }} - {{ auth()->user()->city }}</p>
            <p>المملكة العربية السعودية</p>
            <p>{{ auth()->user()->email }}</p>
            <p>رقم التسجيل الضريبي</p>
            <p>VAT Number</p>
            <p>{{ auth()->user()->tax_number }}</p>
            <p><span class="text-bold">CR</span> {{ auth()->user()->commercial_number }}</p>
        </div>
    </div>

    <div>
        <p class="text-xl">الرصيد المستحق</p>
        <p class="text-slate">Total Due</p>
        <p>{{ $data['total'] }}</p>
    </div>

    <table style="width: 100%">
        <thead>
            <tr>
                <td>
                    <div>
                        <p>المنتج / الوصف</p>
                        <p class="text-slate">Item / Description</p>
                    </div>
                </td>
                <td>
                    <div>
                        <p>الكمية</p>
                        <p class="text-slate">Quantity</p>
                    </div>
                </td>
                <td>
                    <div>
                        <p>السعر</p>
                        <p class="text-slate">Price</p>
                    </div>
                </td>
                <td>
                    <div>
                        <p>المبلغ الخاضع للضريبة</p>
                        <p class="text-slate">Taxable Amount</p>
                    </div>
                </td>
                <td>
                    <div>
                        <p>القيمة المضافة</p>
                        <p class="text-slate">VAT</p>
                    </div>
                </td>
                <td>
                    <div>
                        <p>المجموع</p>
                        <p class="text-slate">Amount</p>
                    </div>
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->products as $product)
                <tr>
                    <td>
                        {{ $product['product_name'] }}
                    </td>
                    <td>
                        {{ $product['quantity'] }}

                    </td>
                    <td>
                        {{ $product['price'] }}
                    </td>
                    <td>
                        {{ $product['sub_total'] }}
                    </td>
                    <td>
                        {{ $product['vat'] }}
                    </td>
                    <td>
                        {{ $product['total'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="total-table">
        <tbody>
            <tr>
                <td>
                    <div>
                        <p>المجموع الفرعي</p>
                        <p class="text-slate">Sub Total</p>
                    </div>
                </td>
                <td>
                    <p>SAR ر.س.</p>
                </td>
                <td>{{ $data->sub_total }}</td>
            </tr>
            <tr>
                <td>
                    <div>
                        <p>إجمالي ضريبة القيمة المضافة</p>
                        <p class="text-slate">Total VAT</p>
                    </div>
                </td>
                <td>
                    <p>SAR ر.س.</p>
                </td>
                <td>
                    {{ $data->vat }}
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <p>الإجمالي</p>
                        <p class="text-slate">Total</p>
                    </div>
                </td>
                <td>
                    <p>SAR ر.س.</p>
                </td>
                <td>
                    {{ $data->total }}
                </td>
            </tr>

        </tbody>

    </table>

    <div>
        <p class="text-lg">ملاحظات</p>
        <p class="text-slate">Notes</p>
        <p>{{ $data['notes'] }}</p>
    </div>
</body>

</html>
