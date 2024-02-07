
//TODO: Add search functionality to the site
document.querySelector("#search").addEventListener("keyup", (e) => {
    const search = e.target.value.toLowerCase();
    const items = document.querySelectorAll(".snippet-post");
    items.forEach((item) => {
        if (item.textContent.toLowerCase().indexOf(search) != -1) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
})