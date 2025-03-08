<!DOCTYPE html>
<html>

<head>
    <title>Order Form</title>
    <script src="/assets/jquery.js"></script>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto grid grid-cols-3 gap-6">
        <!-- Form -->
        <div class="col-span-2 bg-white p-6 rounded shadow">
            <form id="orderForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="recipient_id" class="block text-gray-700 font-bold mb-2">Recipient ID</label>
                    <input id="recipient_id" name="recipient_id" class="w-full p-2 border" placeholder="e.g., ABC123"
                        required pattern="^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]+$"
                        title="Must contain both letters and numbers">
                </div>
                <div class="mb-4">
                    <label for="buyer_name" class="block text-gray-700 font-bold mb-2">Buyer Name</label>
                    <input id="buyer_name" name="buyer_name" class="w-full p-2 border" placeholder="Enter your name"
                        required>
                </div>
                <div class="mb-4">
                    <label for="buyer_email" class="block text-gray-700 font-bold mb-2">Buyer Email</label>
                    <input id="buyer_email" name="buyer_email" type="email" class="w-full p-2 border"
                        placeholder="e.g., john@example.com" required>
                </div>
                <div class="mb-4">
                    <label for="phone_number" class="block text-gray-700 font-bold mb-2">Phone Number</label>
                    <input id="phone_number" name="phone_number" class="w-full p-2 border"
                        placeholder="Enter your phone number" required>
                </div>
                <div class="mb-4">
                    <label for="note" class="block text-gray-700 font-bold mb-2">Note (Optional)</label>
                    <textarea id="note" name="note" class="w-full p-2 border" placeholder="Any additional notes"></textarea>
                </div>
                <div class="mb-4">
                    <label for="buyer_id" class="block text-gray-700 font-bold mb-2">Buyer ID (Optional)</label>
                    <input id="buyer_id" name="buyer_id" class="w-full p-2 border"
                        placeholder="Enter buyer ID if applicable">
                </div>
                <div class="mb-4">
                    <label for="attachment" class="block text-gray-700 font-bold mb-2">Attachment (Optional)</label>
                    <input id="attachment" type="file" name="attachment" accept=".jpg,.jpeg,.png" class="p-2">
                </div>
                <div id="products">
                    <div class="product mb-4 grid grid-cols-4 gap-2">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Product Name</label>
                            <input name="product_name[]" class="w-full p-2 border" placeholder="Enter product name"
                                required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Price</label>
                            <input name="price[]" type="number" step="0.01" class="w-full p-2 border"
                                placeholder="e.g., 10.00" required min="0">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Quantity</label>
                            <input name="quantity[]" type="number" min="1" class="w-full p-2 border"
                                placeholder="e.g., 1" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2 invisible">Action</label>
                            <button type="button"
                                class="remove-product bg-red-500 text-white p-2 w-full">Remove</button>
                        </div>
                    </div>
                </div>
                <button type="button" id="addProduct" class="bg-blue-500 text-white p-2 mb-4">+ Add Product</button>
                <button type="submit" class="bg-green-500 text-white p-2">Submit Order</button>
            </form>
        </div>
        <!-- Sidebar -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-bold mb-4">Order Summary</h2>
            <div id="summary"></div>
            <p class="font-bold mt-4">Total: $<span id="total">0.00</span></p>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#addProduct').click(function() {
                $('#products').append(`
                    <div class="product mb-4 grid grid-cols-4 gap-2">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Product Name</label>
                            <input name="product_name[]" class="w-full p-2 border" placeholder="Enter product name" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Price</label>
                            <input name="price[]" type="number" step="0.01" class="w-full p-2 border" placeholder="e.g., 10.00" required min="0">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Quantity</label>
                            <input name="quantity[]" type="number" min="1" class="w-full p-2 border" placeholder="e.g., 1" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2 invisible">Action</label>
                            <button type="button" class="remove-product bg-red-500 text-white p-2 w-full">Remove</button>
                        </div>
                    </div>
                `);
                updateSummary();
            });

            $(document).on('click', '.remove-product', function() {
                if ($('.product').length > 1) $(this).parent().parent().remove();
                updateSummary();
            });

            function updateSummary() {
                let summary = '';
                let total = 0;
                $('.product').each(function() {
                    let name = $(this).find('input[name="product_name[]"]').val();
                    let price = parseFloat($(this).find('input[name="price[]"]').val()) || 0;
                    let qty = parseInt($(this).find('input[name="quantity[]"]').val()) || 0;
                    let subtotal = price * qty;
                    if (name && price && qty) {
                        summary += `<p>${name} x${qty}: $${subtotal.toFixed(2)}</p>`;
                        total += subtotal;
                    }
                });
                $('#summary').html(summary);
                $('#total').text(total.toFixed(2));
            }

            $('#orderForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('order.submit') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('Order submitted successfully!');
                        $('#orderForm')[0].reset();
                        $('#products').html(`
                            <div class="product mb-4 grid grid-cols-4 gap-2">
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Product Name</label>
                                    <input name="product_name[]" class="w-full p-2 border" placeholder="Enter product name" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Price</label>
                                    <input name="price[]" type="number" step="0.01" class="w-full p-2 border" placeholder="e.g., 10.00" required min="0">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Quantity</label>
                                    <input name="quantity[]" type="number" min="1" class="w-full p-2 border" placeholder="e.g., 1" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2 invisible">Action</label>
                                    <button type="button" class="remove-product bg-red-500 text-white p-2 w-full">Remove</button>
                                </div>
                            </div>
                        `);
                        updateSummary();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('input', '.product input', updateSummary);
        });
    </script>
</body>

</html>
