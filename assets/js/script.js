document.addEventListener("DOMContentLoaded", function () {
    const cart = {};
    const itemCards = document.querySelectorAll(".item-card");
    const cartList = document.getElementById("cartList");
    const totalPriceEl = document.getElementById("totalPrice");
    const clearCartBtn = document.getElementById("clearCartBtn");
    const checkoutBtn = document.getElementById("checkoutBtn");

    // Initialize cart from DOM
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

            // Update minus button state on every render
            updateButtonState(id, cart[id]);
        });

        totalPriceEl.textContent = total.toFixed(2);

        // Toggle Clear Cart button visibility
        clearCartBtn.style.display = hasItem ? "inline-block" : "none";

        // Enable or disable Checkout button
        checkoutBtn.disabled = !hasItem;
        checkoutBtn.style.cursor = hasItem ? "pointer" : "not-allowed";
    }

    // Attach + and - buttons
    itemCards.forEach(card => {
        const id = card.dataset.id;
        card.querySelector(".plus").addEventListener("click", () => updateDOM(id, 1));
        card.querySelector(".minus").addEventListener("click", () => updateDOM(id, -1));
    });

    // Admin modal logic
    const adminBtn = document.getElementById("adminBtn");
    const adminModal = document.getElementById("adminModal");
    const closeBtn = document.querySelector(".closeBtn");

    adminBtn.addEventListener("click", () => {
        adminModal.style.display = "block";
    });

    closeBtn.addEventListener("click", () => {
        adminModal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === adminModal) {
            adminModal.style.display = "none";
        }
    });

    // Checkout handler
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

            if (result.success) {
                alert("Checkout successful!");

                if (result.receipt_url && result.filename) {
                    const link = document.createElement("a");
                    link.href = result.receipt_url;
                    link.download = result.filename;  // use actual filename (e.g., receipt_1.txt)
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }

                setTimeout(() => location.reload(), 500);
            }
            else {
                alert("Checkout failed: " + result.message);
            }
        });
    });

    // Clear Cart handler
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

    // Initial render
    renderCart();
});
