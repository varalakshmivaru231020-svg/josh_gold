<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color:#000; margin:30px;}
        h2,h3,h4{ margin:0; padding:0; }
        .container{ width:100%; }

        /* Header */
        .header {
              display: flex;
              justify-content: space-between;
              align-items: center;
          }

          .company-logo img{
              width: 80px;       /* adjust as you like */
              object-fit: contain;
          }


        /* Contact */
        .contact{
            margin-top:10px;
        }
        .contact a{
            display:flex;
            align-items:center;
            gap:6px;
            margin-bottom:6px;
            text-decoration:none;
            color:#000;
        }

        /* Tables */
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }
        table th, table td{
            border:1px solid #000;
            padding:6px;
        }
        th{
            background:#f3f3f3;
        }
        .text-right{ text-align:right; }

        /* Totals */
        .totals-table{
            width:300px;
            float:right;
            margin-top:15px;
            clear:both;
        }

        hr{ border:1px solid #000; margin:15px 0; }

        .footer{
            text-align:center;
            margin-top:25px;
            font-size:11px;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Header -->
    <div class="header">
        <div>
            <h2><strong>INVOICE</strong></h2>
            <p>
                Invoice #: {{ $order->order_number }} <br>
                Date: {{ $order->created_at->format('d M Y') }}
            </p>
        </div>

        <div class="company-logo">
            <img src="{{ public_path('blr.jpg') }}" alt="Company Logo">
        </div>
    </div>

    <!-- Company -->
    <h3><strong>Bengaluru Law House Publications</strong></h3>
    <p style="margin:5px 0;">
        Sahakar Nagar, Bengaluru, Karnataka - 560092 <br><br>
    </p>

    <!-- Contacts -->
    <div class="contact">

        <!-- WhatsApp -->
        <a href="https://wa.me/918217215195" target="_blank">
            <img src="{{ public_path('whatsa.png') }}" height="17">
            <span>8217215195</span>
        </a>

        <!-- Website -->
        <a href="https://blrlawhouse.com" target="_blank">
            Web: <span>https://blrlawhouse.com</span>
        </a>

        <!-- Email -->
        <a href="mailto:bengalurulawhouse@gmail.com">
            Email: <span>bengalurulawhouse@gmail.com</span>
        </a>
    </div>

    <hr>

    <!-- Customer -->
    <h4><strong>Bill To:</strong></h4>
    <p>
        <strong>{{ $order->customer_name }}</strong><br>
        {{ $order->customer_mobile }}<br>
        @if($order->customer_email) {{ $order->customer_email }}<br> @endif
        {{ $order->shipping_address }}<br>
        {{ $order->city }}, {{ $order->state }}<br>
        {{ $order->country }} - {{ $order->pincode }}
    </p>
    <hr>

    <!-- Order items -->
    <table>
        <thead>
        <tr>
            <th>Product</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Unit Price</th>
            <th class="text-right">Subtotal</th>
            <th class="text-right">Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $order->product->name }}</td>
            <td>{{ $order->product->name ?? 'Product Removed' }}</td>
            <td class="text-right">₹{{ number_format($order->unit_price,2) }}</td>
            <td class="text-right">₹{{ number_format($order->subtotal,2) }}</td>
            <td class="text-right">₹{{ number_format($order->total,2) }}</td>
        </tr>
        </tbody>
    </table>

    <!-- Amounts -->
    <table class="totals-table">
        <tr>
            <td><strong>Subtotal</strong></td>
            <td class="text-right">₹{{ number_format($order->subtotal,2) }}</td>
        </tr>
        <tr>
            <td><strong>Discount</strong></td>
            <td class="text-right">- ₹{{ number_format($order->discount,2) }}</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td class="text-right"><strong>₹{{ number_format($order->total,2) }}</strong></td>
        </tr>
    </table>

    <br><br><br>

    <p><strong>Amount:</strong> {{ numberToWordsIndian($order->total, 2) }}</p><br>
    <p><strong>Payment Reference No:</strong> {{ $order->payment_id }}</p>
    <br>

    <hr>

    <small>Books once purchased are not eligible for return.</small>

    <p class="footer">
        This is a system-generated invoice. No signature required.
    </p>

</div>

</body>
</html>
