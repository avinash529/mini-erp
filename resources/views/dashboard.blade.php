<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mini ERP Dashboard</title>

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
            <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
               New Purchase Order
            </a>

            <a href="{{ route('inventory.index') }}" class="btn btn-outline-light">
               Inventory
            </a>

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
               @csrf

               <button class="btn btn-outline-light">
                  Logout
               </button>
            </form>
         </div>
      </div>
   </nav>

   <div class="container py-4 mini-erp-page">

      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
         <h2 class="mb-0">Dashboard</h2>
      </div>

      <div class="row g-3 mb-4">

         <div class="col-md-4">
            <div class="card mini-erp-card stat-card h-100">
               <div class="card-body">
                  <h6 class="text-muted mb-0">Active Products</h6>
                  <h2>{{ $activeProducts }}</h2>
               </div>
            </div>
         </div>

         <div class="col-md-4">
            <div class="card mini-erp-card stat-card h-100">
               <div class="card-body">
                  <h6 class="text-muted mb-0">Low Stock Alerts</h6>
                  <h2 class="text-danger">{{ $lowStockCount }}</h2>
               </div>
            </div>
         </div>

         <div class="col-md-4">
            <div class="card mini-erp-card stat-card h-100">
               <div class="card-body">
                  <h6 class="text-muted mb-0">Total Expenditure</h6>
                  <h2>
                     ₹{{ number_format($totalExpenditure, 2) }}
                  </h2>
               </div>
            </div>
         </div>

      </div>

      <div class="card mini-erp-card">
         <div class="card-header">
            <strong>Latest Purchase Orders</strong>
         </div>

         <div class="card-body p-0">

            <table class="table table-hover app-table mb-0">

               <thead>
                  <tr>
                     <th>PO #</th>
                     <th>Supplier</th>
                     <th>Status</th>
                     <th>Total</th>
                  </tr>
               </thead>

               <tbody>

                  @forelse($latestPurchaseOrders as $po)

                  <tr>
                     <td>
                        <a href="{{ route('purchase-orders.show', $po) }}">
                           #{{ $po->id }}
                        </a>
                     </td>

                     <td>
                        {{ $po->supplier->name }}
                     </td>

                     <td>
                        <span class="badge bg-secondary">
                           {{ $po->status }}
                        </span>
                     </td>

                     <td>
                        ₹{{ number_format($po->total_amount, 2) }}
                     </td>
                  </tr>

                  @empty

                  <tr>
                     <td colspan="4" class="text-center py-4">
                        No purchase orders found.
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