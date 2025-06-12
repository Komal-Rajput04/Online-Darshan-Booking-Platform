/*Get elements
const menuButton = document.getElementById("mButton");
const dropdownMenu = document.getElementById("dropdownMenu");

// Toggle menu visibility
menuButton.addEventListener("click", function (event) {
    event.stopPropagation(); // Prevents immediate closing when clicking button
    dropdownMenu.style.display = (dropdownMenu.style.display === "block") ? "none" : "block";
});

// Close dropdown when clicking outside
document.addEventListener("click", function (event) {
    if (!mButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = "none";
    }
});*/
const templeModal = document.getElementById('templeModal'); 
templeModal.addEventListener('show.bs.modal', function (event) {
    const trigger = event.relatedTarget;
    const title = trigger.getAttribute('data-title');
    const description = trigger.getAttribute('data-description');

     // Replace tabs with spacing and newlines with <br>
    const formattedDesc = description
        .replace(/\\t/g, '&emsp;&emsp;')
        .replace(/\\n/g, '<br>');

    templeModal.querySelector('.modal-title').textContent = title;
    templeModal.querySelector('#templeModalDescription').innerHTML = formattedDesc;
}); 

