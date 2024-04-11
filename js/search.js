document.querySelector("#search-bar").addEventListener("keyup", (e) => {
    const search = e.target.value.toLowerCase();

    fetch('http://localhost/snippetmanager/php/posts.php?query=' + search)
        .then(response => response.json())
        .then(data => {
            const snippets = document.querySelectorAll(".snippet-post");
            snippets.forEach((snippet) => {
                if (JSON.stringify(data) === '{}' || data.some(item => item.snippet_id == snippet.dataset.id)) {
                    snippet.style.display = "flex";
                } else {
                    snippet.style.display = "none";
                }
            });
        })
        .catch(error => console.error(error));
})