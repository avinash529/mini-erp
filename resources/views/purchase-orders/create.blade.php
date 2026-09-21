<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>Create Purchase Order - Mini ERP</title>

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

      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
         <h2 class="mb-0">Create Purchase Order</h2>

         <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            Back
         </a>
      </div>

      @if($errors->any())
      <div class="alert alert-danger">
         <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
         </ul>
      </div>
      @endif

      <form method="POST" action="{{ route('purchase-orders.store') }}">

         @csrf

         <div class="card mini-erp-card mb-4">

            <div class="card-header">
               <strong>Purchase Order Details</strong>
            </div>

            <div class="card-body">

               <div class="mb-4">

                  <label class="form-label">
                     Supplier
                  </label>

                  <select name="supplier_id" class="form-select" required>
                     <option value="">
                        Select Supplier
                     </option>

                     @foreach($suppliers as $supplier)
                     <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                     </option>
                     @endforeach

                  </select>

               </div>

               <div class="table-responsive">

                  <table class="table align-middle">

                     <thead>
                        <tr>
                           <th width="35%">Product</th>
                           <th width="15%">Quantity</th>
                           <th width="20%">Unit Price</th>
                           <th width="20%">Subtotal</th>
                           <th width="10%"></th>
                        </tr>
                     </thead>

                     <tbody id="items-container">

                        <tr class="item-row">

                           <td>
                              <select name="items[0][product_id]" class="form-select product-select" required>
                                 <option value="">
                                    Select Product
                                 </option>

                                 @foreach($products as $product)

                                 <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}">
                                    {{ $product->sku }}
                                    - {{ $product->name }}
                                 </option>

                                 @endforeach

                              </select>
                           </td>

                           <td>
                              <input type="number" name="items[0][quantity]" class="form-control quantity" min="1"
                                 value="1" required>
                           </td>

                           <td>
                              <input type="number" name="items[0][unit_price]" class="form-control unit-price" min="0"
                                 step="0.01" required>
                           </td>

                           <td>
                              <input type="text" class="form-control subtotal" value="0.00" readonly>
                           </td>

                           <td>
                              <button type="button" class="btn btn-danger remove-row">
                                 ×
                              </button>
                           </td>

                        </tr>

                     </tbody>

                  </table>

               </div>

               <button type="button" id="add-row" class="btn btn-outline-primary">
                  + Add Product
               </button>

            </div>

         </div>

         <div class="card mini-erp-card">

            <div class="card-body">

               <div class="d-flex justify-content-between align-items-center">

                  <h4 class="mb-0">
                     Total
                  </h4>

                  <h3 class="mb-0">
                     ₹<span id="grand-total">0.00</span>
                  </h3>

               </div>

               <div class="text-end mt-3">

                  <button type="submit" class="btn btn-success btn-lg">
                     Create Draft PO
                  </button>

               </div>

            </div>

         </div>

      </form>

   </div>

   <script>
   let rowIndex = 1;

   function calculateRow(row) {

      const quantity =
         parseFloat(row.querySelector('.quantity').value) || 0;

      const unitPrice =
         parseFloat(row.querySelector('.unit-price').value) || 0;

      const subtotal = quantity * unitPrice;

      row.querySelector('.subtotal').value =
         subtotal.toFixed(2);

      calculateTotal();
   }

   function calculateTotal() {

      let total = 0;

      document.querySelectorAll('.subtotal').forEach(input => {
         total += parseFloat(input.value) || 0;
      });

      document.getElementById('grand-total').textContent =
         total.toFixed(2);
   }

   document.addEventListener('change', function(event) {

      if (event.target.classList.contains('product-select')) {

         const row = event.target.closest('.item-row');

         const selectedOption =
            event.target.options[event.target.selectedIndex];

         const price =
            selectedOption.dataset.price || 0;

         row.querySelector('.unit-price').value = price;

         calculateRow(row);
      }
   });

   document.addEventListener('input', function(event) {

      if (
         event.target.classList.contains('quantity') ||
         event.target.classList.contains('unit-price')
      ) {
         calculateRow(
            event.target.closest('.item-row')
         );
      }
   });

   document.getElementById('add-row').addEventListener('click', function() {

      const container =
         document.getElementById('items-container');

      const firstRow =
         document.querySelector('.item-row');

      const newRow =
         firstRow.cloneNode(true);

      newRow.querySelector('.product-select').name =
         `items[${rowIndex}][product_id]`;

      newRow.querySelector('.quantity').name =
         `items[${rowIndex}][quantity]`;

      newRow.querySelector('.unit-price').name =
         `items[${rowIndex}][unit_price]`;

      newRow.querySelector('.product-select').value = '';

      newRow.querySelector('.quantity').value = 1;

      newRow.querySelector('.unit-price').value = '';

      newRow.querySelector('.subtotal').value = '0.00';

      container.appendChild(newRow);

      rowIndex++;
   });

   document.addEventListener('click', function(event) {

      if (event.target.classList.contains('remove-row')) {

         const rows =
            document.querySelectorAll('.item-row');

         if (rows.length > 1) {

            event.target
               .closest('.item-row')
               .remove();

            calculateTotal();
         }
      }
   });
   </script>

</body>

</html>