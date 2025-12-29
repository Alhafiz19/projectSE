<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Our Menu</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fafafa; margin: 0; padding: 0; padding-bottom: 80px; }
        
        /* HEADER */
        .header { background: #333; color: white; padding: 15px; display: flex; align-items: center; justify-content: center; position: relative; }
        .header h1 { margin: 0; font-size: 1.5rem; }
        
        .home-btn { position: absolute; left: 20px; color: white; text-decoration: none; font-weight: bold; border: 1px solid white; padding: 5px 15px; border-radius: 20px; transition: 0.3s; }
        .home-btn:hover { background: white; color: #333; }

        /* TABS */
        .tabs { display: flex; justify-content: center; background: white; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .tab-btn { flex: 1; padding: 15px; border: none; background: transparent; font-size: 18px; font-weight: bold; color: #555; cursor: pointer; border-bottom: 3px solid transparent; }
        .tab-btn.active { border-bottom: 3px solid #ff6b6b; color: #ff6b6b; }
        
        /* GRID */
        .menu-container { max-width: 1100px; margin: 20px auto; padding: 10px; display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
        
        /* CARD STYLES */
        .card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column; transition: 0.2s; border: 2px solid transparent; height: 100%; }
        
        /* IMAGE STYLES */
        .card-img { width: 100%; height: 160px; object-fit: cover; background: #f0f0f0; }
        
        /* INTERACTIVITY */
        .card.interactive { cursor: pointer; }
        .card.interactive:active { transform: scale(0.96); border-color: #ff6b6b; }
        .card.readonly { cursor: default; opacity: 0.8; }

        /* BODY CONTENT */
        .card-body { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .card-title { font-weight: bold; margin-bottom: 5px; font-size: 1.1em; color: #333; }
        .card-desc { font-size: 0.85em; color: #777; margin-bottom: 12px; line-height: 1.4; }
        .card-price { font-weight: bold; color: #e67e22; font-size: 1.2em; margin-top: auto; }
        
        /* UTILS */
        .hidden { display: none !important; }

        /* FLOATING BUTTON */
        .float-btn { position: fixed; bottom: 20px; right: 20px; color: white; padding: 15px 30px; border-radius: 50px; font-size: 1.1rem; font-weight: bold; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: 0.3s; z-index: 200; }
        .float-btn:hover { transform: scale(1.05); }
        .btn-pay { background: #2ecc71; }
        .btn-pay:hover { background: #27ae60; }
        .btn-checkin { background: #e67e22; }
        .btn-checkin:hover { background: #d35400; }
    </style>
</head>
<body>

    <div class="header">
        <a href="/" class="home-btn">← Home</a>
        <h1>Restaurant Menu</h1>
    </div>

    @if(!$canOrder)
        <div style="background: #fff3cd; color: #856404; text-align: center; padding: 10px; font-weight: bold; font-size: 0.9rem;">
            You are in View-Only mode. Please Check In to order.
        </div>
    @endif

    <div class="tabs">
        <button class="tab-btn active" onclick="setCategory('food')">FOOD</button>
        <button class="tab-btn" onclick="setCategory('beverage')">BEVERAGE</button>
    </div>

    <div id="menu-grid" class="menu-container">
        @forelse($menuItems as $item)
            <div class="card {{ $item->category === 'food' ? '' : 'hidden' }} {{ $canOrder ? 'interactive' : 'readonly' }}" 
                 data-category="{{ $item->category }}"
                 data-name="{{ $item->name }}"
                 data-price="{{ $item->price }}"
                 
                 @if($canOrder)
                    onclick="placeOrder(this)"
                 @endif
                 >
                 
                @if($item->image)
                    <img src="{{ $item->image }}" alt="{{ $item->name }}" class="card-img">
                @else
                    <div class="card-img" style="display: flex; align-items: center; justify-content: center; color: #999;">No Image</div>
                @endif
                 
                <div class="card-body">
                    <div>
                        <div class="card-title">{{ $item->name }}</div>
                        <div class="card-desc">{{ $item->description }}</div>
                    </div>
                    <div class="card-price">${{ number_format($item->price, 2) }}</div>
                    
                    @if($canOrder)
                        <small style="color:green; display:none; margin-top:5px; font-weight:bold;" class="added-msg">Added to Bill!</small>
                    @endif
                </div>
            </div>
        @empty
            <p style="text-align:center; width:100%; margin-top: 50px; color: #777;">Loading menu items...</p>
        @endforelse
    </div>

    @if($canOrder)
        <a href="/payment" class="float-btn btn-pay">Confirm & Pay →</a>
    @else
        <a href="/" class="float-btn btn-checkin">Check In to Order</a>
    @endif

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

            // Show 'Added' message
            const msg = element.querySelector('.added-msg');
            msg.style.display = 'block';
            setTimeout(() => msg.style.display = 'none', 1000);

            try {
                await fetch('/order', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ item_name: name, price: price })
                });
            } catch (err) { alert('Error adding item. Please try again.'); }
        }
    </script>
</body>
</html>