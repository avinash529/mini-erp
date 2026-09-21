<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>Purchase Order #{{ $purchaseOrder->id }} - Mini ERP</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   @vite(['resources/css/app.css'])
</head>

<body class="mini-erp-shell">

   <nav class="navbar navbar-dark mini-erp-navbar">
      <div class="container">

         <a href="{{ route('dashboard') }}" class="navbar-brand">
            Mini ERP
         </a>

         <div class="d-flex align-items-center flex-wrap nav-actions">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light">
               Dashboard
            </a>

            <a href="{{ route('inventory.index') }}" class="btn btn-outline-light">
               Inventory
            </a>
         </div>

      </div>
   </nav>

   <div class="container py-4 mini-erp-page">

      @if(session('success'))
      <div class="alert alert-success">
         {{ session('success') }}
      </div>
      @endif

      @if($errors->any())
      <div class="alert alert-danger">
         {{ $errors->first() }}
      </div>
      @endif

      <div class="d-flex justify-content-between align-items-center mb-4">

         <div>
            <h2>
               Purchase Order #{{ $purchaseOrder->id }}
            </h2>

            <p class="text-muted mb-0">
               Created:
               {{ $purchaseOrder->created_at->format('d M Y H:i') }}
            </p>
         </div>

         @php
         $badgeClass = match($purchaseOrder->status) {
         'DRAFT' => 'bg-secondary',
         'APPROVED' => 'bg-primary',
         'RECEIVED' => 'bg-success',
         'CANCELLED' => 'bg-danger',
         default => 'bg-secondary',
         };
         @endphp

         <span class="badge {{ $badgeClass }} fs-6">
            {{ $purchaseOrder->status }}
         </span>

      </div>

      <div class="card mini-erp-card mb-4">

         <div class="card-header">
            <strong>Purchase Order Information</strong>
         </div>

         <div class="card-body">

            <div class="row">

               <div class="col-md-6">
                  <strong>Supplier</strong>

                  <p class="mb-0">
                     {{ $purchaseOrder->supplier->name }}
                  </p>
               </div>

               <div class="col-md-6">
                  <strong>Supplier Email</strong>

                  <p class="mb-0">
                     {{ $purchaseOrder->supplier->email ?? '-' }}
                  </p>
               </div>

            </div>

         </div>

      </div>

      <div class="card mini-erp-card mb-4">

         <div class="card-header">
            <strong>Items</strong>
         </div>

         <div class="card-body p-0">

            <div class="table-responsive">

               <table class="table table-hover mb-0">

                  <thead>
                     <tr>
                        <th>SKU</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                     </tr>
                  </thead>

                  <tbody>

                     @foreach($purchaseOrder->items as $item)

                     <tr>

                        <td>
                           {{ $item->product->sku }}
                        </td>

                        <td>
                           {{ $item->product->name }}
                        </td>

                        <td>
                           {{ $item->quantity }}
                        </td>

                        <td>
                           ₹{{ number_format($item->unit_price, 2) }}
                        </td>

                        <td>
                           ₹{{ number_format($item->subtotal, 2) }}
                        </td>

                     </tr>

                     @endforeach

                  </tbody>

                  <tfoot>

                     <tr>
                        <th colspan="4" class="text-end">
                           Total
                        </th>

                        <th>
                           ₹{{ number_format($purchaseOrder->total_amount, 2) }}
                        </th>
                     </tr>

                  </tfoot>

               </table>

            </div>

         </div>

      </div>

      @if($purchaseOrder->status === 'DRAFT')

      <div class="card mini-erp-card">

         <div class="card-header">
            <strong>Actions</strong>
         </div>

         <div class="card-body d-flex gap-2">

            <form method="POST" action="{{ route('purchase-orders.update-status', $purchaseOrder) }}">
               @csrf
               @method('PATCH')

               <input type="hidden" name="status" value="APPROVED">

               <button class="btn btn-primary">
                  Approve PO
               </button>
            </form>

            <form method="POST" action="{{ route('purchase-orders.update-status', $purchaseOrder) }}">
               @csrf
               @method('PATCH')

               <input type="hidden" name="status" value="CANCELLED">

               <button class="btn btn-danger">
                  Cancel PO
               </button>
            </form>

         </div>

      </div>

      @elseif($purchaseOrder->status === 'APPROVED')

      <div class="card mini-erp-card">

         <div class="card-header">
            <strong>Actions</strong>
         </div>

         <div class="card-body">

            <form method="POST" action="{{ route('purchase-orders.update-status', $purchaseOrder) }}">
               @csrf
               @method('PATCH')

               <input type="hidden" name="status" value="RECEIVED">

               <button class="btn btn-success">
                  Mark as Received
               </button>

            </form>

         </div>

      </div>

      @elseif($purchaseOrder->status === 'RECEIVED')

      <div class="alert alert-success">
         This purchase order has been received.
         Its items are now immutable.
      </div>

      @elseif($purchaseOrder->status === 'CANCELLED')

      <div class="alert alert-danger">
         This purchase order has been cancelled.
         Its items are now immutable.
      </div>

      @endif

   </div>

</body>

</html>