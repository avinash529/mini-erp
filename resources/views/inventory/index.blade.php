<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Inventory - Mini ERP</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   @vite(['resources/css/app.css'])
</head>

<body class="mini-erp-shell">

   <nav class="navbar navbar-dark mini-erp-navbar">
      <div class="container">
         <a class="navbar-brand" href="{{ route('dashboard') }}">
            Mini ERP
         </a>

         <div class="d-flex align-items-center flex-wrap nav-actions">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light">
               Dashboard
            </a>

            <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
               New PO
            </a>
         </div>
      </div>
   </nav>

   <div class="container py-4 mini-erp-page">

      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
         <h2 class="mb-0">Inventory</h2>
         <span class="badge bg-dark rounded-pill px-3 py-2">
            {{ $products->count() }} Products
         </span>
      </div>

      <div class="card mini-erp-card">

         <div class="card-body p-0">

            <table class="table table-hover app-table mb-0">

               <thead>
                  <tr>
                     <th>SKU</th>
                     <th>Product</th>
                     <th>Current Stock</th>
                     <th>Low Stock Threshold</th>
                     <th>Unit Price</th>
                     <th>Status</th>
                  </tr>
               </thead>

               <tbody>

                  @forelse($products as $product)

                  @php
                  $isLowStock =
                  $product->stock_quantity <= $product->low_stock_threshold;
                     @endphp

                     <tr class="{{ $isLowStock ? 'table-danger' : '' }}">

                        <td>
                           <strong>{{ $product->sku }}</strong>
                        </td>

                        <td>
                           {{ $product->name }}
                        </td>

                        <td>
                           <strong>
                              {{ $product->stock_quantity }}
                           </strong>
                        </td>

                        <td>
                           {{ $product->low_stock_threshold }}
                        </td>

                        <td>
                           ₹{{ number_format($product->unit_price, 2) }}
                        </td>

                        <td>
                           @if($isLowStock)
                           <span class="badge bg-danger">
                              Low Stock
                           </span>
                           @else
                           <span class="badge bg-success">
                              In Stock
                           </span>
                           @endif
                        </td>

                     </tr>

                     @empty

                     <tr>
                        <td colspan="6" class="text-center py-4">
                           No active products found.
                        </td>
                     </tr>

                     @endforelse

               </tbody>

            </table>

         </div>

      </div>

   </div>

</body>

</html>