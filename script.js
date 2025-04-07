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



function changeQuantity(amount) {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);

    // Ensure the value doesn't go below 1
    if (currentValue + amount >= 1) {
        quantityInput.value = currentValue + amount;
    }
}