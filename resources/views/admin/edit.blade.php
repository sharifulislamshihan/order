<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Edit Order</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.update', $order->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="recipient_id" class="block text-sm font-medium text-gray-700">Recipient ID</label>
                <input type="text" name="recipient_id" id="recipient_id"
                    value="{{ old('recipient_id', $order->recipient_id) }}" required
                    class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="buyer_name" class="block text-sm font-medium text-gray-700">Buyer Name</label>
                <input type="text" name="buyer_name" id="buyer_name"
                    value="{{ old('buyer_name', $order->buyer_name) }}" required
                    class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="buyer_email" class="block text-sm font-medium text-gray-700">Buyer Email</label>
                <input type="email" name="buyer_email" id="buyer_email"
                    value="{{ old('buyer_email', $order->buyer_email) }}" required
                    class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number"
                    value="{{ old('phone_number', $order->phone_number) }}" required
                    class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="note" class="block text-sm font-medium text-gray-700">Note</label>
                <textarea name="note" id="note"
                    class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('note', $order->note) }}</textarea>
            </div>

            <div>
                <label for="buyer_id" class="block text-sm font-medium text-gray-700">Buyer ID</label>
                <input type="text" name="buyer_id" id="buyer_id" value="{{ old('buyer_id', $order->buyer_id) }}"
                    class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-700">Attachment</label>
                <input type="text" name="attachment" id="attachment"
                    value="{{ old('attachment', $order->attachment) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="ip_address" class="block text-sm font-medium text-gray-700">IP Address</label>
                <input type="text" name="ip_address" id="ip_address"
                    value="{{ old('ip_address', $order->ip_address) }}"
                    class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div id="products-container">
                @foreach ($order->products as $index => $product)
                    <div class="grid grid-cols-5 gap-4 mb-4 product-row">
                        <div>
                            <label>Product Name</label>
                            <input type="text" name="products[{{ $index }}][product_name]"
                                value="{{ $product->product_name }}" required
                                class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label>Price</label>
                            <input type="number" step="0.01" name="products[{{ $index }}][price]"
                                value="{{ $product->price }}" min="0" required
                                class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label>Quantity</label>
                            <input type="number" name="products[{{ $index }}][quantity]"
                                value="{{ $product->quantity }}" min="1" required
                                class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label>Total Price</label>
                            <input type="number" step="0.01" name="products[{{ $index }}][total_price]"
                                value="{{ $product->total_price }}" min="0" required
                                class="mt-1 block w-full py-2 pl-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="mt-6">
                            <button type="button"
                                class="btn btn-danger remove-product text-red-600 hover:text-red-800">Remove</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-product"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4">Add Product</button>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update
                Order</button>
        </form>
    </div>

    <script>
        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const index = container.getElementsByClassName('product-row').length;
            const row = `
                <div class="grid grid-cols-5 gap-4 mb-4 product-row">
                    <div>
                        <label>Product Name</label>
                        <input type="text" name="products[${index}][product_name]" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label>Price</label>
                        <input type="number" step="0.01" name="products[${index}][price]" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label>Quantity</label>
                        <input type="number" name="products[${index}][quantity]" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label>Total Price</label>
                        <input type="number" step="0.01" name="products[${index}][total_price]" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="mt-6">
                        <button type="button" class="text-red-600 hover:text-red-800 remove-product">Remove</button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', row);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product')) {
                e.target.closest('.product-row').remove();
            }
        });
    </script>
</body>

</html>
