<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Our Menu</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fafafa; margin: 0; padding: 0; padding-bottom: 80px; } /* Padding for footer */
        .header { background: #333; color: white; padding: 15px; text-align: center; }
        .tabs { display: flex; justify-content: center; background: white; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .tab-btn { flex: 1; padding: 15px; border: none; background: transparent; font-size: 18px; font-weight: bold; color: #555; cursor: pointer; border-bottom: 3px solid transparent; }
        .tab-btn.active { border-bottom: 3px solid #ff6b6b; color: #ff6b6b; }
        .menu-container { max-width: 1000px; margin: 20px auto; padding: 10px; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; }
        .card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column; cursor: pointer; transition: 0.2s; border: 2px solid transparent; }
        .card:active { transform: scale(0.95); border-color: #ff6b6b; }
        .card-body { padding: 12px; }
        .card-title { font-weight: bold; }
        .card-price { font-weight: bold; color: #333; margin-top: 10px; }
        .hidden { display: none !important; }

        /* FLOATING CONFIRM BUTTON */
        .float-btn { position: fixed; bottom: 20px; right: 20px; background: #2ecc71; color: white; padding: 15px 30px; border-radius: 50px; font-size: 1.2rem; font-weight: bold; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: 0.3s; }
        .float-btn:hover { background: #27ae60; transform: scale(1.05); }
    </style>
</head>
<body>

    <div class="header"><h1>Restaurant Menu</h1></div>

    <div class="tabs">
        <button class="tab-btn active" onclick="setCategory('food')">FOOD</button>
        <button class="tab-btn" onclick="setCategory('beverage')">BEVERAGE</button>
    </div>

    <div id="menu-grid" class="menu-container">
        @forelse($menuItems as $item)
            <div class="card {{ $item->category === 'food' ? '' : 'hidden' }}" 
                 data-category="{{ $item->category }}"
                 data-name="{{ $item->name }}"
                 data-price="{{ $item->price }}"
                 onclick="placeOrder(this)">
                <div class="card-body">
                    <div class="card-title">{{ $item->name }}</div>
                    <div class="card-price">${{ number_format($item->price, 2) }}</div>
                    <small style="color:green; display:none;" class="added-msg">Added!</small>
                </div>
            </div>
        @empty
            <p style="text-align:center; width:100%;">No items found.</p>
        @endforelse
    </div>

    <a href="/payment" class="float-btn">Confirm & Pay →</a>

    <script>
        function setCategory(category) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');
            document.querySelectorAll('.card').forEach(c => {
                c.classList.toggle('hidden', c.getAttribute('data-category') !== category);
            });
        }

        async function placeOrder(element) {
            const name = element.getAttribute('data-name');
            const price = element.getAttribute('data-price');

            // Visual Feedback
            element.querySelector('.added-msg').style.display = 'block';
            setTimeout(() => element.querySelector('.added-msg').style.display = 'none', 1000);

            try {
                await fetch('/order', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ item_name: name, price: price })
                });
            } catch (err) { alert('Error adding item'); }
        }
    </script>
</body>
</html>