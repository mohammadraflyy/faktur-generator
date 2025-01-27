<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Form</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
</head>
<body class="font-sans p-4">
    <div class="container mx-auto">
        <form action="{{ route('generate.invoice') }}" method="POST" class="w-full" target="_blank">
            @csrf

            <div>
                <input class="border p-2 mb-2 w-full" type="text" name="invoice_items[receiver]" id="receiver" placeholder="Receiver">
            </div>

            <div class="mb-2">
                <textarea class="hidden" name="invoice_items[note]" id="note"></textarea>
                <div id="editor" class="border p-2">
                    <p>Keterangan</p>
                </div>
            </div>

            <div id="invoiceItems">
                <div class="invoice-item flex items-center space-x-2 w-full">
                    <input type="text" name="invoice_items[items][0][product_name]" placeholder="Product Name" required class="border px-2 py-2 w-full"">
                    <input type="text" name="invoice_items[items][0][unit]" placeholder="Unit" class="unit border px-2 py-2 w-full"" required>
                    <input type="number" name="invoice_items[items][0][qty]" placeholder="Qty" class="qty border px-2 py-2 w-full"" required>
                    <input type="number" name="invoice_items[items][0][price]" placeholder="Price" class="price border px-2 py-2 w-full"" required>
                    <button type="button" class="removeItem bg-red-500 text-white px-2 py-2 rounded">Remove</button>
                </div>
            </div>

            <button type="button" id="addItem" class="bg-blue-500 text-white px-4 py-2 rounded">Add Item</button>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mt-4">Generate PDF</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        const quill = new Quill('#editor', {
            theme: 'snow'
        });

        const form = document.querySelector('form');
        form.addEventListener('submit', () => {
            const noteContent = quill.root.innerHTML;
            document.getElementById('note').value = noteContent;
        });

        let itemCount = 1;

        document.getElementById('addItem').addEventListener('click', () => {
            const newInvoiceItem = `
                <div class="invoice-item flex items-center space-x-2 w-full mt-2">
                    <input type="text" name="invoice_items[items][${itemCount}][product_name]" placeholder="Product Name" required class="border px-2 py-2 w-full">
                    <input type="text" name="invoice_items[items][${itemCount}][unit]" placeholder="Unit" class="unit border px-2 py-2 w-full" required>
                    <input type="number" name="invoice_items[items][${itemCount}][qty]" placeholder="Qty" class="qty border px-2 py-2 w-full" required>
                    <input type="number" name="invoice_items[items][${itemCount}][price]" placeholder="Price" class="price border px-2 py-2 w-full" required>
                    <button type="button" class="removeItem bg-red-500 text-white px-2 py-2 rounded">Remove</button>
                </div>
            `;

            document.getElementById('invoiceItems').insertAdjacentHTML('beforeend', newInvoiceItem);

            document.querySelectorAll('.removeItem').forEach((button) => {
                button.addEventListener('click', handleRemoveItem);
            });

            itemCount++;
        });

        function handleRemoveItem(event) {
            const itemElement = event.target.closest('.invoice-item');
            if (itemElement) {
                itemElement.remove();
            }
        }

        document.querySelectorAll('.removeItem').forEach((button) => {
            button.addEventListener('click', handleRemoveItem);
        });
    </script>
</body>
</html>
