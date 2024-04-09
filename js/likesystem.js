
//TODO: Add search functionality to the site
document.querySelector(".snippet-footer").addEventListener("click", (e) => {
    console.log(e.target.parentNode.parentNode.dataset.id);
    let action = "";
    if(e.target.classList.contains("up-vote"))
        action = "like";
    else if(e.target.classList.contains("down-vote"))
        action = "dislike";
        fetch('http://localhost/snippetmanager/php/likeSystem.php?postID=' + e.target.parentNode.parentNode.dataset.id + '&actionType=' + action)
        .then(response => response.json())
        .then(data => {
        })
        .catch(error => console.error(error));
})