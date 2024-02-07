
//TODO: Add search functionality to the site
document.querySelector("#search-bar").addEventListener("keyup", (e) => {
    const search = e.target.value.toLowerCase();
    const items = document.querySelectorAll(".snippet-post");
    items.forEach((item) => {
        let snippetObj = JSON.parse(item.dataset.info);
        let data = snippetObj.snippet_title + snippetObj.description + snippetObj.username + snippetObj.snippet_code + (item.querySelector(".snippet-body > .snippet-code > pre > code").classList[1]).split("-")[1];
        if(data.toLowerCase().includes(search)){
            item.style.display = "flex";
        } else {
            item.style.display = "none";
        }
    });
})