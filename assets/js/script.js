document.addEventListener("DOMContentLoaded", function () {
    const cart = {};
    const itemCards = document.querySelectorAll(".item-card");
    const cartList = document.getElementById("cartList");
    const totalPriceEl = document.getElementById("totalPrice");
    const clearCartBtn = document.getElementById("clearCartBtn");
    const checkoutBtn = document.getElementById("checkoutBtn");

    itemCards.forEach(card => {
        const id = card.dataset.id;
        const cartQty = parseInt(card.querySelector(".cart").textContent);
        cart[id] = cartQty;
    });

    function updateDOM(id, change) {
        const card = document.querySelector(`.item-card[data-id="${id}"]`);
        const cartQtyEl = card.querySelector(".cart");
        const stockEl = card.querySelector(".stock");
        const price = parseFloat(card.querySelector(".price").textContent.replace("Price: $", ""));

        let cartQty = cart[id];
        let stock = parseInt(stockEl.textContent);

        if (change === 1 && stock > 0) {
            cartQty++;
            stock--;
        } else if (change === -1 && cartQty > 0) {
            cartQty--;
            stock++;
        }

        cart[id] = cartQty;
        cartQtyEl.textContent = cartQty;
        stockEl.textContent = stock;

        updateButtonState(id, cartQty);
        renderCart();
    }

    function updateButtonState(id, cartQty) {
        const minusBtn = document.getElementById(`minus-${id}`);
        if (minusBtn) {
            minusBtn.disabled = cartQty <= 0;
            minusBtn.style.cursor = cartQty <= 0 ? "not-allowed" : "pointer";
        }
    }

    function renderCart() {
        cartList.innerHTML = "";
        let total = 0;
        let hasItem = false;

        itemCards.forEach(card => {
            const id = card.dataset.id;
            const name = card.querySelector("h3").textContent;
            const price = parseFloat(card.querySelector(".price").textContent.replace("Price: $", ""));
            const qty = cart[id];

            if (qty > 0) {
                const itemTotal = qty * price;
                total += itemTotal;

                const li = document.createElement("li");
                li.textContent = `${name} x ${qty} = $${itemTotal.toFixed(2)}`;
                cartList.appendChild(li);

                hasItem = true;
            }

            updateButtonState(id, cart[id]);
        });

        totalPriceEl.textContent = total.toFixed(2);
        clearCartBtn.style.display = hasItem ? "inline-block" : "none";
        checkoutBtn.disabled = !hasItem;
        checkoutBtn.style.cursor = hasItem ? "pointer" : "not-allowed";
    }

    itemCards.forEach(card => {
        const id = card.dataset.id;
        card.querySelector(".plus").addEventListener("click", () => updateDOM(id, 1));
        card.querySelector(".minus").addEventListener("click", () => updateDOM(id, -1));
    });

    const adminBtn = document.getElementById("adminBtn");
    const adminModal = document.getElementById("adminModal");
    const closeBtn = document.querySelector(".closeBtn");

    adminBtn?.addEventListener("click", () => {
        adminModal.style.display = "block";
    });

    closeBtn?.addEventListener("click", () => {
        adminModal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === adminModal) {
            adminModal.style.display = "none";
        }
    });

    checkoutBtn.addEventListener("click", () => {
        const data = { cart: cart };
        fetch("checkout.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success && result.receipt_text) {
                showReceiptPopup(result.receipt_text, result.filename || "receipt.txt");
                setTimeout(() => location.reload(), 1000);
            } else {
                alert("Checkout failed: " + result.message);
            }
        });
    });

    clearCartBtn.addEventListener("click", () => {
        itemCards.forEach(card => {
            const id = card.dataset.id;
            const cartQty = cart[id];
            if (cartQty > 0) {
                const cartEl = card.querySelector(".cart");
                const stockEl = card.querySelector(".stock");

                stockEl.textContent = parseInt(stockEl.textContent) + cartQty;
                cartEl.textContent = 0;
                cart[id] = 0;
            }
        });

        renderCart();
    });

    function showReceiptPopup(receiptText, filename) {
        const popup = window.open('', '_blank', 'width=400,height=600');
        popup.document.write(`
            <html>
            <head>
                <title>Receipt</title>
                <link rel="stylesheet" href="assets/css/style.css">
            </head>
            <body>
                <pre>${receiptText}</pre>
                <div class="receipt-buttons">
                    <button class="ok-btn" onclick="window.close()">OK</button>
                    <button class="print-btn" onclick="downloadReceipt()">Print</button>
                </div>
                <script>
                    function downloadReceipt() {
                        const blob = new Blob([\`${receiptText}\`], { type: 'text/plain' });
                        const link = document.createElement('a');
                        link.href = URL.createObjectURL(blob);
                        link.download = '${filename}';
                        link.click();
                    }
                <\/script>
            </body>
            </html>
        `);
        popup.document.close();
    }

    renderCart();
});
