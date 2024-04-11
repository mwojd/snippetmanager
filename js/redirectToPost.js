

document.querySelector("body").addEventListener("click", (e) => {
    if(e.target.classList.contains("snippet-footer") || e.target.classList.contains("up-vote") || e.target.classList.contains("down-vote"))
        return;
    else {
        if(typeof e.target.parentNode.dataset.id != 'undefined')
            window.location.replace("http://localhost/snippetmanager/php/post.php?postID=" + e.target.parentNode.dataset.id)
    }

})