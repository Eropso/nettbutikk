//navbar
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.profile')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

function showSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.style.display = 'flex';
}

function hideSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.style.display = 'none';
}



function changeQuantity(idOrAmount, amount) {
    // Hvis bare ett argument: bruk på produktsiden
    if (amount === undefined) {
        var quantityInput = document.getElementById('quantity');
        var currentValue = parseInt(quantityInput.value);
        if (currentValue + idOrAmount >= 1) {
            quantityInput.value = currentValue + idOrAmount;
        }
    } else {
        // Handlekurv: id og amount
        var quantityInput = document.getElementById('quantity_' + idOrAmount);
        var currentValue = parseInt(quantityInput.value);
        if (currentValue + amount >= 1) {
            quantityInput.value = currentValue + amount;
        }
    }
}