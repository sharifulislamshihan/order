<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Recipient ID</th>
                        <th class="py-3 px-6 text-left">Buyer Name</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Phone</th>
                        <th class="py-3 px-6 text-left">Note</th>
                        <th class="py-3 px-6 text-left">Buyer ID</th>
                        <th class="py-3 px-6 text-left">IP Address</th>
                        <th class="py-3 px-6 text-left">Created At</th>
                        <th class="py-3 px-6 text-left">Updated At</th>
                        <th class="py-3 px-6 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-6">{{ $order->id }}</td>
                            <td class="py-3 px-6">{{ $order->recipient_id }}</td>
                            <td class="py-3 px-6">{{ $order->buyer_name }}</td>
                            <td class="py-3 px-6">{{ $order->buyer_email }}</td>
                            <td class="py-3 px-6">{{ $order->phone_number }}</td>
                            <td class="py-3 px-6">{{ $order->note }}</td>
                            <td class="py-3 px-6">{{ $order->buyer_id }}</td>
                            <td class="py-3 px-6">{{ $order->ip_address }}</td>
                            <td class="py-3 px-6">{{ $order->created_at }}</td>
                            <td class="py-3 px-6">{{ $order->updated_at }}</td>
                            <td class="py-3 px-6">
                                <a href="{{ route('admin.edit', $order->id) }}"
                                    class="text-blue-600 hover:text-blue-800 mr-4">
                                    Edit
                                </a>


                                    <form action="{{ route('admin.delete', $order->id) }}" method="POST"
                                    style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
